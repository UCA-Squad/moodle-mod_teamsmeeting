<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Mandatory public API of teamsmeeting module
 *
 * @package    mod_teamsmeeting
 * @copyright  2022 Anthony Durif, Université Clermont Auvergne
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Graph.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Core/GraphConstants.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Http/GraphRequest.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Http/GraphResponse.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Entity.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/DirectoryObject.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/User.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Group.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Team.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/OnlineMeeting.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/OnlineMeetingInfo.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Identity.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/IdentitySet.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/MeetingParticipantInfo.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/MeetingParticipants.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Recipient.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/AttendeeBase.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Attendee.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/DateTimeTimeZone.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/ItemBody.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/OutlookItem.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/vendor/microsoft/microsoft-graph/src/Model/Event.php');
require_once($CFG->dirroot . '/mod/teamsmeeting/classes/Office365.php');
require_once($CFG->dirroot . '/calendar/lib.php');

/**
 * List of features supported in Folder module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function teamsmeeting_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return false;
        case FEATURE_SHOW_DESCRIPTION:        return true;
        default: return null;
    }
}

/**
 * Add teams meeting instance.
 * @param object $data the form data.
 * @param object $mform the form.
 * @return int the new teams instance id
 */
function teamsmeeting_add_instance($data, $mform) {
    global $CFG, $DB, $USER, $COURSE;

    require_once($CFG->dirroot.'/mod/url/locallib.php');

    if (!empty($data->name)) {
        // Fixing default display options.
        $displayoptions = array();
        $data->display = RESOURCELIB_DISPLAY_NEW;
        $data->displayoptions = serialize($displayoptions);
        $data->intro = $data->intro;
        $data->introformat = "1";
        $data->timemodified = time();

        try {
            $office = teamsmeeting_get_office();
            $userid = $office->getUserId($USER->email);
        } catch (Throwable $th) {
            new Exception(get_string('notfound', 'mod_teamsmeeting'));
        }

        if (empty($data->useopendate)) {
            $data->opendate = 0;
        }
        if (empty($data->useclosedate)) {
            $data->closedate = 0;
        }

        // Online meeting creation.
        $meeting = ($data->reuse_meeting == 0)
            ? $office->createBroadcastEvent($data->name, $data->opendate, $data->closedate, $USER)
            : $office->createOnlineMeeting($userid, $data->name);
        $data->resource_teams_id = $meeting->getId();
        $data->externalurl = ($data->reuse_meeting == 0) ? $meeting->getOnlineMeeting()->getJoinUrl() : $meeting->getJoinWebUrl();
        if ($data->reuse_meeting == 0) {
            // We match the dates of the Teams calendar event with Moodle calendar.
            $data->opendate = ($data->opendate > 0) ? $data->opendate : strtotime($meeting->getStart()->getDateTime());
            $data->closedate = ($data->closedate > 0) ? $data->closedate : strtotime($meeting->getEnd()->getDateTime());

            if ($data->externalurl != null) {
                if (get_config('mod_teamsmeeting', 'notif_mail') == true) {
                    // Send meeting link to the creator.
                    $text = sprintf(get_string('create_mail_content', 'mod_teamsmeeting'), $data->name, $COURSE->fullname);
                    $html = html_writer::start_tag('div') . PHP_EOL;
                    $html .= html_writer::tag('p', str_replace("\\n", "<br>", $text)) . PHP_EOL;
                    $text .= $meeting->getJoinWebUrl();
                    $html .= html_writer::link($meeting->getJoinWebUrl() , $meeting->getJoinWebUrl(), array('target' => '_blank'));
                    $html .= html_writer::end_tag('div') . PHP_EOL;

                    // Creation notification.
                    $message = new \core\message\message();
                    $message->courseid = $COURSE->id;
                    $message->component = 'mod_teamsmeeting';
                    $message->name = 'meetingconfirm';
                    $message->userfrom = get_admin();
                    $message->userto = $USER;
                    $message->subject = get_string('create_mail_title', 'mod_teamsmeeting');
                    $message->fullmessage = $text;
                    $message->fullmessageformat = FORMAT_PLAIN;
                    $message->fullmessagehtml = $html;
                    $message->smallmessage = get_string('create_mail_title', 'mod_teamsmeeting');
                    $message->notification = 1;
                    message_send($message);
                }
            }
        }

        $data->creator_id = $USER->id;
        $data->id = $DB->insert_record('teamsmeeting', $data); // Insert in database.
        teamsmeeting_set_events($data); // Create meeting events if defined.
    }

    return $data->id;
}

/**
 * Update teams meeting instance.
 * @param object $data the form data.
 * @param object $mform the form.
 * @return bool true if update ok and false in other cases.
 */
function teamsmeeting_update_instance($data, $mform) {
    global $CFG, $DB, $USER;

    require_once($CFG->dirroot.'/mod/url/locallib.php');

    if (!empty($data->name)) {
        // Fixing default display options.
        $displayoptions = array();
        $data->display = RESOURCELIB_DISPLAY_NEW;
        $data->displayoptions = serialize($displayoptions);
        $data->intro = $data->intro;
        $data->introformat = "1";
        $data->timemodified = time();

        if (empty($data->useopendate)) {
            $data->opendate = 0;
        }
        if (empty($data->useclosedate)) {
            $data->closedate = 0;
        }

        $meeting = $DB->get_record('teamsmeeting', array('id' => $data->instance));
        if ($data->opendate != $meeting->opendate || $data->closedate != $meeting->closedate || $meeting->name != $data->name) {
            // We update the event dates.
            try {
                $office = teamsmeeting_get_office();
                $creator = $DB->get_record('user', array('id' => $meeting->creator_id));
                $userid = $office->getUserId($creator->email);
            } catch (Throwable $th) {
                new Exception(get_string('notfound', 'mod_teamsmeeting'));
            }
            $teamsmeeting = $office->updateBroadcastEvent($meeting->resource_teams_id, $data, $creator);
            if ($data->reuse_meeting == 0) {
                // We match the dates of the Teams calendar event with Moodle calendar.
                $data->opendate = ($data->opendate > 0) ? $data->opendate : strtotime($teamsmeeting->getStart()->getDateTime());
                $data->closedate = ($data->closedate > 0) ? $data->closedate : strtotime($teamsmeeting->getEnd()->getDateTime());
            }
        }

        $data->creator_id = (isset($data->creator_id)) ? $data->creator_id : $USER->id;

        $data->id = $data->instance;
        teamsmeeting_set_events($data); // Create meeting events if defined.

        $DB->update_record('teamsmeeting', $data);

        return true;
    }

    return false;
}

/**
 * Delete teams meeting instance.
 * @param int $id the id of the teams instance to delete
 * @return bool true.
 */
function teamsmeeting_delete_instance($id) {
    global $DB;

    if (!$team = $DB->get_record('teamsmeeting', array('id' => $id))) {
        return false;
    }

    $cm = get_coursemodule_from_instance('teamsmeeting', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'teamsmeeting', $id, null);

    // Note: all context files are deleted automatically.
    $DB->delete_records('teamsmeeting', array('id' => $team->id));

    return true;
}

/**
 * Given a coursemodule object, this function returns the extra information needed to print this activity in various places.
 * Function adapted from the url_get_coursemodule_info function.
 * @param cm_info $coursemodule the course module.
 * @return cached_cm_info info
 */
function teamsmeeting_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;
    require_once("$CFG->dirroot/mod/url/locallib.php");

    if (!$resource = $DB->get_record('teamsmeeting', array('id' => $coursemodule->instance),
        'id, course, name, display, displayoptions, externalurl, intro, introformat, opendate, closedate, reuse_meeting, resource_teams_id, creator_id, timemodified')) {
        return null;
    }

    $info = new cached_cm_info();
    $info->name = $resource->name;

    $display = url_get_final_display_type($resource);

    if ($display == RESOURCELIB_DISPLAY_POPUP) {
        $fullurl = "$CFG->wwwroot/mod/teamsmeeting/view.php?id=$coursemodule->id&amp;redirect=1";
        $options = empty($resource->displayoptions) ? array() : unserialize($resource->displayoptions);
        $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
        $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
        $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
        $info->onclick = "window.open('$fullurl', '', '$wh'); return false;";
    } else if ($display == RESOURCELIB_DISPLAY_NEW) {
        $fullurl = "$CFG->wwwroot/mod/teams/view.php?id=$coursemodule->id&amp;redirect=1";
        $info->onclick = "window.open('$fullurl'); return false;";
    }

    if ($coursemodule->showdescription) {
        // Convert intro to html. Do not filter cached version, filters run at display time.
        $info->content = format_module_intro('teamsmeeting', $resource, $coursemodule->id, false);
    }

    $course = get_course($resource->course); // Get cached course.
    $info->customdata = array('fullurl' => str_replace('&amp;', '&', url_get_full_url($resource, $coursemodule, $course)));

    return $info;
}


/**
 * Return the Office365 object to do API calls.
 * @return Office365
 * @throws dml_exception
 */
function teamsmeeting_get_office()
{
    return new Office365(get_config('mod_teamsmeeting', 'tenant_id'), get_config('mod_teamsmeeting', 'client_id'), get_config('mod_teamsmeeting', 'client_secret'));
}

/**
 * Add calendar events if startdate or/and closedate are enabled for the online meeting.
 * @param $meeting online meeting.
 * @throws coding_exception
 * @throws dml_exception
 */
function teamsmeeting_set_events($meeting) {
    global $DB;

    if ($events = $DB->get_records('event', array('modulename' => 'teamsmeeting', 'instance' => $meeting->id))) {
        foreach ($events as $event) {
            $event = calendar_event::load($event);
            $event->delete();
        }
    }

    // The open-event.
    $event = new stdClass;
    $event->description = $meeting->name;
    $event->courseid = $meeting->course;
    $event->groupid = 0;
    $event->userid = 0;
    $event->modulename = 'teamsmeeting';
    $event->instance = $meeting->id;
    $event->eventtype = 'open';
    $event->timestart = $meeting->opendate;
    $event->visible = instance_is_visible('teamsmeeting', $meeting);
    $event->timeduration = ($meeting->closedate - $meeting->opendate);

    if ($meeting->closedate && $meeting->opendate && $meeting->timeduration > 0) {
        // Single event for the whole questionnaire.
        $event->name = $meeting->name;
        calendar_event::create($event);
    } else {
        // Separate start and end events.
        $event->timeduration  = 0;
        if ($meeting->opendate) {
            $event->name = $meeting->name . get_string('opendate_session', 'mod_teamsmeeting');
            calendar_event::create($event);
            unset($event->id); // So we can use the same object for the close event.
        }
        if ($meeting->closedate) {
            $event->name = $meeting->name . get_string('closedate_session', 'mod_teamsmeeting');
            $event->timestart = $meeting->closedate;
            $event->eventtype = 'close';
            calendar_event::create($event);
        }
    }
}

/**
 * Prints team info and link to the teams meeting resource.
 * @param object $meeting the meeting.
 * @param object $cm the course module.
 * @param object $course the course.
 * @return does not return
 */
function teamsmeeting_print_workaround($meeting, $cm, $course) {
    global $OUTPUT;

    url_print_header($meeting, $cm, $course);
    url_print_heading($meeting, $cm, $course, true);
    url_print_intro($meeting, $cm, $course, true);

    $fullurl = url_get_full_url($meeting, $cm, $course);

    $display = url_get_final_display_type($meeting);
    if ($display == RESOURCELIB_DISPLAY_POPUP) {
        $jsfullurl = addslashes_js($fullurl);
        $options = empty($meeting->displayoptions) ? array() : unserialize($meeting->displayoptions);
        $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
        $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
        $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
        $extra = "onclick=\"window.open('$jsfullurl', '', '$wh'); return false;\"";
    } else if ($display == RESOURCELIB_DISPLAY_NEW) {
        $extra = "onclick=\"this.target='_blank';\"";
    } else {
        $extra = '';
    }

   echo teamsmeeting_print_details_dates($meeting);

    echo '<div class="urlworkaround">';
    print_string('clicktoopen', 'url', "<a id='teams_resource_url' href=\"$fullurl\" $extra>$fullurl</a>");
    echo '</div>';

    echo '<br><div id="meeting_url_copydiv"><button class="btn btn-default" id="meeting_url_copybtn">';
    echo html_writer::tag('img', '', array('src' => $OUTPUT->image_url('e/insert_edit_link', 'core'), 'style' => 'margin-right: 5px;'));
    echo get_string('copy_link', 'mod_teamsmeeting'). '</button></div>';

    // Script to copy the link.
    echo '<script>
            var btn = document.getElementById(\'meeting_url_copybtn\');
            btn.addEventListener(\'click\', function(event) {
            var div = document.querySelector(\'#meeting_url_copydiv\');
            var input = div.appendChild(document.createElement("input"));
            input.value = document.querySelector(\'#teams_resource_url\').innerHTML;
            input.focus();
            input.select();
            document.execCommand(\'copy\');
            input.parentNode.removeChild(input);
            });
        </script>';

    echo $OUTPUT->footer();
    die;
}

/**
 * Prints information about the availability of the online meeting.
 * @param $meeting the teams meeting instance.
 * @param string $format the format ('html' by default, 'text' can be used for notification).
 * @return string the information about the meeting.
 * @throws coding_exception
 */
function teamsmeeting_print_details_dates($meeting, $format = 'html')
{
    global $OUTPUT;
    if ($meeting->opendate != 0) {
        $details = ($meeting->closedate != 0)
            ? sprintf(get_string('dates_between', 'mod_teamsmeeting'), date('d/m/Y H:i', $meeting->opendate), date('d/m/Y H:i', $meeting->closedate))
            : sprintf(get_string('dates_from', 'mod_teamsmeeting'), date('d/m/Y H:i', $meeting->opendate));
    } else if ($meeting->closedate != 0) {
        $details = sprintf(get_string('dates_until', 'mod_teamsmeeting'), date('d/m/Y H:i', $meeting->closedate));
    }
    if ($details) {
        $msg = sprintf(get_string('meetingavailable', 'mod_teamsmeeting'), $details);
        $icon = html_writer::tag('img', '', array('src' => $OUTPUT->image_url('i/info'), 'style' => 'margin-right: 5px;'));
        return ($format == 'html') ? '<div>'. $icon . $msg .'</div><br/>' : $msg;
    }

    return '';
}