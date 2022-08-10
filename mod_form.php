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
 * Teams meeting configuration form
 *
 * @package   mod_teamsmeeting
 * @copyright 2022 Anthony Durif, Université Clermont Auvergne
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/url/locallib.php');
require_once($CFG->dirroot.'/mod/teamsmeeting/lib.php');
require_once($CFG->dirroot.'/mod/teamsmeeting/vendor/autoload.php');

class mod_teamsmeeting_mod_form extends moodleform_mod
{
    /**
     * Form construction.
     * @throws Exception
     * @throws coding_exception
     */
    function definition() {
        global $USER;
        $mform = $this->_form;
        $error = null;

        $office = teamsmeeting_get_office();
        try {
            $userId = $office->getUserId($USER->email);
        } catch (Throwable $th) {
            $error = $th->getMessage();
            $userId = null;
        }

        $has_account = (!empty($userId));

        if ($has_account) {
            // Current user had a correct account.
            $edit_ok = true;
            $default_reuse = ($this->current->id) ? $this->current->reuse_meeting : 1;
            $meetingexists = true;

            if (!empty($this->current->id)) {

                // Resource mod edition.
                try {
                    $office->getMeetingObject($this->current);
                    $meetingexists = true;
                } catch (Exception $e) {
                    $meetingexists = false;
                }
            }

            if ($edit_ok) {
                if ($meetingexists) {
                    $mform->addElement('header', 'general', get_string('general'));

                    $mform->addElement('text', 'name', get_string('name', 'mod_teamsmeeting'), 'size=80');
                    $mform->addRule('name', null, 'required', null, 'client');
                    $mform->setType('name', PARAM_TEXT);
                    $mform->addHelpButton('name', 'name', 'mod_teamsmeeting');

                    $mform->addElement('hidden', 'resource_teams_id');
                    $mform->setType('resource_teams_id', PARAM_TEXT);
                    if ($this->current->id) {
                        $mform->setDefault('resource_teams_id', $this->current->resource_teams_id);
                    }

                    $this->standard_intro_elements();
                    $element = $mform->getElement('introeditor');
                    $attributes = $element->getAttributes();
                    $attributes['rows'] = 5;
                    $element->setAttributes($attributes);

                    $reusearray = [];
                    $reusearray[] = $mform->createElement('radio', 'reuse_meeting', '', get_string('reuse_meeting_yes', 'mod_teamsmeeting'), 1);
                    $reusearray[] = $mform->createElement('radio', 'reuse_meeting', '', get_string('reuse_meeting_no', 'mod_teamsmeeting'), 0);
                    $mform->addGroup($reusearray, 'reuse_meeting', get_string('reuse_meeting', 'mod_teamsmeeting'), array(' '), false);
                    $mform->addHelpButton('reuse_meeting', 'reuse_meeting', 'mod_teamsmeeting');
                    $mform->setDefault('reuse_meeting', $default_reuse);
                    //$mform->disabledIf('reuse_meeting', 'team_id', 'neq', ''); // Disable if we edit the resource

                    $enableopengroup = [];
                    $enableopengroup[] =& $mform->createElement('date_time_selector', 'opendate', '');
                    $enableopengroup[] =& $mform->createElement('checkbox', 'useopendate', get_string('enable', 'moodle'));
                    $mform->addGroup($enableopengroup, 'enableopengroup', get_string('opendate', 'mod_teamsmeeting'), ' ', false);
                    $mform->addHelpButton('enableopengroup', 'opendate', 'mod_teamsmeeting');
                    $mform->disabledIf('enableopengroup', 'useopendate', 'notchecked');

                    $enableclosegroup = [];
                    $enableclosegroup[] =& $mform->createElement('date_time_selector', 'closedate', '');
                    $enableclosegroup[] =& $mform->createElement('checkbox', 'useclosedate', get_string('enable', 'moodle'));
                    $enableclosegroup[] =& $mform->addGroup($enableclosegroup, 'enableclosegroup', get_string('closedate', 'mod_teamsmeeting'), ' ', false);
                    $mform->addHelpButton('enableclosegroup', 'closedate', 'mod_teamsmeeting');
                    $mform->disabledIf('enableclosegroup', 'useclosedate', 'notchecked');

                    $mform->hideIf('enableopengroup', 'reuse_meeting', 'eq', 1);
                    $mform->hideIf('enableclosegroup', 'reuse_meeting', 'eq', 1);

                    $dateitems = [];
                    $dateitems[] =& $mform->createElement('html', get_string('dates_help', 'mod_teamsmeeting'));
                    $dategroup = $mform->createElement('group', 'group_date', false, $dateitems, null, false);
                    $mform->addElement($dategroup);

                    $this->standard_coursemodule_elements();
                    $this->add_action_buttons();
                } else {
                    // Resource not found (edit mode).
                    $this->standard_hidden_coursemodule_elements();
                    notice(get_string('meetingnotfound', 'mod_teamsmeeting'), new moodle_url('/course/view.php', array('id' => $this->current->course)));
                    die;
                }
            } else {
                // The current user does not have the rights to edit the resource info.
                $this->standard_hidden_coursemodule_elements();
                $mform->addElement('html', '<div class="alert alert-danger">' . get_string('no_owner', 'teamsmeeting', $error) . '</div>');
                $mform->addElement('cancel', '', get_string('back', 'teamsmeeting'));
            }
        } else {
            // The current user does not have an Azure AD account -> impossible to add a teams meeting instance.
            $this->standard_hidden_coursemodule_elements();
            $mform->addElement('html', '<div class="alert alert-danger">' . get_string('noaccount', 'teamsmeeting', $error) . '</div>');
            $mform->addElement('cancel', '', get_string('back', 'teamsmeeting'));
        }
    }

    /**
     * Form validation.
     * @param array $data
     * @param array $files
     * @return array
     */
    function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        // Checks dates choice consistency.
        if (isset($data['useopendate']) && isset($data['useclosedate']) && $data['closedate'] < $data['opendate']) {
            $errors['enableclosegroup'] = get_string('error_dates', 'mod_teamsmeeting');
        }
        // Checks close date is not already past.
        if (isset($data['useclosedate']) && $data['closedate'] < time()) {
            $errors['enableclosegroup'] = get_string('error_dates_past', 'mod_teamsmeeting');
        }

        return $errors;
    }

    /**
     * Fix default values for date fields.
     * @param array $defaultvalues
     */
    public function data_preprocessing(&$defaultvalues) {
        global $DB;
        if (empty($defaultvalues['opendate'])) {
            $defaultvalues['useopendate'] = 0;
        } else {
            $defaultvalues['useopendate'] = 1;
        }
        if (empty($defaultvalues['closedate'])) {
            $defaultvalues['useclosedate'] = 0;
        } else {
            $defaultvalues['useclosedate'] = 1;
        }
    }

    /**
     * Allows modules to modify the data returned by form get_data().
     * This method is also called in the bulk activity completion form.
     *
     * Only available on moodleform_mod.
     *
     * @param stdClass $data the form data to be modified.
     */
    public function data_postprocessing($data) {
        parent::data_postprocessing($data);
        if (!empty($data->completionunlocked)) {
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionentriesenabled) || !$autocompletion) {
                $data->completionentries = 0;
            }
        }
    }

}
