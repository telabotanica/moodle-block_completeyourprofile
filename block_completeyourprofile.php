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
        $this->content  =  new stdClass;
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

        $profileIsComplete = true;
        $str = "";

        if ($USER->id == 1) { // guest user
            // No content will make the block disappear
            return '';
        }

        // should we consider '' (empty string) as NULL (not filled) ?
        $emptyFieldClause = "data IS NOT NULL";
        if (! empty($this->config->emptyasnull) &&  ($this->config->emptyasnull == 1)){
            $emptyFieldClause .= " AND data != ''";
        }

        // should we consider required fields only ?
        $where1 = "visible > 0";
        if (! empty($this->config->requiredonly) &&  ($this->config->requiredonly == 1)){
            $where1 .= " AND required = 1";
        }

        // which fields are supposed to be filled ?
        $fieldsToFill = $DB->get_records_select('user_info_field', $where1, null, '', 'id');
        //var_dump($fieldsToFill);
        if (count($fieldsToFill) > 0) {
            // get desired fields IDs
            $ftfIds = array();
            foreach ($fieldsToFill as $ftf) {
                $ftfIds[] = $ftf->id;
            }
            // check if those fields are filled in the current user's profile
            $where2 = "userid = " . $USER->id . " AND $emptyFieldClause AND fieldid IN(" . implode(',', $ftfIds) . ")";
            $nbFilledFields = $DB->count_records_select('user_info_data', $where2, null, 'COUNT(*)');
            // compare results
            if ($nbFilledFields < count($ftfIds)) {
                $profileIsComplete = false;
            }
        }

        // so what now ?
        if (! $profileIsComplete) {
            $editProfileUrl = new moodle_url('/user/edit.php', array('id' => $USER->id));
            $str .= "<p>";
            if (! empty($this->config->block_text)){
                $str .= $this->config->block_text;
            } else {
                $str .= get_string('complete_your_profile', 'block_completeyourprofile');
            }
            $str .= "</p>";
            $str .= "<br/>";
            $str .= '<a class="submit" href="' . $editProfileUrl->out() . '">';
            if (! empty($this->config->button_text)){
                $str .= $this->config->button_text;
            } else {
                $str .= get_string('edit_profile', 'block_completeyourprofile');
            }
            $str .= "</a>";
        } // else nothing, everything is OK

        return $str;
    }
}
?>