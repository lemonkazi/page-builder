<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blocks_fav_model extends CI_Model {

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
     * Insert favourite block
     *
     * @param   int         $id
     * @param  	array 		$frames
     * @return 	integer 	insert_id
     */
    public function insert($id, $frames)
    {
    	$data = [
    		'user_id'		=> $this->session->userdata('user_id'),
    		'blocks_url'	=> 'sites/getframe/' . $id,
    		'blocks_height'	=> $frames['frames_height'],
    		//'created_at'	=> date('Y-m-d H:i:s')
    	];
    	$this->db->insert('blocks_fav', $data);

        return $this->db->insert_id();
    }

    /**
     * To Update the Fields
     *
     * @param 	integer 	$blocks_id
     * @param 	string 		$field
     * @param 	array 		$value
     * @return 	boolean 	TRUE/FALSE
     */
    public function update_field($blocks_id, $field, $value)
    {
    	$data = array(
            $field          => $value,
            //'modified_at'   => date('Y-m-d H:i:s', time())
            );
        $this->db->where('blocks_id', $blocks_id);
        $this->db->update('blocks_fav', $data);

        if ($this->db->affected_rows() >= 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Retrieves a single favourite block
     *
     * @param   integer     $blocks_id
     * @return  array       block
     */
    public function getsingle($blocks_id)
    {

        $this->db->from('blocks_fav');
        $this->db->where('blocks_id', $blocks_id);

        $q = $this->db->get();

        return $q->row_array();

    }

    /**
     * Retrieves a single favourite block
     *
     * @param   integer     $blocks_id
     * @return  void       
     */
    public function delete($blocks_id)
    {

        // retrieve content from blocks_fav first
        $this->db->from('blocks_fav');
        $this->db->where('blocks_id', $blocks_id);

        $q = $this->db->get();

        $block = $q->row_array();

        // retrieve frames id
        $temp = explode("/", $block['blocks_url']);

        $frameID = $temp[2];

        // delete the frames table entry
        $this->db->where('frames_id', $frameID);
        $this->db->delete('frames');

        // delere the blocks_fav entry
        $this->db->where('blocks_id', $blocks_id);
        $this->db->delete('blocks_fav');

    }

}