<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all settings values from the DB
     *
     * @return  object   $q->result
     */
    public function get_all()
    {
	    $q = $this->db->get('settings');
        return $q->result();
    }

    /**
     * Get value by name
     *
     * @param   string   $name
     * @return  array    $data
     */
    public function get_by_name($name)
    {
        $data = array();
        $this->db->where('name', $name);
        $this->db->limit(1);
        $q = $this->db->get('settings');

        if ( $q->num_rows() > 0 ) 
        {
            if ($q->num_rows() > 0)
            {
                $data = $q->row_array();
            }

            $q->free_result();
            return $data;
        }
        else
        {
            return false;
        }
    }


    /**
     * Get rest api keys
     *
     * @return  string    $data
     */
    public function get_rest_keys()
    {
        
        $q = $this->db->get('rest_api_keys');
        $temp = array();

        foreach( $q->result() as $row )
        {
            $temp[] = $row->key;
        }

        $data = implode(", ", $temp);

        return $data;

    }


    /**
     * updates the settings
     *
     * @param   array    $settings
     * @return  void
     */
    public function update($settings)
    {
	    
        foreach($settings as $name => $value)
        {

            $this->db->where('name', $name);
            $this->db->delete('settings');

            $data = array(
                'name' => $name,
                'value' => $value
            );

            $this->db->insert('settings', $data);

        }

    }

     /**
     * updates the settings
     *
     * @param   array    $settings
     * @return  void
     */
    public function update_integration($settings)
    {

        $temp = array();

        $temp['rest_on'] = ( isset($settings['rest_on']) && $settings['rest_on'] == '1' )? 1: 0;
        $temp['rest_ip_whitelist_enabled'] = ( isset($settings['rest_ip_whitelist_enabled']) && $settings['rest_ip_whitelist_enabled'] == '1' )? 1: 0;
        $temp['rest_ip_whitelist'] = ( isset($settings['rest_ip_whitelist']) && $settings['rest_ip_whitelist'] != '' )? $settings['rest_ip_whitelist']: '';
        $temp['alogin_on'] = ( isset($settings['alogin_on']) && $settings['alogin_on'] == '1' )? 1: 0;
        $temp['alogin_key'] = ( isset($settings['alogin_key']) && $settings['alogin_key'] != '' )? $settings['alogin_key']: '';

        foreach ( $temp as $name => $value )
        {

            $this->db->where('name', $name);
            $this->db->delete('settings');

            $data = array(
                'name' => $name,
                'value' => $value
            );

            $this->db->insert('settings', $data);
        }


        // Deal with the API rest keys
        $this->db->empty_table('rest_api_keys');
        if ( isset($settings['rest_keys']) && $settings['rest_keys'] != '' )
        {

            $keys = explode(',', $settings['rest_keys']);

            foreach( $keys as $key )
            {
                $data = array(
                    'key' => $key
                );

                $this->db->insert('rest_api_keys', $data);
            }

        }
    }

}