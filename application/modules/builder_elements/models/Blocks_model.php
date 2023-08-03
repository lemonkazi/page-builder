<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blocks_model extends CI_Model {

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
    public function all($cats = false)
    {
		/*Check settings to show locked icon */
		$showLocked = $this->config->item('show_locked_blocks') == 'yes' ? true : false;
        /** Grab categpries */
        $this->db->from('blocks_categories');
        if ( $cats && !$showLocked ) $this->db->where_in('blocks_categories_id', $cats);
        /** Exclude the All blocks category */
        $this->db->where('blocks_categories_id !=', '1');
        $this->db->order_by('list_order', 'ASC');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get();
        $categories = $query->result_array();

        /** Grab blocks */
        $this->db->from('blocks');
        $query = $this->db->get();
        $blocks = $query->result_array();


		
        /** Build array */
        $final = [];
        foreach ($categories as $category)
        {
            $tempBlocks = [];

            foreach ($blocks as $block)
            {
                if ($block['blocks_category'] == $category['blocks_categories_id'])
                {

					if($cats && !in_array($block['blocks_category'],$cats) && $showLocked)
					{
						$block['blocks_url'] = '';
					
					}
					$tempBlocks[] = $block;
						
                }
				
            }
			//file_put_contents('filename.txt', print_r($tempBlocks, true),FILE_APPEND);
            if ( ! empty($tempBlocks) || (empty($tempBlocks) && $category['blocks_categories_id'] == 1))
            {
				/*if($cats)
				{
					if(!in_array($category['blocks_categories_id'],$cats))
					{
						foreach($tempBlocks as $block)
						{
							$block['blocks_url'] = '';
						}
							
					}
				}*/
				
				$final[$category['category_name']] = $tempBlocks;
            }
        }

        /** Include the All blocks category */
        if ( !$cats || in_array(1, $cats) )
        {
            $this->db->from('blocks_categories');
            $this->db->where('blocks_categories_id', '1');
            $query = $this->db->get();

            $final = array($this->lang->line('blocks_model_all_category') => $blocks) + $final;
        }

        /** Include the saved/favourite blocks */
        $this->db->from('blocks_fav');
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $query = $this->db->get();
        $favblocks = $query->result_array();

        $final = array($this->lang->line('blocks_model_fav_category') => $favblocks) + $final;

        return $final;
    }

    /**
     * Returns all blocks
     *
     * @return  array       $query
     */
    public function allBasic()
    {
        $this->db->from('blocks');
        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * Returns all block categories
     *
     * @return  array       $query
     */
    public function allBlockCategories()
    {
        $this->db->from('blocks_categories');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
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

        $this->db->insert('blocks_categories', $data);
    }

    /**
     * Check if Category name is unique
     *
     * @param   string      $catName
     * @return  boolen      TRUE/FALSE
     */
    public function blockCatNameIsUnique($catName)
    {

        $this->db->from('blocks_categories');
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

        $this->db->where('blocks_categories_id', $catid);
        $this->db->update('blocks_categories', $data);
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
        /** delete the block category */
        $this->db->where('blocks_categories_id', $catID);
        $this->db->delete('blocks_categories');

        /** replace with */
        $data = array(
            'blocks_category' => $replaceWith
        );

        $this->db->where('blocks_category', $catID);
        $this->db->update('blocks', $data);
    }

    /**
     * Update category for block
     *
     * @param   integer     $blockID
     * @param   integer     $updatedCatID
     * @return  void
     */
    public function updateCatForBlock($blockID, $updatedCatID)
    {
        $data = array(
            'blocks_category' => $updatedCatID
        );

        $this->db->where('blocks_id', $blockID);
        $this->db->update('blocks', $data);
    }

    /**
     * Load block
     *
     * @param   integer     $blockID
     * @return  mixed       $query/FALSE
     */
    public function loadBlock($blockID)
    {
        $this->db->from('blocks');
        $this->db->where('blocks_id', $blockID);
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
     * Update block
     *
     * @param   integer     $blockID
     * @param   array       $data
     * @return  void
     */
    public function updateBlock($blockID, $data)
    {
        $this->db->where('blocks_id', $blockID);
        $this->db->update('blocks', $data);
    }

    /**
     * Update's block screenshot URL for given template file
     *
     * @param   integer     $blockID
     * @param   array       $data
     * @return  void
     */
    public function updateScreenshotWithUrl($url, $screenshot)
    {
        $data = array(
            'blocks_thumb' => $screenshot
        );

        $this->db->where('blocks_url', $url);
        $this->db->update('blocks', $data);
    }

    /**
     * Add block
     *
     * @param   array   $data
     * @return  void
     */
    public function addBlock($data)
    {
        $this->db->insert('blocks', $data);

        return $this->db->insert_id();
    }

    /**
     * Delete block
     *
     * @param   integer     $blockID
     * @return  void
     */
    public function deleteBlock($blockID)
    {
        /** retrieve the thumbnail URL and template file URL for deletion */
        $this->db->from('blocks');
        $this->db->where('blocks_id', $blockID);
        $query = $this->db->get();

        $block = $query->row();

        /** delete the thumbnail */
        if ( file_exists("./" . $block->blocks_thumb) ) unlink("./" . $block->blocks_thumb);

        /** delete the template file */
        if (file_exists("./" . $block->blocks_url)) unlink("./" . $block->blocks_url);

        /** delete the data itself */
        $this->db->where('blocks_id', $blockID);
        $this->db->delete('blocks');
    }

    /**
     * Delete block
     *
     * @return  boolean
     */
    public function deleteBlockForUrl($url)
    {

        /** retrieve the thumbnail URL and template file URL for deletion */
        $this->db->from('blocks');
        $this->db->where('blocks_url', $url);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) 
        {

            $block = $query->row();

            /** delete the thumbnail */
            if ( file_exists("./" . $block->blocks_thumb) ) unlink("./" . $block->blocks_thumb);

            /** delete the template file */
            if (file_exists("./" . $block->blocks_url)) unlink("./" . $block->blocks_url);

            /** delete the data itself */
            $this->db->where('blocks_url', $url);
            $this->db->delete('blocks');

            return true;

        }
        else
        {
            return false;
        }
    }

    /**
     * Load template files
     *
     * @return  array       $templates
     */
    public function loadTemplateFiles()
    {
        $templates = [];
        $templates[] = "";

        $di = new RecursiveDirectoryIterator($this->config->item('elements_dir'), RecursiveDirectoryIterator::SKIP_DOTS);
        $it = new RecursiveIteratorIterator($di);

        foreach ($it as $file)
        {
            if (pathinfo($file, PATHINFO_EXTENSION) == "html" && strpos($file, 'skeleton') === false)
            {
                $templates[] = (string)$file;
            }
        }

        sort($templates);

        return $templates;
    }

    /**
     * Updates the block's original template file
     *
     * @return  boolean
     */
    public function updateOriginal($template, $source)
    {

        $this->load->helper('file');

        $template = str_replace($this->config->item('base_url'), '', $template);
        $content = $source;

        if ( file_exists('./' . $template) )
        {
            if ( write_file('./' . $template, $content) ) 
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }

}