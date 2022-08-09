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
 * Displays information about all the teams meeting modules in the requested course
 *
 * @package   mod_teamsmeeting
 * @copyright 2022 Anthony Durif, Université Clermont Auvergne
 */

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/teamsmeeting/lib.php');
// For this type of page this is the course id.
$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
require_login($course);
$PAGE->set_url('/mod/teamsmeeting/index.php', array('id' => $id));
$PAGE->set_pagelayout('incourse');

$params = array(
    'context' => context_course::instance($course->id)
);

// Print the header.
$strplural = get_string("modulenameplural", "teamsmeeting");
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($strplural));
require_capability('mod/teamsmeeting:view', $params['context']);

$meetings = get_all_instances_in_course('teamsmeeting', $course);
if (!$meetings) {
    notice('There are no instances of teams resources', "../../course/view.php?id=$course->id");
    die;
}

// Print the table.
$table = new html_table();
$table->head = array(get_string('sectionname', 'format_'.$course->format), get_string('name'));
$table->align = array('left', 'left', 'center');

foreach ($meetings as $meeting) {
    if (has_capability('mod/teamsmeeting:view', context_module::instance($meeting->coursemodule))) {
        if (!$meeting->visible) {
            // Show dimmed if the mod is hidden.
            $link = '<a class="dimmed" href="view.php?id=' . $meeting->coursemodule . '">' . format_string($meeting->name) . '</a>';
        } else {
            // Show normal if the mod is visible.
            $link = '<a href="view.php?id=' . $meeting->coursemodule . '">' . format_string($meeting->name) . '</a>';
        }
        $table->data[] = array(get_section_name($course, $meeting->section), $link);
    }
}

echo html_writer::table($table);
echo $OUTPUT->footer();