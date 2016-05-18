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

defined('MOODLE_INTERNAL') || die;

// Secttings header title according to language file.
$settings->add(new admin_setting_heading(
    'configheader',
    get_string('blocksettings', 'block'),
    ''
));

// Should we consider '' (empty string) as NULL (not filled) ?
$settings->add(new admin_setting_configcheckbox(
    'completeyourprofile/Consider_Empty_As_Null',
    get_string('consider_empty_as_null', 'block_completeyourprofile'),
    get_string('consider_empty_as_null_desc', 'block_completeyourprofile'),
    '0'
));

// Should we consider required fields only ?
$settings->add(new admin_setting_configcheckbox(
    'completeyourprofile/Consider_Required_Fields_Only',
    get_string('consider_required_fields_only', 'block_completeyourprofile'),
    get_string('consider_required_fields_only_desc', 'block_completeyourprofile'),
    '0'
));

// Customize block and button texts.
$settings->add(new admin_setting_configtextarea(
    'completeyourprofile/Block_Text',
    get_string('config_block_text', 'block_completeyourprofile'),
    get_string('config_block_text_desc', 'block_completeyourprofile'),
    ''
));

// Customize block and button texts.
$settings->add(new admin_setting_configtext(
    'completeyourprofile/Button_Text',
    get_string('config_button_text', 'block_completeyourprofile'),
    get_string('config_button_text_desc', 'block_completeyourprofile'),
    ''
));
