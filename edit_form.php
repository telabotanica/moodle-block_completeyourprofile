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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class block_completeyourprofile_edit_form extends \block_edit_form {

    protected function specific_definition($mform) {
        global $DB;

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $fieldstofill = $DB->get_records_menu('user_info_field', [], null, 'id,name');

        $mform->addElement('select', 'config_ignorefields', get_string('ignorefields', 'block_completeyourprofile'), $fieldstofill, ['multiple' => true]);

        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_INT);

    }
}