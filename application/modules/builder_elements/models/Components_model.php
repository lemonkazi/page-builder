<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Components_model extends CI_Model {

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
     * Returns all blocks
     *
     * @return  array       $final
     */
    public function all()
    {
        /** Grab categpries */
        $this->db->from('components_categories');
        $query = $this->db->get();
        $categories = $query->result_array();

        /** Grab blocks */
        $this->db->from('components');
        $query = $this->db->get();
        $components = $query->result_array();

        /** Build array */
        $final = [];

        foreach ($categories as $category)
        {
            $tempComponents = [];

            foreach ($components as $component)
            {
                if ($component['components_category'] == $category['components_categories_id'])
                {
                    $tempComponents[] = $component;
                }
            }

            $final[$category['category_name']] = $tempComponents;
        }

        return $final;
    }

    /**
     * Returns all components
     *
     * @return  array       $query
     */
    public function allBasic()
    {
        $this->db->from('components');
        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * Returns all component categories
     *
     * @return  array       $query
     */
    public function allComponentCategories()
    {
        $this->db->from('components_categories');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * Check if component category name unique
     *
     * @param   string      $catName
     * @return  boolean     TRUE/FALSE
     */
    public function componentCatNameIsUnique($catName)
    {
        $this->db->from('components_categories');
        $this->db->where('category_name', $catName);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Add category
     *
     * @param   string      $catname
     * @return  void
     */
    public function addCategory($catname)
    {
        $data = array(
            'category_name' => $catname
        );

        $this->db->insert('components_categories', $data);
    }

    /**
     * Update category
     *
     * @param   string      $catname
     * @param   integer     $catid
     * @return  void
     */
    public function updateCategory($catname, $catid)
    {
        $data = array(
            'category_name' => $catname,
        );

        $this->db->where('components_categories_id', $catid);
        $this->db->update('components_categories', $data);
    }

    /**
     * Remove category
     *
     * @param   integer     $catID
     * @param   string      $replaceWith
     * @return  void
     */
    public function removeCategory ($catID, $replaceWith)
    {
        /** Delete the block category */
        $this->db->where('components_categories_id', $catID);
        $this->db->delete('components_categories');

        /** Replace with */
        $data = array(
            'components_category' => $replaceWith
        );

        $this->db->where('components_category', $catID);
        $this->db->update('components', $data);
    }

    /**
     * Load component
     *
     * @param   integer     $componentID
     * @return  mixed       $query/FALSE
     */
    public function loadComponent($componentID)
    {
        $this->db->from('components');
        $this->db->where('components_id', $componentID);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Update component
     *
     * @param   integer     $componentID
     * @param   array       $data
     * @return  void
     */
    public function updateComponent($componentID, $data)
    {
        $this->db->where('components_id', $componentID);
        $this->db->update('components', $data);
    }

    /**
     * Delete component
     *
     * @param   integer     $componentID
     * @return  void
     */
    public function deleteComponent($componentID)
    {
        /** Retrieve the thumbnail URL and template file URL for deletion */
        $this->db->from('components');
        $this->db->where('components_id', $componentID);
        $query = $this->db->get();

        $component = $query->row();

        /** delete the thumbnail */
        unlink("./" . $component->components_thumb);

        /** delete the data itself */
        $this->db->where('components_id', $componentID);
        $this->db->delete('components');
    }

    /**
     * Add component
     *
     * @param   array       $data
     * @return  integer     insert_id
     */
    public function addComponent($data)
    {
        $this->db->insert('components', $data);

        return $this->db->insert_id();
    }

}