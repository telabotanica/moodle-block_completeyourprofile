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
class block_completeyourprofile extends block_base {

    /**
     * Initiates the block title
     */
    public function init() {
        $this->title = get_string('block_completeyourprofile_title', 'block_completeyourprofile');
    }

    /**
     * Returns the block content
     * @return string
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->text = $this->generate_content();

        return $this->content;
    }

    /**
     * Generates the block content
     * @global type $USER
     * @global type $DB
     * @return string
     */
    protected function generate_content() {
        global $USER;
        global $DB;

        $profileiscomplete = true;
        $str = "";

        if (!isloggedin() or isguestuser()) { // Guest user
            // No content will make the block disappear.
            return '';
        }

        // Should we consider '' (empty string) as NULL (not filled) ?
        $consideremptyasnull = get_config('completeyourprofile', 'Consider_Empty_As_Null');
        $emptyfieldclause = "data IS NOT NULL";
        if (! empty($consideremptyasnull) &&  ($consideremptyasnull == 1)) {
            $emptyfieldclause .= " AND data != ''";
        }

        // Should we consider required fields only ?
        $considerrequiredfieldsonly = get_config('completeyourprofile', 'Consider_Required_Fields_Only');
        $where1 = "visible > 0";
        if (! empty($considerrequiredfieldsonly) &&  ($considerrequiredfieldsonly == 1)) {
            $where1 .= " AND required = 1";
        }

        // Which fields are supposed to be filled ?
        $fieldstofill = $DB->get_records_select('user_info_field', $where1, null, '', 'id');

        if (count($fieldstofill) > 0) {
            // Get desired fields IDs.
            $ftfids = array();
            foreach ($fieldstofill as $ftf) {
                $ftfids[] = $ftf->id;
            }
            // Check if those fields are filled in the current user's profile.
            $where2 = "userid = " . $USER->id . " AND $emptyfieldclause AND fieldid IN(" . implode(',', $ftfids) . ")";
            $nbfilledfields = $DB->count_records_select('user_info_data', $where2, null, 'COUNT(*)');
            // Compare results
            if ($nbfilledfields < count($ftfids)) {
                $profileiscomplete = false;
            }
        }

        // So what now ?
        if (! $profileiscomplete) {
            $editprofileurl = new moodle_url('/user/edit.php', array('id' => $USER->id));
            $str .= "<p>";
            $blocktext = get_config('completeyourprofile', 'Block_Text');
            if (! empty($blocktext)) {
                $str .= $blocktext;
            } else {
                $str .= get_string('complete_your_profile', 'block_completeyourprofile');
            }
            $str .= "</p>";
            $str .= "<br/>";
            $str .= '<a class="submit" href="' . $editprofileurl->out() . '">';
            $buttontext = get_config('completeyourprofile', 'Button_Text');
            if (! empty($buttontext)) {
                $str .= $buttontext;
            } else {
                $str .= get_string('edit_profile', 'block_completeyourprofile');
            }
            $str .= "</a>";
        } // else nothing, everything is OK

        return $str;
    }

    public function has_config() {
        return true;
    }
}
