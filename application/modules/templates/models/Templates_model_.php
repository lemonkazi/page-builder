<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates_model extends CI_Model {

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
     * Returns all available templates
     *
     * @param   integer     $catID
     * @param   integer     $pages_id
     * @return  array       $res
     */
    public function all($catID = false, $pages_id = NULL)
    {

        $this->db->select('pages.pages_id, pages.sites_id, pages.pages_name, pages.pages_timestamp, pages.pages_title, pages.pages_meta_keywords, pages.pages_meta_description, pages.pages_header_includes, pages.pages_preview, pages.pages_template, pages.pages_css, pages.created_at, pages.modified_at, pages.pagethumb, pages.google_fonts, category_id, templates_categories_id, category_name');
        $this->db->from('pages');
        if ( $pages_id ) $this->db->where_in('pages.pages_id', $pages_id);
        if ( $catID ) $this->db->where('category_id', $catID);
        $this->db->where('pages_template !=', 0);
        $this->db->join('template_to_category', 'pages.pages_id = template_to_category.pages_id', 'left');
        $this->db->join('template_categories', 'template_to_category.category_id = template_categories.templates_categories_id', 'left');
        $query = $this->db->get();
        $res = $query->result();

        return $res;

    }

    /**
     * Returns relevant data for the requested template
     *
     * @param   integer     $templateID
     * @return  array       $res
     */
    public function single($templateID)
    {
        // make sure this templateID is indeed a template ID and does not belong to a regular page
		$this->db->select('pages_title, pages_meta_keywords, pages_meta_description, pages_header_includes, pages_css, google_fonts');
        $this->db->from('pages');
        $this->db->where('pages_id', $templateID);
        $this->db->where('pages_template', 1);

        $query = $this->db->get();
		$pages_data = $query->result_array();
        if ( $query->num_rows() == 0 ) return false;
		//print_r($pages_data);
        $this->db->select('frames_id, frames_height, frames_original_url');
        $this->db->from('frames');
        $this->db->where('pages_id', $templateID);
        $this->db->where('frames_popup', '');

        $query = $this->db->get();

        if ( $query->num_rows() == 0 ) return false;
        else
		{
			$frames_data = $query->result_array();
			$pages_data = array_merge($pages_data, $frames_data);
			print_r($pages_data);
			return $returnData;

		}

    }


    /**
     * Returns all available templates
     *
     * @param   integer     $pages_id
     * @return  array       $res
     */
    public function allWithNoCategory($pages_id = NULL)
    {

        $this->db->select('pages.pages_id, pages.sites_id, pages.pages_name, pages.pages_timestamp, pages.pages_title, pages.pages_meta_keywords, pages.pages_meta_description, pages.pages_header_includes, pages.pages_preview, pages.pages_template, pages.pages_css, pages.created_at, pages.modified_at, pages.pagethumb, pages.google_fonts, category_id, templates_categories_id, category_name');
        $this->db->from('pages');
        if ( $pages_id ) $this->db->where_in('pages.pages_id', $pages_id);
        $this->db->where('category_id', NULL);
        $this->db->where('pages_template !=', 0);
        $this->db->join('template_to_category', 'pages.pages_id = template_to_category.pages_id', 'left');
        $this->db->join('template_categories', 'template_to_category.category_id = template_categories.templates_categories_id', 'left');
        $query = $this->db->get();
        $res = $query->result();
        
        return $res;

    }

    /**
     * Grabs all templates for given category
     *
     * @param   integer     $catID
     * @param   integer     $pages_id
     * @return  array       $templates
     */
    public function getForCategory($catID, $pages_id = NULL)
    {

        $templates = array();

        if ($pages_id)
        {
            $this->db->where_in('pages.pages_id', $pages_id);
        }

        $this->db->from('pages');
        $this->db->select('pages.pages_id, pages.sites_id, pages.pages_name, pages.pages_timestamp, pages.pages_title, pages.pages_meta_keywords, pages.pages_meta_description, pages.pages_header_includes, pages.pages_preview, pages.pages_template, pages.pages_css, pages.created_at, pages.modified_at, pages.pagethumb, pages.google_fonts, category_id, templates_categories_id, category_name');
        $this->db->join('template_to_category', 'pages.pages_id = template_to_category.pages_id', 'left');
        $this->db->join('template_categories', 'template_to_category.category_id = template_categories.templates_categories_id', 'left');
        if ( $catID !== 0 ) $this->db->where('templates_categories_id', $catID);
        $this->db->where('pages_template', '1');

        $q = $this->db->get();

        if ($q->num_rows() > 0)
        {
            $pages = $q->result_array();

            return $pages;
        }
        else
        {
            return FALSE;
        }

    }


    /**
     * Creates a new page template
     *
     * @return  array       $page_id
     */
    public function createNew()
    {

        /** Create empty index page */
        $data = array(
            'sites_id'          => 0,
            'pages_name'        => 'index',
            'pages_timestamp'   => time(),
            'pages_template'    => 1,
            'created_at'        => date("Y-m-d H:i:s")
        );
        $this->db->insert('pages', $data);

        $page_id = $this->db->insert_id();

        return $page_id;

    }


    /**
     * Retrieves the template categories from the database
     *
     * @return  array       $cats
     */
    public function getCategories()
    {

        $q = $this->db->order_by('category_name')->get('template_categories');

        $cats = $q->result_array();

        return $cats;

    }


    /**
     * Retrieves the template category for given template
     *
     * @param   integer     $templateID
     * @return  integer       $categoryID
     */
    public function getTemplateCategory($templateID)
    {

        $this->db->from('template_to_category');
        $this->db->where('pages_id', $templateID);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) 
        {
            $row = $query->row();

            return $row->category_id;

        }
        else 
        {
            return false;
        }
    }

    /**
     * Check if Category name is unique
     *
     * @param   string      $catName
     * @return  boolen      TRUE/FALSE
     */
    public function catNameIsUnique($catName)
    {

        $this->db->from('template_categories');
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
     * Returns all block categories
     *
     * @param   string      $catname
     * @return  void
     */
    public function addCategory($catname)
    {
        $data = array(
            'category_name' => $catname
        );

        $this->db->insert('template_categories', $data);
    }


    /**
     * Returns all template categories
     *
     * @return  array       $query
     */
    public function allCategories()
    {
        $this->db->from('template_categories');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }


    /**
     * Remove category
     *
     * @param   integer     $catID
     * @param   string      $replaceWith
     * @return  void
     */
    public function removeCategory($catID, $replaceWith)
    {
        /** delete the template category */
        $this->db->where('templates_categories_id', $catID);
        $this->db->delete('template_categories');

        /** delete from template_to_category table **/
        $this->db->where('category_id', $catID);
        $this->db->delete('template_to_category');

        /** replace with */
        if ( $replaceWith != 0 ) {

            $data = array(
                'category_id' => $replaceWith
            );

            $this->db->insert('template_to_category', $data);

        }
    }


    /**
     * Update category name
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

        $this->db->where('templates_categories_id', $catid);
        $this->db->update('template_categories', $data);
    }

    /**
     * Update category for given template
     *
     * @param   integer     $templateID
     * @param   integer     $catID
     * @return  void
     */
    public function setCatForTemplate($templateID, $catID)
    {

        // Delete old entry, if any
        $this->db->where('pages_id', $templateID);
        $this->db->delete('template_to_category');

        // Insert new one
        if ( $catID != 0 ) 
        {
            $data = array(
                    'pages_id' => $templateID,
                    'category_id' => $catID
            );

            $this->db->insert('template_to_category', $data);
        }

    }

}