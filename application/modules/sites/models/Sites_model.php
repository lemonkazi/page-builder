<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sites_model extends CI_Model {

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
     * Get site details by ID
     *
     * @param   integer     $id
     * @return  array       $data
     */
    public function get_by_id($id)
    {
        $data = [];
        $this->db->where('sites_id', $id);
        $this->db->where('sites_trashed', 0);
        $query = $this->db->get('sites');
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $data = $row;
            }
        }

        $query->free_result();
        return $data;
    }

    /**
     * Get user id
     * @param int $siteId
     * @return int $userId
     */
    public function getUserIdBySiteId($siteId)
    {
        $this->db->select('sites.users_id');
        $this->db->where('sites_id', $siteId);
        $query = $this->db->get('sites');
        $userId = $query->result()[0]->users_id;

        return $userId;
    }

    /**
     * Get site details by any field value
     *
     * @param   string      $field
     * @param   string      $value
     * @return  array       $data
     */
    public function get_by_field_value($field, $value, $userID = false)
    {
        $data = [];
        $this->db->select('sites.*, users.status');
        $this->db->where($field, $value);
        $this->db->where('sites_trashed', 0);
        $this->db->join('users', 'sites.users_id = users.id');
        if ($userID) $this->db->where('users_id', $userID);
        $query = $this->db->get('sites');
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $data[] = $row;
            }
        }

        $query->free_result();
        return $data;
    }

    /**
     * Returns all available sites
     *
     * @param   integer     $user_id
     * @return  array       $allSites
     */
    public function get_all($user_id = '')
    {
        /** If $user_id is set, this means we're looking for the sites belonging to a specific user */
        $this->db->select('s.*, COUNT(p.sites_id) as page_count, u.first_name, u.last_name, s.users_id');
        $this->db->from('sites s');
        if ($user_id == '')
        {
            if ($this->session->userdata('user_type') != 'Admin')
            {
                $this->db->where('s.users_id', $this->session->userdata('user_id'));
            }
        }
        else
        {
            $this->db->where('s.users_id', $user_id);
        }

        $this->db->where('s.sites_trashed', 0);
        $this->db->where('s.sites_id !=', 0);
        $this->db->join('pages p', 's.sites_id = p.sites_id', 'left');
        $this->db->join('users u', 's.users_id = u.id', 'left');
        $this->db->group_by('s.sites_id');
        $query = $this->db->get();
        $res = $query->result();

        return $res;
    }

    public function get_all_limit($user_id = '', $limit, $offset)
    {
        /** If $user_id is set, this means we're looking for the sites belonging to a specific user */
        $this->db->select('s.*, COUNT(p.sites_id) as page_count, u.first_name, u.last_name, s.users_id');
        $this->db->from('sites s');
        if ($user_id == '')
        {
            if ($this->session->userdata('user_type') != 'Admin')
            {
                $this->db->where('s.users_id', $this->session->userdata('user_id'));
            }
        }
        else
        {
            $this->db->where('s.users_id', $user_id);
        }

        $this->db->where('s.sites_trashed', 0);
        $this->db->where('s.sites_id !=', 0);
        $this->db->join('pages p', 's.sites_id = p.sites_id', 'left');
        $this->db->join('users u', 's.users_id = u.id', 'left');
        $this->db->group_by('s.sites_id');

        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $res = $query->result();

        $res['pageCount'] = $this->getTotalPageCount($user_id, $limit);

        return $res;
    }

    private function getTotalPageCount($user_id = '', $limit)
    {
        /** If $user_id is set, this means we're looking for the sites belonging to a specific user */
        $this->db->select('s.sites_id');
        $this->db->from('sites s');
        if ($user_id == '')
        {
            if ($this->session->userdata('user_type') != 'Admin')
            {
                $this->db->where('s.users_id', $this->session->userdata('user_id'));
            }
        }
        else
        {
            $this->db->where('s.users_id', $user_id);
        }

        $this->db->where('s.sites_trashed', 0);
        $this->db->where('s.sites_id !=', 0);
        $this->db->join('pages p', 's.sites_id = p.sites_id', 'left');
        $this->db->join('users u', 's.users_id = u.id', 'left');
        $this->db->group_by('s.sites_id');
        $count = $this->db->count_all_results();

        return ceil($count/$limit);
    }

    /**
     * Site count by user
     *
     * @param   integer     $user_id
     * @return  array       $query
     */
    public function site_by_user($user_id)
    {
        $this->db->where('users_id', $user_id);
        $this->db->where('sites_trashed', 0);
        $this->db->from('sites');
        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * Checks to see if a site belongs to this user
     *
     * @param   integer     $siteID
     * @return  boolean
     */
    public function isMine($site_id)
    {
        $user_id = $this->session->userdata('user_id');
        $q = $this->db->from('sites')->where('sites_id', $site_id)->get();
        if ($q->num_rows() > 0)
        {
            $res = $q->result();
            if ($res[0]->users_id != $user_id)
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Creates a new, empty shell site
     *
     * @return  integer     $new_site_id;
     */
    public function createNew($templateID)
    {
        $user_id = $this->session->userdata('user_id');

        // Get the page thumnail
        if ( $templateID )
        {
            $this->db->from('pages');
            $this->db->where('pages_id', $templateID);
            $q = $this->db->get();

            $pagedata = $q->row();

            $pagethumb = $pagedata->pagethumb;
			$page_title = $pagedata->pages_title;
			$page_meta_description = $pagedata->pages_meta_description;
			$page_meta_keywords = $pagedata->pages_meta_keywords;
			$page_header_includes= $pagedata->pages_header_includes;
			$page_css = $pagedata->pages_css;
			$page_google_font = $pagedata->google_fonts;
        }
        else
        {
            $pagethumb = "";
        }

    	/** Create site */
    	$data = array(
            'sites_name'        => $this->lang->line('sites_model_mynewsite'),
            'users_id'          => $user_id,
            'sitethumb'         => $pagethumb,
            'sites_created_on'  => time(),
            'created_at'        => date("Y-m-d H:i:s")
        );
    	$this->db->insert('sites', $data);

    	$new_site_id = $this->db->insert_id();

    	/** Create empty index page */
        $data = array(
            'sites_id'          => $new_site_id,
            'pages_name'        => 'index',
            'pagethumb'         => $pagethumb,
            'pages_timestamp'   => time(),
            'created_at'        => date("Y-m-d H:i:s")
        );
		
		if ($templateID)
		{
			$data['pages_title'] = $page_title;
			$data['pages_meta_description'] = $page_meta_description;
			$data['pages_meta_keywords'] = $page_meta_keywords;
			$data['pages_header_includes'] = $page_header_includes;
			$data['pages_css'] = $page_css;	
			$data['google_fonts'] = $page_google_font;
		}
		
        $this->db->insert('pages', $data);

        // Template?
        if ($templateID)
        {
            $pageID = $this->db->insert_id();
            // grab the frames
            $this->db->from('frames');
            $this->db->where('pages_id', $templateID);
            $q = $this->db->get();

            foreach ($q->result_array() as $frame)
            {
                $data = array(
                    'pages_id'              => $pageID,
                    'sites_id'              => $new_site_id,
                    'frames_content'        => $this->processFrameContent($frame['frames_content']),
                    'frames_height'         => $frame['frames_height'],
                    'frames_original_url'   => $frame['frames_original_url'],
                    'frames_sandbox'        => $frame['frames_sandbox'],
                    'frames_loaderfunction' => $frame['frames_loaderfunction'],
                    'frames_popup'          => $frame['frames_popup'],
                    'frames_embeds'         => $frame['frames_embeds'],
                    'frames_settings'       => $frame['frames_settings'],
                    'frames_timestamp'      => time(),
                    'created_at'            => date("Y-m-d H:i:s")
                );
                $this->db->insert('frames', $data);
            }

        }

        return $new_site_id;
    }

    /**
     * Creates a new site item, including pages and frames
     *
     * @param   string      $site_name
     * @param   array       $pages
     * @return  integer     $site_id
     */
    public function create($site_name, $pages)
    {
    	$user_id = $this->session->userdata('user_id');

    	/** Create the site item first */
        $data = array(
            'users_id'          => $user_id,
            'sites_name'        => $site_name,
            'sites_created_on'  => time(),
            'created_at'        => date("Y-m-d H:i:s")
        );
        $this->db->insert('sites', $data);
        $site_id = $this->db->insert_id();

        /** Next we create the pages and frames */
        foreach ($pages as $page_name => $frames)
        {
            $data = array(
                'sites_id'          => $site_id,
                'pages_name'        => $page_name,
                'pages_timestamp'   => time(),
                'created_at'        => date("Y-m-d H:i:s")
            );
            $this->db->insert('pages', $data);

            $page_id = $this->db->insert_id();

            /** Page is done, now all the frames for this page */
            foreach ($frames as $frame_data)
            {
                $data = array(
                    'pages_id'              => $page_id,
                    'sites_id'              => $site_id,
                    'frames_content'        => $frame_data['frameContent'],
                    'frames_height'         => $frame_data['frameHeight'],
                    'frames_original_url'   => $frame_data['originalUrl'],
                    'frames_sandbox'        => $frame_data['frameSandbox'],
                    'frames_loaderfunction' => $frame_data['frameLoaderfunction'],
                    'frames_timestamp'      => time(),
                    'created_at'            => date("Y-m-d H:i:s")
                );
                $this->db->insert('frames', $data);
            }
        }

        return $site_id;
    }

    /**
     * Clone an existing site including pages and frames
     *
     * @param   array       $site
     * @param   array       $pages
     * @return  integer     $site_id
     */
    public function clone_site($site, $pages)
    {
        $user_id = $this->session->userdata('user_id');

        /** Create the site item first */
        $data = array(
            'users_id'          => $user_id,
            'sites_name'        => $site['sites_name'] . ' - Clone',
            'sites_created_on'  => time(),
            'sitethumb'         => $site['sitethumb'],
            'created_at'        => date("Y-m-d H:i:s")
        );
        $this->db->insert('sites', $data);
        $site_id = $this->db->insert_id();

        /** Next we create the pages and frames */
        foreach ($pages as $page_name => $frames)
        {
            $data = array(
                'sites_id'          => $site_id,
                'pages_name'        => $page_name,
                'pages_timestamp'   => time(),
                'created_at'        => date("Y-m-d H:i:s")
            );
            $this->db->insert('pages', $data);
            $page_id = $this->db->insert_id();

            /** Page is done, now all the frames for this page */
            /** Check if the page has any frames */
            if (count($frames['frames']) > 0)
            {
                foreach ($frames['frames'] as $frame_data)
                {
                    $data = array(
                        'pages_id'              => $page_id,
                        'sites_id'              => $site_id,
                        'frames_content'        => $frame_data['frames_content'],
                        'frames_height'         => $frame_data['frames_height'],
                        'frames_original_url'   => $frame_data['frames_original_url'],
                        'frames_sandbox'        => $frame_data['frames_sandbox'],
                        'frames_loaderfunction' => $frame_data['frames_loaderfunction'],
                        'frames_timestamp'      => time(),
                        'created_at'            => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('frames', $data);
                }
            }
        }

        return $site_id;
    }

    /**
     * Updates an existing site item
     *
     * @param   int         $siteID
     * @param   array       $data
     * @return  void
     */
    public function update_fields($siteID, $data)
    {
        /** Update the site details */
        $this->db->where('sites_id', $siteID);
        $this->db->update('sites', $data);
    }

    /**
     * Updates an existing site item, including pages and frames
     *
     * @param   array       $siteData
     * @param   array       $pages
     * @return  void
     */
    public function update($siteData, $pages)
    {
        $this->load->helper('base64');

        /** Update the site details first */
        $data = array(
            'sites_name'            => $siteData['sites_name'],
            'sites_lastupdate_on'   => time(),
            'viewmode'              => isset($siteData['responsiveMode']) ? $siteData['responsiveMode'] : 'desktop'
        );
        $this->db->where('sites_id', $siteData['sites_id']);
        if ($this->session->userdata('user_type') != "Admin") $this->db->where('users_id', $this->session->userdata('user_id'));
        $this->db->update('sites', $data);

        /** If there are no affected sites, this means the user is not an admin and tries to update a site which does not belong to him her */
        if ($this->db->affected_rows() == 0)
        {
            return false;
        }

        /** Update the pages */
        foreach ($pages as $page => $pageData)
        {

            /** If there is no page or site */
            if ($page == '0' || ! $siteData['sites_id'])
            {
                return false;
            }

            /** Dealing with a changed page */
            if ($pageData['status'] == 'changed')
            {
                if ( ! isset($pageData['pageID']) || $pageData['pageID'] == 0)
                {
                    $query = $this->db->from('pages')->where('sites_id', $siteData['sites_id'])->where('pages_name', $page)->get();

                    /** Prevent stuff from breaking */
                    if ($query->num_rows() == 0)
                    {
                        return false;
                    }

                    $pageDataOld = $query->result();
                    $pageID = $pageDataOld[0]->pages_id;
                }
                else
                {
                    $pageID = $pageData['pageID'];
                }

                $data = array(
                    'pages_name'                => $page,
                    'pages_timestamp'           => time(),
                    'pages_title'               => $pageData['pageSettings']['title'],
                    'pages_meta_keywords'       => $pageData['pageSettings']['meta_keywords'],
                    'pages_meta_description'    => $pageData['pageSettings']['meta_description'],
                    'pages_header_includes'     => $pageData['pageSettings']['header_includes'],
                    'pages_css'                 => $pageData['pageSettings']['page_css'],
                    'google_fonts'              => (isset($pageData['pageSettings']['google_fonts'])) ? json_encode($pageData['pageSettings']['google_fonts']) : ''
                );
                $this->db->where('pages_id', $pageID);
                $this->db->update('pages', $data);
            }
            elseif ($pageData['status'] == 'new')
            {
                $data = array(
                    'sites_id'                  => $siteData['sites_id'],
                    'pages_name'                => $page,
                    'pages_timestamp'           => time(),
                    'pages_title'               => $pageData['pageSettings']['title'],
                    'pages_meta_keywords'       => $pageData['pageSettings']['meta_keywords'],
                    'pages_meta_description'    => $pageData['pageSettings']['meta_description'],
                    'pages_header_includes'     => $pageData['pageSettings']['header_includes'],
                    'pages_css'                 => $pageData['pageSettings']['page_css']
                );
                $this->db->insert('pages', $data);
                $pageID = $this->db->insert_id();
            }

            /** Page done, onto the blocks */

            /** Check revision, if needed remove the older one. */
            $max_limit = $this->config->item('max_number_or_revision');
            $sites_id = $siteData['sites_id'];
            $this->load->model('sites/Frames_model', 'MFrames');
            $revisions = $this->MRevisions->getForSite($sites_id, $page, 'DESC');
            if ($revisions && count($revisions) >= $max_limit)
            {
                foreach ($revisions as $key => $value)
                {
                    if ($key >= ($max_limit - 1))
                    {
                        $this->MRevisions->delete($sites_id, $value->frames_timestamp, $page);
                    }
                }
            }

            /** Push existing frames into revision */
            $data = array(
                'revision' => 1
            );
            $this->db->where('pages_id', $pageID);
            $this->db->update('frames', $data);

            if (isset($pageData['blocks']))
            {
            	foreach ($pageData['blocks'] as $block)
                {
                	$data = array(
                        'pages_id'              => $pageID,
                        'sites_id'              => $siteData['sites_id'],
                        'frames_content'        => $this->processFrameContent(custom_base64_decode($block['frameContent'])),
                        'frames_height'         => $block['frameHeight'],
                        'frames_original_url'   => $block['originalUrl'],
                        'frames_sandbox'        => ($block['sandbox'] == 'TRUE') ? 1 : 0,
                        'frames_loaderfunction' => $block['loaderFunction'],
                        'frames_timestamp'      => time(),
                        'frames_global'         => (isset($block['frames_global'])) ? 1: 0,
                        'frames_embeds'         => (isset($block['frames_embeds'])) ? $block['frames_embeds'] : '',
                    );

                	$this->db->insert('frames', $data);
                }
            }

            if (isset($pageData['popups']))
            {
                foreach ($pageData['popups'] as $popup)
                {
                    $data = array(
                        'pages_id'              => $pageID,
                        'sites_id'              => $siteData['sites_id'],
                        'frames_content'        => $this->processFrameContent(custom_base64_decode($popup['frameContent'])),
                        'frames_height'         => $popup['frameHeight'],
                        'frames_original_url'   => $popup['originalUrl'],
                        'frames_sandbox'        => ($popup['sandbox'] == 'TRUE') ? 1 : 0,
                        'frames_loaderfunction' => $popup['loaderFunction'],
                        'frames_timestamp'      => time(),
                        'frames_global'         => (isset($popup['frames_global']))? 1: 0,
                        'frames_popup'          => (isset($popup['type'])) ? $popup['type']: 'entry',
                        'frames_embeds'         => (isset($popup['frames_embeds']))? $popup['frames_embeds'] : '',
                        'frames_settings'       => (isset($popup['additionalSettings'])) ? json_encode($popup['additionalSettings']) : ''
                    );

                    $this->db->insert('frames', $data);
                }
            }

        }

        return true;
    }

    /**
     * Updates a site's meta data (name, ftp details, etc)
     *
     * @param   string      $frameContent
     * @return  string      $raw
     */
    public function processFrameContent($frameContent)
    {
        $this->load->library('Simple_html_dom');

        $raw = str_get_html($frameContent, true, true, DEFAULT_TARGET_CHARSET, false);

        /** remove data-selector attributes */
        foreach($raw->find('*[data-selector]') as $element)
        {
            /** remove attribute */
            $element->removeAttribute("data-selector");
        }

        /** remove draggable attributes */
        foreach($raw->find('*[draggable]') as $element)
        {
            $element->removeAttribute("draggable");
        }

        /** remove builder scripts (these are injected when loading the iframes) */
        foreach($raw->find('script.builder') as $element)
        {
            $element->outertext = '';
        }

        /** remove background images for parallax blocks */
        foreach($raw->find('*[data-parallax]') as $element)
        {
            $oldCss = $element->getAttribute('style');
            $replaceWith = "background-image: none";

            $regex = '/(background-image: url\((["|\']?))(.+)(["|\']?\))/';

            $oldCss = preg_replace($regex, $replaceWith, $oldCss);

            $element->setAttribute('style', $oldCss);
        }

        /** remove data-hover="true" attribute **/
        foreach($raw->find('*[data-hover]') as $element)
        {
            $element->removeAttribute("data-hover");
        }

        /** remove the sb_hover class name **/
        foreach($raw->find('*[class*="sb_hover"]') as $element)
        {
            $element->class = str_replace('sb_hover', '', $element->class);
        }

        /** remove .canvasElToolbar elements **/
        foreach($raw->find('div.canvasElToolbar') as $el)
        {
            $el->outertext = '';
        }

        return $raw;
    }

    /**
     * Set home page flag 0 to all sites
     *
     * @return  boolean
     */
    public function remove_home_page()
    {
        $data = array('home_page' => 0);
        $this->db->update('sites', $data);

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
     * Updates a site's meta data (name, ftp details, etc)
     *
     * @param   array       $site_data
     * @return  boolean
     */
    public function updateSiteData($site_data)
    {
        $this->load->helper('str');
        $this->load->model('shared/Ftp_model', 'MFtp');

        $site_data = array_map('trim', $site_data);
        /** test the FTP data */
        $ftp_ok = 0;
        if (isset($site_data['ftp_type']))
        {
            $path = ($site_data['ftp_path'] != '') ? $site_data['ftp_path'] : "/";
            $result = $this->MFtp->test($site_data['ftp_server'], $site_data['ftp_user'], $site_data['ftp_password'], $site_data['ftp_port'], $path, $site_data['ftp_type']);

            if ($result['connection'])
            {
                $ftp_ok = 1;
            }
        }

        $custom_domain = isset($site_data['custom_domain']) ? $site_data['custom_domain'] : '';
        $custom_domain = str_replace('http://', '', $custom_domain);
        $custom_domain = str_replace('https://', '', $custom_domain);

        $www_check = substr($custom_domain, 0, 4);
        if ($www_check == "www.")
        {
            $custom_domain = substr($custom_domain, 4);
        }

		//Upload favicon
		$this->db->where('sites_id', $site_data['siteID']);
		$query = $this->db->get('sites');
		$currentFaviconName = $query->result()[0]->favicon;
		$favicon_file = '';
		$user_id = $query->result()[0]->users_id;

		if ( isset($_FILES['favicon_file']) && $_FILES['favicon_file']['name'] != '')
		{
			//if there user changes favicon - delete old file
			if($currentFaviconName != '')
			{
				$favicon_fp = './' . $this->config->item('images_uploadDir') . '/' . $user_id . '/favicon/' . $currentFaviconName;
				if ( file_exists($favicon_fp) )
				{
					unlink($favicon_fp);
				}
			}
			//if the upload path does not exist, create it
			if ( !file_exists('./' . $this->config->item('images_uploadDir') . '/' . $user_id .'/favicon'))
			{
				mkdir('./' . $this->config->item('images_uploadDir') . '/' . $user_id .'/favicon', 0777, TRUE);
			}
			$this->load->helper(array('form', 'url'));

			$config['upload_path'] = './' . $this->config->item('images_uploadDir') . '/' . $user_id . '/favicon/';
			$config['allowed_types'] = 'png|ico|jpg';
			$config['max_size']	= '64';
			$config['max_width']  = '96';
			$config['max_height']  ='96';

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('favicon_file'))
			{
				$return['favicon_upload_error'] =  $this->upload->display_errors();
			}
			else
			{
				$favicon_file = $this->upload->data('file_name');
			}

    	}
		else
		{
			//if user didn't change the favicon
			if( isset($site_data['currentFaviconFile']) && $site_data['currentFaviconFile'] != '' )
			{
				$favicon_file = $currentFaviconName;

			}
			else
			{
				//if user wants to remove current favicon
				if( $currentFaviconName != '' )
				{
					$favicon_fp = './' . $this->config->item('images_uploadDir') . '/' . $user_id . '/favicon/' . $currentFaviconName;
					/*$file = fopen($this->config->item('images_uploadDir') . '/' . $user_id .'/favicon/text.txt', "w");
					fwrite($file, $favicon_fp);
					fclose($file);*/
					if ( file_exists($favicon_fp) )
					{
						unlink($favicon_fp);
					}
				}
			}
		}
		//end Upload favicon

        // Prep data for the sub folder and sub domain values
        $subFolder = isset($site_data['sub_folder']) ? clean($site_data['sub_folder']) : '';

        $subDomain = isset($site_data['sub_domain']) ? clean($site_data['sub_domain']) : '';

        $data = array(
            'sites_name'    => $site_data['sites_name'],
            'custom_domain' => $custom_domain,
            'sub_domain'    => $subDomain,
            'sub_folder'    => $subFolder,
            'home_page'     => isset($site_data['home_page']) ? $site_data['home_page'] : '',
            'ftp_type'      => isset($site_data['ftp_type']) ? $site_data['ftp_type'] : '',
            'ftp_server'    => isset($site_data['ftp_server']) ? $site_data['ftp_server'] : '',
            'ftp_user'      => isset($site_data['ftp_user']) ? $site_data['ftp_user'] : '',
            'ftp_password'  => isset($site_data['ftp_password']) ? $site_data['ftp_password'] : '',
            'ftp_path'      => isset($site_data['ftp_path']) ? $site_data['ftp_path'] : '',
            'ftp_port'      => isset($site_data['ftp_port']) ? $site_data['ftp_port'] : '',
            'ftp_ok'        => isset($ftp_ok) ? $ftp_ok : '',
            'global_css'    => $site_data['global_css'],
            'global_js'     => $site_data['global_js'],
            'remote_url'    => isset($site_data['remote_url']) ? $site_data['remote_url'] : '',
			'favicon'  		=> $favicon_file
        );

        $this->db->where('sites_id', $site_data['siteID']);
        if ($this->session->userdata('user_type') != "Admin") $this->db->where('users_id', $this->session->userdata('user_id'));
        $this->db->update('sites', $data);

        if ($this->db->affected_rows() >= 0)
        {
            $return['ftp_ok'] = $ftp_ok;
            $return['return'] = TRUE;
            return $return;
        }
        else
        {
            $return['ftp_ok'] = $ftp_ok;
            $return['return'] = FALSE;
            return $return;
        }
    }

    /**
     * Returns a single site, without pages/frames
     *
     * @param   integer     $site_id
     * @return  mixed       $res[0]/FALSE
     */
    public function siteData($site_id)
    {
        $query = $this->db->from('sites')->where('sites_id', $site_id)->get();
        if ($query->num_rows() > 0)
        {
            $res = $query->result();
            return $res[0];
        }
        else
        {
            return FALSE;
        }
    }


    /**
     * Takes a site ID and returns all the site data, or FALSE is the site doesn't exist
     *
     * @param   integer     $site_id
     * @return  mixed       $siteArray/FALSE
     */
    public function getSite($site_id)
    {

        $this->db->from('sites');
        $this->db->where('sites_id', $site_id);
        if ($this->session->userdata('user_type') != "Admin") $this->db->where('users_id', $this->session->userdata('user_id'));

        $query = $this->db->get();

    	if ($query->num_rows() == 0)
        {
            return FALSE;
        }

        $res = $query->result();

        $site = $res[0];

        $siteArray = array();
        $siteArray['site'] = $site;

        /** Get the pages + frames */
        $query = $this->db->from('pages')->where('sites_id', $site->sites_id)->get();
        $res = $query->result();
        $pageFrames = array();
        foreach ($res as $page)
        {
            /** Get the frames for each page */
            $query = $this->db->from('frames')->where('pages_id', $page->pages_id)->where('revision', 0)->where('frames_popup', '')->order_by('frames_id')->get();

            $pageDetails = array();
            $pageDetails['blocks'] = $query->result();
            $pageDetails['page_id'] = $page->pages_id;
            $pageDetails['pages_title'] = $page->pages_title;
            $pageDetails['meta_description'] = $page->pages_meta_description;
            $pageDetails['meta_keywords'] = $page->pages_meta_keywords;
            $pageDetails['header_includes'] = $page->pages_header_includes;
            $pageDetails['page_css'] = $page->pages_css;
            $pageDetails['google_fonts'] = json_decode($page->google_fonts);

            /** Get the popups for this page **/
            $query = $this->db->from('frames')
                ->where('pages_id', $page->pages_id)
                ->where('revision', 0)
                ->where('frames_popup !=', '')
                ->order_by('frames_id')->get();

            $pageDetails['popups'] = $query->result();

            $pageFrames[$page->pages_name] = $pageDetails;
        }

        $siteArray['pages'] = $pageFrames;

        /** Grab the assets folders as well */
        $this->load->helper('directory');

        $folderContent = directory_map($this->config->item('elements_dir'), 2);
        $assetFolders = array();

        if (is_array($folderContent))
        {
            foreach ($folderContent as $key => $item)
            {
                if (is_array($item))
                {
                    array_push($assetFolders, $key);
                }
            }
        }

        $siteArray['assetFolders'] = $assetFolders;

		if($site->favicon != '')
		{
			$siteArray['favicon_imagePath'] = '/' . $this->config->item('images_uploadDir') . '/' . $site->users_id . '/favicon/' . $site->favicon;
		}
		else
		{
			$siteArray['favicon_imagePath'] = '';
		}			

        /** Site package options for user */
        if ($this->session->userdata('user_type') != "Admin")
        {
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            $siteArray['hosting_option'] = json_decode($package['hosting_option']);
            $siteArray['ftp_publish'] = $package['ftp_publish'];
        }

        return $siteArray;
    }

    /**
     * Grabs a single frame and returns it
     *
     * @param   integer     $frame_id
     * @return  array       $res[0]
     */
    public function getSingleFrame($frame_id)
    {
        $query = $this->db->from('frames')->where('frames_id', $frame_id)->get();
        $res = $query->result();
        return $res[0];
    }

    /**
     * Gets the assets and pages of a site
     *
     * @param   integer     $site_id
     * @return  array       $return
     */
    public function getAssetsAndPages($site_id)
    {
        /** Get the asset folders first, we only grab the first level folders inside $this->config->item('elements_dir') */
        $this->load->helper('directory');

        $folderContent = directory_map($this->config->item('elements_dir'), 2);
        $assetFolders = array();

        foreach ($folderContent as $key => $item)
        {
            if (is_array($item))
            {
                array_push($assetFolders, $key);
            }
        }

        /** Now we get the pages */
        $query = $this->db->from('pages')->where('sites_id', $site_id)->get();
        $pages = $query->result();

        $return = array();
        $return['assetFolders'] = $assetFolders;
        $return['pages'] = $pages;

        return $return;
    }

    /**
     * Moves a site to the trash
     *
     * @param   integer     $site_id
     * @param   integer     $user_id
     */
    public function trash($site_id, $user_id = 0)
    {
    	$data = array(
            'sites_trashed' => 1
        );

    	$this->db->where('sites_id', $site_id);
        if ( $user_id != 0 ) $this->db->where('users_id', $user_id);
    	$this->db->update('sites', $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Returns all admin images
     *
     * @return  mixed       $adminImages/FALSE
     */
    public function adminImages()
    {
        $folderContent = directory_map($this->config->item('images_dir'), 2);

        if ($folderContent)
        {
            $adminImages = array();
            foreach ($folderContent as $key => $item)
            {
                if ( ! is_array($item))
                {
                    /** check the file extension */
                    $ext = pathinfo($item, PATHINFO_EXTENSION);
                    /** prep allowed extensions array */
                    $temp = explode("|", $this->config->item('images_allowedExtensions'));

                    if (in_array($ext, $temp))
                    {
                        array_push($adminImages, $item);
                    }
                }
            }

            return $adminImages;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Trashes a users' sites
     *
     * @param   integer     $user_id
     */
    public function deleteAllFor($user_id)
    {
        $data = array(
            'sites_trashed' => 1
        );

        $this->db->where('users_id', $user_id);
        $this->db->update('sites', $data);
    }

    /**
     * Grabs a singlepage for preview
     *
     * @param   integer     $site_id
     * @param   string      $page_name
     * @return  mixed       $q/FALSE
     */
    public function getPage($site_id, $page_name)
    {
        $q = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $page_name)->order_by('pages_timestamp', 'asc')->get()->row();

        if ($q->pages_preview != '')
        {
            return $q;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Marks site as published
     *
     * @param   integer     $site_id
     */
    public function published($site_id)
    {
        $data = array(
            'ftp_published'     => 1,
            'publish_date'      => time()
        );

        $this->db->where('sites_id', $site_id);
        $this->db->update('sites', $data);
    }

    /**
     * Changes the owner of a site
     *
     * @param   integer     $siteID
     * @param   integer     $newOwnerID
     * @return  boolean
     */
    public function changeOwner($siteID, $newOwnerID)
    {

        // make sure the owner ID is kosher
        $this->db->from('users');
        $this->db->where('id', $newOwnerID);

        $q = $this->db->get();

        if ( $q->num_rows() > 0 )
        {

            $data = array(
                'users_id' => $newOwnerID
            );

            $this->db->where('sites_id', $siteID);
            $this->db->update('sites', $data);

            return true;

        }
        else
        {
            return false;
        }

    }

}
