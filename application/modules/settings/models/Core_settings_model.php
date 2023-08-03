<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core_settings_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all core settings values from the DB
     *
     * @return  object   $q->result
     */
    public function get_all()
    {
	    $q = $this->db->get('core_settings');
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
        $q = $this->db->get('core_settings');
        if ($q->num_rows() > 0)
        {
            $data = $q->row_array();
        }

        $q->free_result();
        return $data;
    }

    /**
     * Create new apps settings
     *
     * @param   string   $name
     * @param   string   $description
     * @param   integer  $required
     * @return  integer  insert_id
     */
    public function create($name, $description, $required)
    {
        $data = array(
                'name' => $name,
                'description' => $description,
                'required' => $required,
                'created_at' => date('Y-m-d H:i:s', time())
            );
        $this->db->insert('core_settings', $data);

        return $this->db->insert_id();
    }

    /**
     * updates the apps settings
     *
     * @param   array    $value
     * @return  void
     */
    public function update($value)
    {
	    // auto_update
	    $data = array(
        	'value' => $value['auto_update']
        );

        $this->db->where('name', 'auto_update');
        $this->db->update('core_settings', $data);

        // license_key
        $data = array(
            'value' => trim($value['license_key'])
        );

        $this->db->where('name', 'license_key');
        $this->db->update('core_settings', $data);

        // overwrite blocks
        $data = array(
            'value' => trim($value['overwrite_blocks'])
        );

        $this->db->where('name', 'overwrite_blocks');
        $this->db->update('core_settings', $data);
    }

}