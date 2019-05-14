<?php

class block_custom extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_custom');
    }

    function get_content () {
        global $CFG, $USER, $DB, $OUTPUT;
        require_once($CFG->libdir . '/filelib.php');
        $tagname = optional_param('tag', '', PARAM_TAG); // tag
        

         $this->content = new stdClass;
        
        if (!empty($this->config->text)) {
            $this->content->text = '';
        } else {
            $this->content->text = '';
        } 
        
        $this->content->footer = '';
        
        $this->content->text.='<div>';
        $this->content->text.= '<p>'.$this->config->image.'</p>';
       
         $this->content->text.='</div>';

         $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_custom', 'content');
        var_dump($files);         
         die();
         return $this->content;
    }

}