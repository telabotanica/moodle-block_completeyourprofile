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

$string['completeyourprofile:addinstance'] = 'Add a Complete your profile block';
$string['completeyourprofile:myaddinstance'] = 'Add a Complete your profile block to the My Moodle page';
$string['pluginname'] = 'Complete your profile block';
$string['block_completeyourprofile_title'] = 'Complete your profile';
$string['complete_your_profile'] = "Thank you for taking a little time to complete your profile";
$string['edit_profile'] = "Let's go !";
$string['consider_empty_as_null'] = "Consider the field as not filled in case of an empty value ('')";
$string['consider_required_fields_only'] = "Only consider fields marked as required";
$string['config_block_text'] = "Customize block text (defaults to \"Thank you for taking a little time to complete your profile\")";
$string['config_button_text'] = "Customize the button text (defaults to \"Let's go !\")";