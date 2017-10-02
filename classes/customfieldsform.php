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
 * A simple block that encourages users to complete their profile
 *
 * Checks if all required "profile fields" (admin > users > accouts > profile fields)
 * are filled for the current user; if not, suggests him/her to take a few minutes
 * to complete his/her profile
 *
 * English and french versions included / versions anglaise et française incluses.
 *
 * @package    block_completeyourprofile
 * @category   blocks
 * @copyright  2017 Andrew Hancox <andrewdchancox@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_completeyourprofile;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class customfieldsform extends \moodleform {
    public function definition() {
        global $USER, $DB;
        $config = $this->_customdata['config'];
        $courseid = $this->_customdata['courseid'];

        $buttontext = get_config('completeyourprofile', 'Button_Text');
        if (empty($buttontext)) {
            $buttontext = get_string('edit_profile', 'block_completeyourprofile');
        }

        $mform =& $this->_form;
        profile_definition($mform);

        $mform->addElement('hidden', 'id', $courseid);
        $mform->setType('id', PARAM_INT);

        if (!empty($config->ignorefields)) {
            $fields = $DB->get_records_list('user_info_field', 'id', $config->ignorefields);
            foreach ($fields as $field) {
                $inputname = 'profile_field_' . $field->shortname;

                if (!isset($mform->_elementIndex[$inputname])) {
                    continue;
                }
                $elemindex = $mform->_elementIndex[$inputname];
                $elem = $mform->_elements[$elemindex];
                $mform->removeElement($elem->getName());
            }
        }

        $this->add_action_buttons(false, $buttontext);

        $defaults = (array)$USER;
        $defaults['id'] = $courseid;

        $this->set_data($defaults);
    }

    public function definition_after_data() {
        global $USER;
        $mform = $this->_form;

        profile_definition_after_data($mform, $USER->id);
    }

    public function validation($usernew, $files) {
        global $USER;

        $errors = parent::validation($USER, $files);
        $errors += profile_validation($USER, $files);
        return $errors;
    }
}