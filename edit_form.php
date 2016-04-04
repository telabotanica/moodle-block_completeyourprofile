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
 * @copyright  2016 Mathias Chouet, Tela Botanica
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Block definition
 *
 * @package    block_completeyourprofile
 * @copyright  2016 Mathias Chouet, Tela Botanica
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_completeyourprofile_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // should we consider '' (empty string) as NULL (not filled) ?
        $mform->addElement('advcheckbox', 'config_emptyasnull', get_string('consider_empty_as_null', 'block_completeyourprofile'), '', null, array(0, 1));
        $mform->setDefault('config_emptyasnull', 0);
        $mform->setType('config_emptyasnull', PARAM_RAW);

        // should we consider required fields only ?
        $mform->addElement('advcheckbox', 'config_requiredonly', get_string('consider_required_fields_only', 'block_completeyourprofile'), '', null, array(0, 1));
        $mform->setDefault('config_requiredonly', 0);
        $mform->setType('config_requiredonly', PARAM_RAW);

        // customize block and button texts
        $mform->addElement('text', 'config_block_text', get_string('config_block_text', 'block_completeyourprofile'));
        $mform->setDefault('config_block_text', '');
        $mform->setType('config_block_text', PARAM_RAW);

        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'block_completeyourprofile'));
        $mform->setDefault('config_button_text', '');
        $mform->setType('config_button_text', PARAM_RAW);
    }
}
