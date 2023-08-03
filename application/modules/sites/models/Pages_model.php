<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model {

	/**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $model_list = [
            'sites/Sites_model' => 'MSites'
        ];
        $this->load->model($model_list);
    }

    /**
     * Get page meta data for an entire site
     *
     * @param  integer 	$site_id
     * @return mixed 	$return/FALSE
     */
    public function getPageData($site_id)
    {
    	$query = $this->db->from('pages')->where('sites_id', $site_id)->get();

    	if ($query->num_rows() > 0)
    	{
    		$res = $query->result();
    		$return = array();
    		foreach ($res as $page)
    		{
                // Include all frames for this page
                $query = $this->db->from('frames')->where('pages_id', $page->pages_id)->where('revision', 0)->get();
                if ($query->num_rows() > 0)
                {
                    $page->frames = $query->result();
                }
                else
                {
                    $page->frames = NULL;
                }
    			$return[$page->pages_name] = $page;
    		}

    		return $return;
    	}
    	else
    	{
    		return FALSE;
    	}

    }

    /**
     * Get page and frame data for an entire site
     *
     * @param  integer 	$site_id
     * @return mixed 	$return/FALSE
     */
    public function get_all($site_id = NULL)
    {
    	if ($site_id)
    	{
    		$this->db->where('sites_id', $site_id);
    	}
    	$q_pages = $this->db->get('pages');
    	if ($q_pages->num_rows() > 0)
    	{
    		$return = [];
    		$res = $q_pages->result_array();
    		foreach ($res as $page)
    		{
                $this->db->where('pages_id', $page['pages_id']);
                $this->db->where('revision', 0);
                $q_frames = $this->db->get('frames');
                if ($q_frames->num_rows() > 0)
                {
                    $page['frames'] = $q_frames->result_array();
                }
                else
                {
                    $page['frames'] = NULL;
                }
    			$return[$page['pages_name']] = $page;
    		}

    		return $return;
    	}
    	else
    	{
    		return FALSE;
    	}

    }

    /**
     * Retrieves meta data for single page, using the site_id and page name
     *
     * @param  integer 	$site_id
     * @param  string 	$pageName
     * @return mixed 	$res/FALSE
     */
    public function getSinglePage($site_id, $pageName)
    {
    	$query = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $pageName)->get();
    	if ($query->num_rows() > 0)
    	{
    		$res = $query->result();
    		return $res[0];
    	}
    	// No match found
    	else
    	{
    		return FALSE;
    	}
    }

    /**
     * Updates an existing page item
     *
     * @param   int         $siteID
     * @param   array       $data
     * @return  void
     */
    public function update_fields($pageID, $data)
    {
        /** Update the site details */
        $this->db->where('pages_id', $pageID);
        $this->db->update('pages', $data);
    }	
	
    /**
     * Updates page meta data
     *
     * @param  	array 	$pageData
     * @return 	void
     */
    public function updatePageData($pageData)
    {
    	// Do we have a pageID?
    	if ($pageData['pageID'] != '')
    	{
    		$data = array(
				'pages_title' 				=> $pageData['pageData_title'],
				'pages_meta_keywords' 		=> $pageData['pageData_metaKeywords'],
				'pages_meta_description' 	=> $pageData['pageData_metaDescription'],
				'pages_header_includes' 	=> $pageData['pageData_headerIncludes'],
				'pages_css' 				=> $pageData['pageData_headerCss']
   			);
    		$this->db->where('pages_id', $pageData['pageID']);
    		$this->db->update('pages', $data);
    	}
    	else
    	{
    		// No pageID given, create a new page in the db
    		$data = array(
    		   'sites_id' 				=> $pageData['siteID'],
    		   'pages_name' 			=> $pageData['pageName'],
    		   'pages_timestamp' 		=> time(),
    		   'pages_title' 			=> $pageData['pageData_title'],
    		   'pages_meta_keywords' 	=> $pageData['pageData_metaKeywords'],
    		   'pages_meta_description' => $pageData['pageData_metaDescription'],
    		   'pages_header_includes' 	=> $pageData['pageData_headerIncludes'],
			   'pages_css' 				=> $pageData['pageData_headerCss']
    		);
    		$this->db->insert('pages', $data);
    	}
    }

	/**
	 * Deletes a single page
	 *
	 * @param  	integer 	$site_id
	 * @param  	string 		$page
	 * @return 	void
	 */
	public function delete($site_id, $page)
	{
		$query = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $page)->get();

		if ($query->num_rows() > 0)
		{
			$res = $query->result();
			$page = $res[0];
			// Remove frames
			$this->db->where('pages_id', $page->pages_id);
			$this->db->delete('frames');
			// Delete page
			$this->db->where('pages_id', $page->pages_id);
			$this->db->delete('pages');
		}

	}

	/**
	 * Creates a new page template or saves existing one
	 *
	 * @param  	array 		$siteData
	 * @param  	string 		$contents
	 * @param  	integer 	$templateID
	 * @return 	mixed 		$pageID/$templateID
	 */
	public function saveTemplate($siteData, $contents = '', $templateID = 0, $catID = 0)
	{

		$this->load->helper('base64');

		reset($siteData);
		$pageName = key($siteData);

		// Cerate new template
		if ($templateID == 0)
		{
			$pagePreview = ($contents != '') ? custom_base64_decode($contents) : '';

			$data = array(
				'pages_name' 				=> $pageName,
				'pages_timestamp' 			=> time(),
				'pages_preview' 			=> $pagePreview,
				'pages_template' 			=> 1,
                'pages_title' 				=> $siteData[$pageName]['pageSettings']['title'],
                'pages_meta_keywords' 		=> $siteData[$pageName]['pageSettings']['meta_keywords'],
                'pages_meta_description' 	=> $siteData[$pageName]['pageSettings']['meta_description'],
                'pages_header_includes' 	=> $siteData[$pageName]['pageSettings']['header_includes'],
                'pages_css' 				=> $siteData[$pageName]['pageSettings']['page_css']
			);


			$this->db->insert('pages', $data);

			if ($this->db->affected_rows() == 1)
			{
				$pageID = $this->db->insert_id();
				$frames = $siteData[$pageName]['blocks'];

    			// Page is done, now all the frames for this page
    			if (is_array($frames))
    			{
    				foreach ($frames as $frameData)
    				{
    					$data = array(
    						'pages_id' 				=> $pageID,
    						'frames_content' 		=> $this->MSites->processFrameContent(custom_base64_decode($frameData['frameContent'])),
    						'frames_height' 		=> $frameData['frameHeight'],
    						'frames_original_url' 	=> $frameData['originalUrl'],
							'frames_sandbox' 		=> ($frameData['sandbox'])? 1: 0,
							'frames_loaderfunction' => $frameData['loaderFunction'],
    						'frames_timestamp' 		=> time(),
    						'frames_embeds'         => (isset($block['frames_embeds'])) ? $block['frames_embeds'] : ''
    					);

    					$this->db->insert('frames', $data);
    				}
    			}

    			// Popups?
    			if ( isset($siteData[$pageName]['popups']) && is_array($siteData[$pageName]['popups']) ) {

    				foreach( $siteData[$pageName]['popups'] as $popup ) {

    					$data = array(
	                        'pages_id'              => $pageID,
	                        'frames_content'        => $this->MSites->processFrameContent(custom_base64_decode($popup['frameContent'])),
	                        'frames_height'         => $popup['frameHeight'],
	                        'frames_original_url'   => $popup['originalUrl'],
	                        'frames_sandbox'        => ($popup['sandbox'] == 'TRUE') ? 1 : 0,
	                        'frames_loaderfunction' => $popup['loaderFunction'],
	                        'frames_timestamp'      => time(),
	                        'frames_popup'          => (isset($popup['type'])) ? $popup['type']: 'entry',
	                        'frames_embeds'         => (isset($popup['frames_embeds']))? $popup['frames_embeds'] : '',
	                        'frames_settings'       => (isset($popup['additionalSettings'])) ? json_encode($popup['additionalSettings']) : ''
	                    );

	                    $this->db->insert('frames', $data);

    				}

    			}

                // categoryID?
                if ( $catID != 0 )
                {

                	$data = array(
						'pages_id' => $pageID,
						'category_id' => $catID
					);

					$this->db->insert('template_to_category', $data);

                }

			}
			else
			{
				$pageID = FALSE;
			}

			return $pageID;
		}
		// Update existing template
		else
		{
			$pagePreview = ($contents != '') ? custom_base64_decode($contents) : '';

			$data = array(
				'pages_name' 				=> $pageName,
				'pages_timestamp' 			=> time(),
				'pages_preview' 			=> $pagePreview,
				'pages_template' 			=> 1,
                'pages_title' 				=> $siteData[$pageName]['pageSettings']['title'],
                'pages_meta_keywords' 		=> $siteData[$pageName]['pageSettings']['meta_keywords'],
                'pages_meta_description' 	=> $siteData[$pageName]['pageSettings']['meta_description'],
                'pages_header_includes' 	=> $siteData[$pageName]['pageSettings']['header_includes'],
                'pages_css' 				=> $siteData[$pageName]['pageSettings']['page_css']
			);

			$this->db->where('pages_id', $templateID);
			$this->db->update('pages', $data);
			// Delete old frames
			$this->db->where('pages_id', $templateID);
			$this->db->delete('frames');
			// Insert new frames
			$frames = $siteData[$pageName]['blocks'];

			if (is_array($frames))
			{
				foreach ($frames as $frameData)
				{
					$data = array(
						'pages_id' 				=> $templateID,
                        'frames_content' 		=> custom_base64_decode($frameData['frameContent']),
						'frames_height' 		=> $frameData['frameHeight'],
						'frames_original_url' 	=> $frameData['originalUrl'],
						'frames_sandbox' 		=> ($frameData['sandbox'] == 'true') ? 1 : 0,
						'frames_loaderfunction' => $frameData['loaderFunction'],
						'frames_timestamp' 		=> time()
					);

					$this->db->insert('frames', $data);
				}
			}

			// Popups?
			if ( isset($siteData[$pageName]['popups']) && is_array($siteData[$pageName]['popups']) ) {

				foreach( $siteData[$pageName]['popups'] as $popup ) {

					$data = array(
                        'pages_id'              => $templateID,
                        'frames_content'        => $this->MSites->processFrameContent(custom_base64_decode($popup['frameContent'])),
                        'frames_height'         => $popup['frameHeight'],
                        'frames_original_url'   => $popup['originalUrl'],
                        'frames_sandbox'        => ($popup['sandbox'] == 'TRUE') ? 1 : 0,
                        'frames_loaderfunction' => $popup['loaderFunction'],
                        'frames_timestamp'      => time(),
                        'frames_popup'          => (isset($popup['type'])) ? $popup['type']: 'entry',
                        'frames_embeds'         => (isset($popup['frames_embeds']))? $popup['frames_embeds'] : '',
                        'frames_settings'       => (isset($popup['additionalSettings'])) ? json_encode($popup['additionalSettings']) : ''
                    );

                    $this->db->insert('frames', $data);

				}

			}

            // categoryID?

            $this->db->where('pages_id', $templateID);
		    $this->db->delete('template_to_category');

            if ( $catID != 0 )
            {
		       
	            $data = array(
	                    'pages_id' => $templateID,
	                    'category_id' => $catID
	            );

	            $this->db->insert('template_to_category', $data);

            }

			return $templateID;
		}
	}

	/**
	 * Get all templates
	 *
	 * @param 	array 	$pages_id
	 * @return mixed 	$templates/FALSE
	 */
	public function getAllTemplates($pages_id = NULL)
	{
		$templates = array();
		if ($pages_id)
		{
			$this->db->where_in('pages_id', $pages_id);
		}
		$q = $this->db->from('pages')->where('pages_template', '1')->get();

		if ($q->num_rows() > 0)
		{
			$pages = $q->result();

    		// Now we need all frames for each page
    		foreach ($pages as $page)
    		{
    			$pageFrames = array();
    			$q = $this->db->from('frames')->where('pages_id', $page->pages_id)->where('revision', 0)->get();

    			if ( $q->num_rows() > 0 )
    			{

    			foreach ($q->result() as $f)
    			{
	    			$frame = array();
					$frame['pageName'] = $page->pages_name;
					$frame['pageID'] = $page->pages_id;
	    			$frame['id'] = $f->frames_id;
	    			$frame['height'] = $f->frames_height;
	    			$frame['original_url'] = $f->frames_original_url;
	    			$frame['thumb'] = $page->pagethumb;
	    			$pageFrames[] = $frame;
	    		}

	    		$templates[$page->pages_id] = $pageFrames;

	    		}
			}

			return $templates;
		}
		else
		{
			return FALSE;
		}

	}

	/**
	 * Get all templates
	 *
	 * @return 	mixed 	result object / FALSE
	 */
	public function get_templates($pages_id = NULL)
	{
		if ($pages_id)
		{
			$this->db->where_in('pages_id', $pages_id);
		}
		$this->db->select('pages.pages_id, pages.pages_id, pages.pages_name, pages.pages_timestamp, pages.pages_title, pages.pages_meta_keywords, pages.pages_meta_description, pages.pages_header_includes, pages.pages_preview, pages.pages_template, pages.pages_css, pages.created_at, pages.modified_at, pages.pagethumb, pages.google_fonts, category_id, category_name');
		$this->db->from('pages');
		$this->db->where('pages_template', '1');
		$this->db->join('template_to_category', 'pages.pages_id = template_to_category.pages_id', 'left');
		$this->db->join('template_categories', 'template_to_category.category_id = template_categories.templates_categories_id', 'left');

		$this->db->order_by('category_name');

		$q = $this->db->get();

		if ($q->num_rows() > 0)
		{
			return $q->result();
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Delete page template
	 *
	 * @param 	integer 	$siteID
	 * @param 	integer 	$pageID
	 * @return 	void
	 */
	public function deleteTemplate($siteID, $pageID)
	{
		// Start by deleting the frames
		$this->db->where('pages_id', $pageID);
		$this->db->delete('frames');

		// Delete the page itself
		$this->db->where('pages_id', $pageID);
		$this->db->delete('pages');

		// Delete from template_to_category
		$this->db->where('pages_id', $pageID);
		$this->db->delete('template_to_category');

	}

	/**
	 * Grabs all the blocks on this page, mixes them into a single HTML document and return the result
	 *
	 * @todo 		remove in version 2+
	 * @deprecated 	Use load_page function instead
	 * @param 		integer 	$page_id
	 * @return 		string 	$str
	 */
    public function loadPage($page_id)
    {
	    $this->load->library('simple_html_dom');

	    // Grab the frames
	    $q = $this->db->from('frames')->where('pages_id', $page_id)->get();
	    if ($q->num_rows() > 0)
	    {
	    	//get the skeleton
	    	$theSkeleton = file_get_html('./elements/skeleton.html');
		    foreach ($q->result() as $frame)
		    {
			    $html = str_get_html($frame->frames_content);
			    $block = $html->find('div[id=page]', 0)->innertext;
			    $theSkeleton->find('div[id=page]', 0)->innertext .= $block;
		    }
		    $str = $theSkeleton;

		    $str = str_replace("../", "", $str);

		    echo $str;
	    }
    }

    /**
	 * Grabs all the blocks on this page, mixes them into a single HTML document and return the result
	 *
	 * @param 	integer 	$page_id
	 * @return 	string 		$str
	 */
    public function load_page($page_id)
    {
	    $this->load->library('simple_html_dom');

	    // Grab the frames
	    $this->db->where('pages_id', $page_id);
	    $this->db->where('revision', 0);
	    $q = $this->db->get('frames');
	    if ($q->num_rows() > 0)
	    {
	    	// Get the skeleton
	    	//$theSkeleton = file_get_html('./elements/skeleton.html');
	    	$theSkeleton = str_get_html(file_get_contents('./elements/skeleton.html'), true, true, DEFAULT_TARGET_CHARSET, false);
		    foreach ($q->result() as $frame)
		    {
		    	if ( $frame->frames_popup === '' ) // Regular frames
		    	{

			    	$html = str_get_html($frame->frames_content, true, true, DEFAULT_TARGET_CHARSET, false);
			    	$block = $html->find('div[id=page]', 0)->innertext;
			    	$theSkeleton->find('div[id=page]', 0)->innertext .= $block;

				}
				else // Popups
				{	

					$attributes = '';

					if ( $frame->frames_popup === 'entry' ) 
					{
						$attributes .= 'data-popup="entry" ';
					} else if ( $frame->frames_popup === 'exit' ) 
					{
						$attributes .= 'data-popup="exit" ';
					}

					if ( $frame->frames_settings != '' )
					{
						$settings = json_decode($frame->frames_settings, true);

						if ( isset($settings['popupReoccurrence']) )
						{
							if ( $settings['popupReoccurrence'] == 'Once' ) $attributes .= 'data-popup-occurrence="once" ';
							else  if ( $settings['popupReoccurrence'] == 'All' ) $attributes .= 'data-poopup-occurrence="all"  ';
						}

						if ( isset($settings['popupDelay']) )
						{
							$attributes .= 'data-popup-delay="' . $settings['popupDelay'] . '" ';
						}

						if ( $frame->frames_popup === 'regular' && isset($settings['popupID']) )
						{
							$attributes .= 'id="' . $settings['popupID'] . '"';
						} 
					}

					$attributes .= 'data-popup-id="' . $page_id . '" ';

					$html = str_get_html($frame->frames_content, true, true, DEFAULT_TARGET_CHARSET, false);
					$popup = sprintf($this->config->item('popup_wrapping_html'), $attributes, $html->find('.modal .modal-body', 0)->innertext);
			    	$theSkeleton->find('div[id=popups]', 0)->innertext .= $popup;

				}
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

}