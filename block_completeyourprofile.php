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
defined('MOODLE_INTERNAL') || die();

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
    }

    /**
     * Returns the block content
     *
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

    public function get_title() {
        return get_string('pluginname', 'block_completeyourprofile');
    }

    public function applicable_formats() {
        return array('site' => true, 'course' => true, 'my' => true);
    }

    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Generates the block content
     *
     * @global type $USER
     * @global type $DB
     * @return string
     */
    protected function generate_content() {
        global $USER;
        global $DB;
        $profileiscomplete = true;
        $str = "";
        $displayform = get_config('completeyourprofile', 'Display_form');
        $hideifcomplete = get_config('completeyourprofile', 'Hide_if_complete');
        $confirmationtext = get_config('completeyourprofile', 'Confirmation_Text');
        $newinfosubmitted = false;

        if (!isloggedin() or isguestuser()) { // Guest user
            // No content will make the block disappear.
            return '';
        }

        if ($displayform) {
            $form = new \block_completeyourprofile\customfieldsform(null, ['config' => $this->config, 'courseid' => $this->page->course->id]);
            if ($usernew = $form->get_data()) {
                $usernew->id = $USER->id;
                profile_save_data($usernew);
                $newinfosubmitted = true;
            }
        }

        $params = [];
        // Should we consider '' (empty string) as NULL (not filled) ?
        $consideremptyasnull = get_config('completeyourprofile', 'Consider_Empty_As_Null');
        $emptyfieldclause = "data IS NOT NULL";
        if (!empty($consideremptyasnull) && ($consideremptyasnull == 1)) {
            $emptyfieldclause .= " AND data != ''";
        }

        // Should we consider required fields only ?
        $considerrequiredfieldsonly = get_config('completeyourprofile', 'Consider_Required_Fields_Only');
        $where1 = "visible > 0";
        if (!empty($considerrequiredfieldsonly) && ($considerrequiredfieldsonly == 1)) {
            $where1 .= " AND required = 1";
        }
        if (!empty($this->config->ignorefields)) {
            list($insql, $inparams) = $DB->get_in_or_equal(array_values($this->config->ignorefields), SQL_PARAMS_NAMED, 'param', false);
            $inparams;
        } else {
            $insql = '';
            $inparams = [];
        }

        // Which fields are supposed to be filled ?
        $where1 .= " AND id $insql ";
        $params += $inparams;
        $fieldstofill = $DB->get_records_select('user_info_field', $where1, $params, '', 'id');

        // Should we check for user picture / interests ?
        $checkforuserpicture = get_config('completeyourprofile', 'check_for_user_picture');
        $checkforinterests = get_config('completeyourprofile', 'check_for_interests');

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

        if ($checkforuserpicture) {
            // Check for picture.
            $haspicture = $DB->get_field('user', 'picture', array('id' => $USER->id) );
            if ($haspicture < 1) {
                $profileiscomplete = false;
            }
        }

        if ($checkforinterests) {
            // Check for tags.
            $hastags = $DB->count_records('tag_instance', array('itemtype' => 'user', 'itemid' => $USER->id) );
            if ($hastags < 1) {
                $profileiscomplete = false;
            }
        }

        // So what now ?
        if (!$profileiscomplete && !$displayform) {
            $editprofileurl = new moodle_url('/user/edit.php', array('id' => $USER->id));
            $str .= "<p>";
            $blocktext = get_config('completeyourprofile', 'Block_Text');
            if (!empty($blocktext)) {
                $str .= $blocktext;
            } else {
                $str .= get_string('complete_your_profile', 'block_completeyourprofile');
            }
            $str .= "</p>";
            $str .= "<br/>";
            $str .= '<a class="submit" href="' . $editprofileurl->out() . '">';
            $buttontext = get_config('completeyourprofile', 'Button_Text');
            if (!empty($buttontext)) {
                $str .= $buttontext;
            } else {
                $str .= get_string('edit_profile', 'block_completeyourprofile');
            }
            $str .= "</a>";
        } else if (!$profileiscomplete && $displayform) {
            $blocktext = get_config('completeyourprofile', 'Block_Text');
            if (empty($blocktext)) {
                $blocktext = get_string('complete_your_profile', 'block_completeyourprofile');
            }
            $str .= html_writer::div($blocktext, "completeprofileformintro");
            $str .= $form->render();
        } else if ($newinfosubmitted && !empty($confirmationtext)) {
            $this->title = get_string('block_completeyourprofile_title', 'block_completeyourprofile');
            $str .= html_writer::div($confirmationtext);
        } else if (!$hideifcomplete) { // Else nothing, everything is OK.
            $this->title = get_string('block_completeyourprofile_title', 'block_completeyourprofile');
            $str .= html_writer::div(get_string('complete_your_profile', 'block_completeyourprofile'));
        }

        return $str;
    }

    public function has_config() {
        return true;
    }
}
