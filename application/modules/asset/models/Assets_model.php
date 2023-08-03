<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assets_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns all images belonging to user
     * @param  integer  $userID
     * @return mixed    $userImages/FALSE
     */
    public function get_images($userID)
    {
        if (is_dir($this->config->item('images_uploadDir') . "/" . $userID))
        {
            //$folderContent = directory_map($this->config->item('images_uploadDir')."/".$userID, 2);
            
            if ($handle = opendir($this->config->item('images_uploadDir') . "/" . $userID)) {

                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && $file != '.DS_Store') {
                        $files[filemtime($this->config->item('images_uploadDir') . "/" . $userID . '/' .$file)] = $file;
                    }
                }
                
                closedir($handle);

            }

            if (isset($files))
            {
                // sort
                krsort($files);

                $userImages = array();
                foreach ($files as $key => $item)
                {
                    if ( ! is_array($item))
                    {
                        // Check the file extension
                        $ext = pathinfo($item, PATHINFO_EXTENSION);
                        // Prep allowed extensions array
                        $temp = explode("|", $this->config->item('images_allowedExtensions'));
                        if (in_array($ext, $temp))
                        {
                            array_push($userImages, $item);
                        }
                    }
                }
                return $userImages;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Returns all admin images
     * @return mixed    $adminImages/FALSE
     */
    public function admin_images()
    {
        $folderContent = directory_map($this->config->item('images_dir'), 2);
        if ($folderContent)
        {
            $adminImages = array();
            foreach ($folderContent as $key => $item)
            {
                if ( ! is_array($item))
                {
                    // Check the file extension
                    $ext = pathinfo($item, PATHINFO_EXTENSION);
                    // Prep allowed extensions array
                    $temp = explode("|", $this->config->item('images_allowedExtensions'));
                    if (in_array($ext, $temp))
                    {
                        array_push($adminImages, $item);
                    }
                }
            }
            return $adminImages;
        }
        else
        {
            return FALSE;
        }

    }

    /**
     * Retrieves all configuration values from the DB
     * @return object   $q->result()
     */
    public function getConfig()
    {
	    $q = $this->db->get('configuration');
	    return $q->result();
    }

    /**
     * Updates the configuration settings
     * @param  array    $configData
     */
    public function updateConfig($configData)
    {
	    // elements_dir
	    $data = array(
        	'config_value' => $configData['elements_dir']
        );
        $this->db->where('config_id', 1);
        $this->db->update('configuration', $data);

        // images_dir
	    $data = array(
        	'config_value' => $configData['images_dir']
        );
        $this->db->where('config_id', 2);
        $this->db->update('configuration', $data);

        // images_uploadDir
	    $data = array(
        	'config_value' => $configData['images_uploadDir']
        );
        $this->db->where('config_id', 3);
        $this->db->update('configuration', $data);

        // upload_max_size
	    $data = array(
        	'config_value' => $configData['upload_max_size']
        );
        $this->db->where('config_id', 5);
        $this->db->update('configuration', $data);

        // upload_max_width
	    $data = array(
        	'config_value' => $configData['upload_max_width']
        );
        $this->db->where('config_id', 6);
        $this->db->update('configuration', $data);

        // upload_max_height
	    $data = array(
        	'config_value' => $configData['upload_max_height']
        );
        $this->db->where('config_id', 7);
        $this->db->update('configuration', $data);

        // export_pathToAssets
	    $data = array(
        	'config_value' => $configData['export_pathToAssets']
        );
        $this->db->where('config_id', 9);
        $this->db->update('configuration', $data);

        // export_fileName
	    $data = array(
        	'config_value' => $configData['export_fileName']
        );
        $this->db->where('config_id', 10);
        $this->db->update('configuration', $data);

        // index_page
	    $data = array(
        	'config_value' => $configData['index_page']
        );
        $this->db->where('config_id', 12);
        $this->db->update('configuration', $data);

        // language
	    $data = array(
        	'config_value' => $configData['language']
        );
        $this->db->where('config_id', 13);
        $this->db->update('configuration', $data);
    }

}