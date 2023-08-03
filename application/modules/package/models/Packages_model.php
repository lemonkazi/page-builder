<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages_model extends CI_Model {

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
     * Get single package by id
     *
     * @param   integer     $id
     * @return  array       $data
     */
    public function get_by_id($package_id)
    {
        $data = array();
        $this->db->where('id', $package_id);
        $this->db->limit(1);
        $q = $this->db->get('packages');
        if ($q->num_rows() > 0)
        {
            foreach ($q->result_array() as $row)
            {
                $data = $row;
            }
        }

        $q->free_result();
        return $data;
    }

    /**
     * Get all packages
     *
     * @param   string      $gateway
     * @return  array       $data
     */
    public function get_all($gateway = NULL, $onlyActive = FALSE)
    {
        $data = array();
        if ($gateway)
        {
            $this->db->where('gateway', $gateway);
        }
        if ($onlyActive)
        {
            $this->db->where('status', 'Active');
        }
        $this->db->order_by('price', 'ASC');
        $q = $this->db->get('packages');
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
     * Create new package
     *
     * @param   string      $string
     * @param   string      $stripe_id
     * @return  integer     insert_id
     */
    public function create($gateway, $stripe_id = '')
    {
        // Prep the blocks data
        if ( ! isset($_POST['limit_blocks']))
        {
            $blocks = NULL;
        }
        else
        {
            if ( ! isset($_POST['blockcats']))
            {
                $blocks = '[]';
            }
            else
            {
                $blocks = json_encode($this->input->post('blockcats'));
            }
        }

        $data = array(
            'gateway'           => $gateway,
            'stripe_id'         => $stripe_id,
            'name'              => trim($this->input->post('name')),
            'sites_number'      => $this->input->post('sites_number'),
			'pages_number'      => $this->input->post('pages_number'),
            'hosting_option'    => json_encode($this->input->post('hosting_option')),
            'export_site'       => $this->input->post('export_site'),
            'ftp_publish'       => $this->input->post('ftp_publish'),
			'edit_sourcecode'   => $this->input->post('edit_sourcecode'),
            'disk_space'        => $this->input->post('disk_space'),
            'templates'         => json_encode($this->input->post('templates')),
            'blocks'            => $blocks,
            'price'             => $this->input->post('price'),
            'currency'          => $this->input->post('currency'),
            'subscription'      => $this->input->post('subscription'),
            'status'            => $this->input->post('status'),
            'created_at'        => date('Y-m-d H:i:s', time())
        );
        $this->db->insert('packages', $data);

        return $this->db->insert_id();
    }

    /**
     * Update existing package
     *
     * @return  boolean
     */
    public function update()
    {
        // Prep the blocks data
        if ( !isset($_POST['limit_blocks']) )
        {
            $blocks = NULL;
        }
        else
        {
            if ( !isset($_POST['blockcats']) )
            {
                $blocks = '[]';
            }
            else
            {
                $blocks = json_encode($this->input->post('blockcats'));
            }
        }

        $data = array(
            'name'              => $this->input->post('name'),
            'sites_number'      => $this->input->post('sites_number'),
			'pages_number'      => $this->input->post('pages_number'),
            'hosting_option'    => json_encode($this->input->post('hosting_option')),
            'export_site'       => $this->input->post('export_site'),
            'ftp_publish'       => $this->input->post('ftp_publish'),
			'edit_sourcecode'   => $this->input->post('edit_sourcecode'),
            'disk_space'        => $this->input->post('disk_space'),
            'templates'         => json_encode($this->input->post('templates')),
            'blocks'            => $blocks,
            'status'            => $this->input->post('status'),
            'modified_at'       => date('Y-m-d H:i:s', time())
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('packages', $data);

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
     * Update a specific field value
     *
     * @param   integer     $package_id
     * @param   string      $name
     * @param   mixed       $value
     * @return  boolean
     */
    public function update_field($package_id, $name, $value)
    {
        $data = array(
            $name => $value,
            'modified_at' => date('Y-m-d H:i:s', time())
        );

        $this->db->where('id', $package_id);
        $this->db->update('packages', $data);

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
     * Update status of package
     *
     * @deprecated Toggle package status option is not using any more
     * @param   string      $status
     * @param   integer     $package_id
     * @return  boolean
     */
    public function update_status($status, $package_id)
    {
        $data = array(
            'status'        => $status,
            'modified_at'   => date('Y-m-d H:i:s', time())
        );

        $this->db->where('id', $package_id);
        $this->db->update('packages', $data);

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
     * Delete package by id
     *
     * @param   integer     $package_id
     * @return  boolean
     */
    public function delete($package_id)
    {
        $this->db->where('id', $package_id);
        $this->db->delete('packages');

        if ($this->db->affected_rows() >= 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}