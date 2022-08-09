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
 * Strings for component 'teams_meeting', language 'en'
 *
 * @package   mod_teams_meeting
 * @copyright 2022 Anthony Durif, Université Clermont Auvergne
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['modulename'] = 'Teams meeting';
$string['modulename_help'] = 'Mod which permits to create a Teams online meeting and to display a link to it.';
$string['modulenameplural'] = 'Teams meetings';
$string['pluginname'] = 'Teams meeting';
$string['pluginadministration'] = 'Teams meeting';
$string['teams_meeting:addinstance'] = 'Add a new Teams online meeting';
$string['teams_meeting:view'] = 'View a Teams online meeting';
$string['privacy:metadata'] = 'Teams meeting plugin does not store or transmit any personal data.';
$string['notunique'] = 'The email is not unique in Azure Active Directory.';
$string['notfound'] = 'Email not found in Azure Active Directory.';

$string['name'] = 'Meeting name';
$string['name_help'] = 'Meeting name which will be displayed on your course page.';
$string['desc'] = '<div class="alert alert-info">Team creation is an action which can take few seconds.<br/>
                            It is also possible you do not have a direct access to this team, the access update can take few minutes. Thanks for your understanding.</div>';
$string['noaccount'] = '<p>It seems you do not have any microsoft account, so you cannot create a Team for now.</p>
                               <p>With your account created come back here to create your team from moodle.</p>';
$string['teamserror'] = '<p>It seems an error occured during your team creation: "{$a}".</p>
                                <p>Please retry this operation and contact the support if this problem persists.</p>';
$string['back'] = 'Return to course';

$string['opendate'] = 'Start date of the meeting';
$string['opendate_help'] = 'Start date of the meeting. If this option is not selected the meeting will be avalaible since its creation.';
$string['opendate_session'] = ' (Teams meeting session start)';
$string['closedate'] = 'Closing date of the meeting';
$string['closedate_help'] = 'Closing date of the meeting. If this option is not selected the meeting will be avalaible until another action from one of the organizers.';
$string['closedate_session'] = ' (Teams meeting session end)';
$string['dates_help'] = '<div class="alert alert-info"><strong>Be careful, students and other course users will not receive email notifications for participate tothis meeting.</strong>
                                    <ul><li>One shot meeting: <ul><li>The meeting will only be displayed on its creator Teams calendar. Students see this meeting on the course section where it has been added, on the "Upcoming events" course block and the Moodle calender (think to add theses blocks if needed).</li>
                                    <li>If you do not select a start date or an end date, a default period will be used (since the hour of the meeting creation and using a default duration value set in moodle administration). Tests will be made on moodle when you click on the activity link to redirect you or not to the Teams meeting in function of these period.</li></ul></li>
                                    <li>Permanent meeting: The meeting is only available and visible on the course section where it has been added and is usable since its creation.</li></ul>
                                    <p>Important: All changes about teams and meetings directly made in Teams (update of the meeting name, dates...) will not be reflected on Moodle.</p>';
$string['dates_between'] = 'between %s and %s';
$string['dates_from'] = 'from %s';
$string['dates_until'] = 'until %s';
$string['error_dates'] = 'Closing date must be later than start date.';
$string['error_dates_past'] = 'The period you defined cannot correpond to an old period.';
$string['meetingnotfound'] = 'Access to this team seems possible. A problem may be running on Microsoft Server, in this cas retry to connect later. It is also possible an organizer delete this meeting.';
$string['meetingnotavailable'] = 'Access to this meeting (virtual classroom) is not available.%s In case of difficulties please contact your course manager(s).';
$string['meetingavailable'] = 'Teams online meeting is available %s.';
$string['description'] = 'Team created for the course "%s".';
$string['copy_link'] = 'Copy the resource link to the keyboard';
$string['create_mail_content'] = 'Hello,\nYou have just created the Teams online meeting "%s" on your Moodle course "%s".\nYou can find this meeting by clicking on this link : ';
$string['create_mail_title'] = 'New Teams online meeting created';
$string['messageprovider:meetingconfirm'] = 'Confirmation of the Teams online meeting creation';
$string['notif_mail'] = 'Online meeting creation notification';
$string['notif_mail_help'] = 'Send a notification after the creation of an online meeting with the link to it.';

$string['reuse_meeting'] = 'Utilisation ?';
$string['reuse_meeting_help'] = 'Meeting utilisation:
                                <ul><li>Reusable: the generated meeting url will be accessible by enrolled users since its creation (unless you defines moodle access restrictions).</li>
                                <li>One shot: the meeting url is immediatly available for its creator. For other users Moodle will test its availability compared to the period defined in the form before doing the redirection to the meeting.</li></ul>';
$string['reuse_meeting_no'] = 'One shot';
$string['reuse_meeting_yes'] = 'Permanent';
$string['meeting_default_duration'] = 'Default duration for the meetings if a closedate is not given';
$string['meeting_default_duration_help'] = 'Default duration  for the meetings created with Teams activit if a close is not given. This closedate will be deducted from the startdate and this selected duration.';

$string['gotoresource'] = 'Go to the Teams resource';
$string['title_courseurl'] = 'Return to the course';
