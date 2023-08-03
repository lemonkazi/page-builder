<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Integrations_model extends CI_Model {

    /**
     * Class constructor
     *
     * @return  void
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function set_configs($data = array())
    {
        if(isset($data)){
            $data = array(
                "user_id" => $this->session->userdata('user_id'),
                "name" => $data['name'],
                "value" =>  $data['value'],
                "token" =>  (isset($data['token']) ? $data['token'] : ''),
                "configured" =>  (isset($data['configured']) ? $data['configured'] : 0)
            );
            $this->db->set($data);
            $this->db->insert('integrations');
            return true;
        }

    }

    public function update_configs($data = array())
    {
        if(isset($data)){
            $this->db->set('value', $data['value']);
            $this->db->set('token', (isset($data['token']) ? $data['token'] : ''));
            $this->db->set('configured', $data['configured']);
            $this->db->where('id', $data['integration_id']);
            $this->db->update('integrations');
            return true;
        }

    }

    public function get_configs_by_user_id()
    {
        $result = $this->db->select('*')
            ->where('user_id', $this->session->userdata('user_id'))
            ->get('integrations')
            ->result();
        return $result;
    }

    public function get_configs_by_name($name)
    {
        $result = $this->db->select('*')
            ->where('user_id', $this->session->userdata('user_id'))
            ->where('name', $name)
            ->get('integrations')
            ->row_array();
        return $result;
    }

}