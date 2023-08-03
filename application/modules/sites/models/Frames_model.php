<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frames_model extends CI_Model {

	/**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get frames by ID
     *
     * @param  	integer 	$frames_id
     * @return 	array 		$data
     */
    public function get_by_id($frames_id)
    {
    	$data = [];
    	$this->db->where('frames_id', $frames_id);
    	$q = $this->db->get('frames');
    	if ($q->num_rows() > 0)
    	{
    		$data = $q->row_array();
    	}

    	return $data;
    }

    public function get_all($pages_id = NULL, $sites_id = NULL, $revision = NULL)
    {
        $data = [];
        if ($pages_id)
        {
            $this->db->where('pages_id', $pages_id);
        }
        if ($sites_id)
        {
            $this->db->where('sites_id', $sites_id);
        }
        if ($revision)
        {
            $this->db->where('revision', $revision);
        }
        $this->db->order_by("frames_id", "ASC");
        $q = $this->db->get('frames');
        if ($q->num_rows() > 0)
        {
            foreach ($q->result_array() as $row)
            {
                $data[] = $row;
            }
        }

        $q->free_result();
        return $data;

    }

    /**
     * Insert frame as a favourite block
     *
     * @param  	array 		$frames
     * @return 	integer 	insert_id
     */
    public function insert_frames_as_fav($frames)
    {

        $this->load->helper('base64');
        $this->load->model(['sites/Sites_model' => 'MSites']);

    	$data = [
    		'pages_id'					=> 0,
    		'sites_id'					=> 0,
    		'frames_content' 			=> $this->MSites->processFrameContent(custom_base64_decode($frames['frames_content'])),
    		'frames_height' 			=> $frames['frames_height'],
    		'frames_original_url' 		=> $frames['frames_original_url'],
    		'frames_loaderfunction' 	=> '',
    		'frames_sandbox' 			=> '',
    		'frames_timestamp' 			=> time(),
    		'frames_global'				=> 0,
    		'favourite' 				=> 1,
    		'revision'					=> 0,
    		'created_at' 				=> date('Y-m-d H:i:s')
    	];
    	$this->db->insert('frames', $data);

        return $this->db->insert_id();
    }

    /**
	 * Grabs frame into a single HTML document and return the result
	 *
	 * @param 	integer 	$frame_id
	 * @return 	string 		$str
	 */
    public function load_frame($frame_id)
    {
	    $this->load->library('simple_html_dom');

	    // Grab the frames
	    $this->db->where('frames_id', $frame_id);
	    $this->db->where('revision', 0);
	    $q = $this->db->get('frames');
	    if ($q->num_rows() > 0)
	    {
	    	// Get the skeleton
	    	//$theSkeleton = file_get_html('./elements/skeleton.html');
	    	$theSkeleton = str_get_html(file_get_contents('./elements/skeleton.html'), true, true, DEFAULT_TARGET_CHARSET, false);
		    foreach ($q->result() as $frame)
		    {
			    $html = str_get_html($frame->frames_content, true, true, DEFAULT_TARGET_CHARSET, false);
			    $block = $html->find('div[id=page]', 0)->innertext;
			    $theSkeleton->find('div[id=page]', 0)->innertext .= $block;
		    }
		    $str = $theSkeleton;

		    // Replace both inline css image url and image tag src
			$str = str_replace('../bundles', 'bundles', $str);

			// Google Maps API
			if ($this->config->item('google_api') !== '')
			{
				$str = str_replace('</body>', '<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . $this->config->item('google_api') . '&callback=initMap"></script></body>', $str);
			}

		    return $str;
	    }
    }

    /**
     * deletes a single frame
     *
     * @param   integer     $frame_id
     * @return  void
     */
    public function delete_frame($frame_id)
    {

        $this->db->where('frames_id', $frame_id);
        $this->db->delete('frames');

    }

    public function delete_limited_frames()
    {
        $number_of_days = $this->config->item('max_number_of_days');
        $this->db->where('revision', 1);
        $this->db->where('frames_timestamp < UNIX_TIMESTAMP(DATE_SUB(now(), INTERVAL ' . $number_of_days . ' DAY))');
        $query =  $this->db->delete('frames');

        return $query;
    }


}