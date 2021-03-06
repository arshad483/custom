<?php
class block_custom_edit_form extends block_edit_form {
    
    protected function specific_definition($mform) {
        global $DB;
        
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        
        
        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_custom'));
        $mform->setType('config_title', PARAM_TEXT); 
       
       $users=get_users();
        foreach ($users as $user) {
           
                $mform->addElement('filemanager', 'config_image_'.$user->id.'_file', get_string('backgroundimage', 'block_custom'), null,array('subdirs' => 0, 'maxbytes' => 5000000, 'maxfiles' => 1,'accepted_types' => array('.png', '.jpg', '.gif', '.jpeg')));
               
           }
        }

    function set_data($defaults)
    {
        
        global $CFG, $USER, $DB;
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
        }
        $draftitemid1 = file_get_submitted_draft_itemid('config_image');
        file_prepare_draft_area($draftitemid1, $this->block->context->id, 'block_custom', 'content', 0,
                    array('subdirs' => true));
        $entry->image = $draftitemid1;
                parent::set_data($defaults);
                if ($data = parent::get_data()) {
                    file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_custom', 'content', 0,
                        array('subdirs' => true));
                }        

        
    }//set_data
}//class

