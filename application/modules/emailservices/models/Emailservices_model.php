<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emailservices_model extends CI_Model {

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
     * @return mixed
     */
    public function get_configs_by_user_id()
    {
        $result = $this->db->select('*')
            ->where('user_id', $this->session->userdata('user_id'))
            ->get('integrations')
            ->result();
        return $result;
    }
}