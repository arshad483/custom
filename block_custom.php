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
        $this->content->text.= '<p>'.$this->config->title.'</p>';
       
         $this->content->text.='</div>';
            
         $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_custom', 'content');
      /* var_dump($fs);
        var_dump($this->draftitemid);
        var_dump($files);         
         die();*/
         return $this->content;
    }

    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
        global $DB;
        //var_dump($data->image_53);
         
        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match
        $users=get_users();
        foreach ($users as $user) {            
            $image='image_'.$user->id;
            $config->text = file_save_draft_area_files($data->$image, $this->context->id, 'block_custom', 'content', 0, array('subdirs'=>true), $data->$image);
            $fs = get_file_storage();
            $files = $fs->get_area_files($this->context->id, 'block_custom', 'content');
            $table='block_custom';
            foreach ($files as $file) {
                $filename = 'user_'.$user->id; 
                $destdir=$CFG->dirroot.'/blocks/custom/images/';
                $file->copy_content_to($destdir);
                $only_url=$CFG->wwwroot.'/blocks/custom/images/'.$filename;

                if ($filename !== '.') {
                    $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                    $this->content->text .= '<img src="' . $url . '" alt="' . $filename . '" />';
                
                
                $attachments=$data->$image;
                $conditions=array('image_id'=>$attachments);
                $dataDB=$DB->record_exists($table,$conditions); 
                $updateId=$DB->get_record($table,$conditions);
                
                if($dataDB){                   
                    $dataobject=new stdClass();
                    $dataobject->id=$updateId->id;
                    $dataobject->image_path=$only_url;
                    $DB->update_record($table, $dataobject);
                }else{
                    $dataobject=new stdClass();
                    $dataobject->user_id=$user->id
                    $dataobject->image_id=$this->config->attachments;
                    $dataobject->image_path=$only_url;
                    $DB->insert_record($table, $dataobject);         
                }//else
            }           
        }
         if($this->config->link){
           
            $stored_url=$DB->get_record($table, ['image_id' => $this->config->attachments]);
            
                if(!$stored_url)
                {
                    throw new Exception("No Images found");
                    
                }
                $this->content->text.=  '<div class="container" style="background-image:url(' . $stored_url->image_path . ')">';           
                $this->content->text.='</div>'; 
        }
        }      

        }


}