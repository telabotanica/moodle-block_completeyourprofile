<?php
/**
 * Created by PhpStorm.
 * User: andrewhancox
 * Date: 28/09/2017
 * Time: 21:11
 */

namespace block_completeyourprofile;

require_once($CFG->libdir.'/formslib.php');

class customfieldsform extends \moodleform {
    function definition() {
        global $USER;

        $buttontext = get_config('completeyourprofile', 'Button_Text');
        if (empty($buttontext)) {
            $buttontext = get_string('edit_profile', 'block_completeyourprofile');
        }

        $mform =& $this->_form;
        profile_definition($mform);
        $this->add_action_buttons(false, $buttontext);
        $this->set_data($USER);
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