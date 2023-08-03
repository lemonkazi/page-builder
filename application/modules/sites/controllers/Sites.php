<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sites extends MY_Controller {

    private $perPage = 10;

    /**
     * Class constructor
     *
     * Loads required models, check if user has right to access this class, load the hook class and add a hook point
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $model_list = [
            'user/Users_model' => 'MUsers',
            'asset/Assets_model' => 'MAssets',
            'package/Packages_model' => 'MPackages',
            'settings/Payment_settings_model' => 'MPayments',
            'sites/Blocks_fav_model' => 'MBlocksFav',
            'sites/Frames_model' => 'MFrames',
            'sites/Sites_model' => 'MSites',
            'sites/Pages_model' => 'MPages',
            'shared/Revision_model' => 'MRevisions',
            'shared/Ftp_model' => 'MFtp',
            'templates/Templates_model' => 'MTemplates',
            'settings/Whitelabel_model' => 'MWhitelabel',
            'builder_elements/Blocks_model' => 'MBlocks'
        ];
        $this->load->model($model_list);

        if ( ! $this->session->has_userdata('user_id') && $this->uri->segment(1) != 'loadsinglepage' && $this->uri->segment(1) != 'loadsingleframe')
        {
            redirect('auth', 'refresh');
        }

        $this->hooks = load_class('Hooks', 'core');
        $this->data = [];
        $this->data['whitelabel_general'] = $this->MWhitelabel->load();

        /** Hook point */
        $this->hooks->call_hook('sites_construct');

        //$this->output->enable_profiler(TRUE);
    }

    /**
     * Print_r convenience function, which prints out <PRE> tags around
     * the output of given array. Similar to debug().
     */
    public function dd($var)
    {
        echo '<pre>';
        print_r($var);
        echo '<pre>';
    }

    /**
     * Loads site's dashboard
     *
     * @return  void
     */
    public function index()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_index_pre');

        $this->data['title'] = $this->lang->line('sites_index_title');
        $this->data['content'] = 'sites';
        $this->data['page'] = 'site';
        /** Grab us some sites */
        $this->data['sites'] = $this->MSites->get_all();
        /** Get all users */
        $this->data['users'] = $this->MUsers->get_all();
        $gateway = $this->MPayments->get_by_name('payment_gateway');
        $this->data['packages'] = $this->MPackages->get_all($gateway[0]->value, true);
        $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
        $sites = $this->MSites->site_by_user($this->session->userdata('user_id'));
        if (count($package) > 0)
        {
            $user_sites = count($sites);
            $package_sites = (int)$package['sites_number'];
            if ($user_sites > 0)
            {
                /** User's site is more or equal to its package number */
                if ($user_sites >= $package_sites)
                {
                    $this->data['site_limitation'] = $this->lang->line('sites_index_reach_site_number');
                }
                else if ($user_sites + 2 >= $package_sites)
                {
                    $this->data['site_limitation'] = $this->lang->line('sites_index_almost_reach_site_number');
                }
            }
        }

        if ($this->session->userdata('user_type') == 'Admin')
        {
            // Get the template categories
            $templateCategories = $this->MTemplates->getCategories();
            $sendToBrowser = [];
            $newTemplates = [];

            $noCat = $this->MTemplates->allWithNoCategory();

            if ($noCat)
            {
                $temp = [];
                $temp['category_name'] = $this->lang->line('sites_index_no_template_cat');
                $temp['templates_categories_id'] = 0;

                $sendToBrowser[] = $temp;

                foreach ($noCat as $template)
                {
                    $newTemplates[] = $template;
                }
            }

            foreach ($templateCategories as $templateCategory)
            {
                $templates = $this->MTemplates->all($templateCategory['templates_categories_id']);

                if ($templates)
                {
                    $sendToBrowser[] = $templateCategory;

                    foreach ($templates as $template)
                    {
                        $newTemplates[] = $template;
                    }
                }
            }

            $this->data['templateCategories'] = $sendToBrowser;
            $this->data['templates'] = $newTemplates;
        }
        else
        {
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            if (json_decode($package['templates']) != NULL)
            {
                // Get the template categories
                $templateCategories = $this->MTemplates->getCategories();
                $sendToBrowser = [];
                $newTemplates = [];

                $noCat = $this->MTemplates->allWithNoCategory(json_decode($package['templates']));

                if ($noCat)
                {
                    $temp = [];
                    $temp['category_name'] = $this->lang->line('sites_index_no_template_cat');
                    $temp['templates_categories_id'] = 0;

                    $sendToBrowser[] = $temp;

                    foreach ($noCat as $template)
                    {
                        $newTemplates[] = $template;
                    }
                }

                foreach ($templateCategories as $templateCategory)
                {
                    $templates = $this->MTemplates->all($templateCategory['templates_categories_id'], json_decode($package['templates']));

                    if ($templates)
                    {
                        $sendToBrowser[] = $templateCategory;

                        foreach ($templates as $template)
                        {
                            $newTemplates[] = $template;
                        }
                    }
                }

                $this->data['templateCategories'] = $sendToBrowser;
                $this->data['templates'] = $newTemplates;
            }
        }

        /** Hook point */
        $this->hooks->call_hook('sites_index_post');

        $this->load->view('shared/layout', $this->data);
    }

    /**
     * Loads page builder
     *
     * @return  void
     */
    public function create($templateID = false)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_create_pre');

        /** Check if it is admin or normal user */
        if ($this->session->userdata('user_type') == "Admin")
        {
            /** Create a new, empty site */
            $site_id = $this->MSites->createNew($templateID);
        }
        else
        {
            /** Check if user package support create new site */
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            $sites = $this->MSites->site_by_user($this->session->userdata('user_id'));

            /** User has some sites */
            if (count($sites) > 0)
            {
                /** User's site is more or equal its package number */
                if (count($sites) >= $package['sites_number'])
                {
                    $this->session->set_flashdata('error', $this->lang->line('sites_create_site_exceed'));
                    redirect('sites', 'refresh');
                }
                else
                {
                    /** Create a new, empty site */
                    $site_id = $this->MSites->createNew($templateID);
                }
            }
            else
            {
                /** Create a new, empty site */
                $site_id = $this->MSites->createNew($templateID);
            }
        }

        /** Hook point */
        $this->hooks->call_hook('sites_create_post');

        redirect('sites/' . $site_id, 'refresh');
    }

    /**
     * Used to create new sites AND save existing ones
     *
     * @param   integer     $forPublish
     * @return  json        $return
     */
    public function save($forPublish = 0)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_save_pre');

        $this->load->helper('response');

        /** Do we have the required data? */
        if ( ! isset($_POST['siteData']))
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_save_no_data_error_heading');
            $temp['content'] = $this->lang->line('sites_save_no_data_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            sendResponse(json_encode($this->return));
            die();
        }

        /** Do we have some frames to save? */
        if (( ! isset($_POST['pages']) || $_POST['pages'] == '') && ( ! isset($_POST['toDelete'])))
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_save_no_frame_error_heading');
            $temp['content'] = $this->lang->line('sites_save_no_frame_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            sendResponse(json_encode($this->return));
            die();
        }


        $this->return = array();

        /** Should we save an existing site or create a new one? */
        if ( ! $this->MSites->update($_POST['siteData'], $_POST['pages']))
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_save_error');
            $temp['content'] = $this->lang->line('sites_save_error_messge');
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
            sendResponse(json_encode($this->return));
            die();
        }

        /** Delete any pages? */
        if (isset($_POST['toDelete']) && is_array($_POST['toDelete']) && count($_POST['toDelete']) > 0)
        {
            foreach ($_POST['toDelete'] as $page)
            {
                $this->MPages->delete($_POST['siteData']['sites_id'], $page);
            }
        }

        /** Regular site save */
        if ($forPublish == 0)
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_save_after_publish_success_heading');
            $temp['content'] = $this->lang->line('sites_save_after_publish_success_message');
        }
        /** Saving before publishing, requires different message */
        else if ($forPublish == 1)
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_save_before_publish_success_heading');
            $temp['content'] = $this->lang->line('sites_save_before_publish_success_message');
        }

        $this->return['responseCode'] = 1;
        $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);


        sendResponse(json_encode($this->return));

        /** All good, make thumbnail of the main page */
    	$indexPage = $this->MPages->getSinglePage($_POST['siteData']['sites_id'], 'index');
		if($indexPage)
		{
			$screenshotUrl = base_url() . 'loadsinglepage/' . $indexPage->pages_id;
			$filename = 'sitethumb_' . $_POST['siteData']['sites_id'] . '.jpg';
			$this->load->library('screenshot_library');
			$screenshot = $this->screenshot_library->make_screenshot($screenshotUrl, $filename, '1200x900', $this->config->item('screenshot_sitethumbs_folder'), 'page', 'true');
			if ($screenshot)
			{
				$data = array(
					'sitethumb' => $this->config->item('screenshot_sitethumbs_folder') . $screenshot . "?" . time()
				);
				$this->MSites->update_fields($_POST['siteData']['sites_id'],$data);
			}
		}
		/** Hook point */
        $this->hooks->call_hook('sites_save_post');
		die();
    }

    /**
     * Loads some configuration data with ajax call
     *
     * @return  json        $return
     */
    public function siteData()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_siteData_pre');

        $this->return = array();

        if ($this->session->userdata('templateID'))
        {
            $site['sites_id'] = 0;
            $site['sites_name'] = $this->lang->line('sites_site_template_sitename');
            $site['viewmode'] = "desktop";

            $this->return['site'] = $site;

            $query = $this->db->from('pages')->where('pages_id', $this->session->userdata('templateID'))->get();
            $res = $query->result();
            $pageFrames = array();
            foreach ($res as $page)
            {
                /** Get the frames for each page */
                $query = $this->db->from('frames')
                ->where('pages_id', $page->pages_id)
                ->where('revision', 0)
                ->where('frames_popup', '')
                ->order_by('frames_id')->get();

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

            $this->return['pages'] = $pageFrames;

            $this->return['templateID'] = $this->session->userdata('templateID');
        }
        else
        {
            $this->return = $this->MSites->getSite($this->session->userdata('siteID'));
        }

        /** Delete unneeded stuff */
        if (isset($this->return['assetFolders'])) unset($this->return['assetFolders']);

        /** Admin or no? */
        if ($this->session->userdata('user_type') == "Admin")
        {
            $this->return['is_admin'] = 1;
        }
        else
        {
            $this->return['is_admin'] = 0;
        }

        if ($this->config->item('google_api') !== '')
        {
            $this->return['google_api'] = $this->config->item('google_api');
        }

        $this->return['fonts'] = json_decode($this->config->item('custom_fonts'));

        // Some translation stuff
        $language = Array();
        $language['front_end_spectrum_cancel'] = $this->lang->line('front_end_spectrum_cancel');
        $language['front_end_spectrum_choose'] = $this->lang->line('front_end_spectrum_choose');
        $language['styles'] = $this->lang->line('styles');

        $this->return['language'] = $language;

        // Some settings
        $settings = Array();
        $settings['popup_wrapping_html'] = $this->config->item('popup_wrapping_html');

        $this->return['settings'] = $settings;

        /** Hook point */
        $this->hooks->call_hook('sites_siteData_post');

        echo json_encode($this->return);
    }

    /**
     * Get and retrieve single site data
     *
     * @param   integer     $siteID
     * @return  void
     */
    public function site($siteID)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_site_pre');

        $this->session->unset_userdata('templateID');

        $this->load->helper('thumb');

        /** Store the session ID with this session */
        $this->session->set_userdata('siteID', $siteID);

        $configured_apis = array_map(function($api) {

            $label = explode("_", $api["name"]);
            $label = join(" ", array_map('ucfirst', $label));

            $api["label"] = $label;

            return $api;

        }, $this->MUsers->get_api_list());

        $this->data['configured_apis'] = $configured_apis;

        /** If user is not an admin, we'll need to check of this site belongs to this user */
        if ($this->session->userdata('user_type') != "Admin")
        {
            if ( ! $this->MSites->isMine($siteID))
            {
                redirect('/sites');
            }

            // $hosting = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            // $this->data['hosting_option'] = json_decode($hosting['hosting_option']);
            // print_r($this->data['hosting_option']);
            // die();
        }

        $siteData = $this->MSites->getSite($siteID);

        if ($siteData == FALSE)
        {
            /** Site could not be loaded, redirect to /sites, with error message */
            $this->session->set_flashdata('error', $this->lang->line('sites_site_could_not_load_error'));
            redirect('/sites', 'refresh');
        }
        else
        {
            $this->data['siteData'] = $siteData;

            /** Get page data */
            $pagesData = $this->MPages->getPageData($siteID);
            if ($pagesData)
            {
                $this->data['pagesData'] = $pagesData;
            }

            /** Collect data for the image library */
            $userID = $this->session->userdata('user_id');;
            //$userImages = $this->MUsers->getUserImages($userID);
            $userImages = $this->MAssets->get_images($userID);
            if ($userImages)
            {
                $this->data['userImages'] = $userImages;
            }
            else
            {
                $this->data['userImages'] = [];
            }

            $adminImages = $this->MSites->adminImages();
            if ($adminImages)
            {
                $this->data['adminImages'] = $adminImages;
            }
            else
            {
                $this->data['adminImages'] = [];
            }

            /** Pre-build templates */
            if ($this->session->userdata('user_type') == 'Admin')
            {
                // Get the template categories
                $templateCategories = $this->MTemplates->getCategories();

                $newTemplates = [];

                $allTemplates = $this->MTemplates->getForCategory(0);

                if ($allTemplates)
                {
                    $newTemplates[$this->lang->line('sites_site_label_alltemplates')] = $this->load->view('shared/templateframes', array('pages'=>$allTemplates), TRUE);
                }

                foreach ($templateCategories as $templateCategory)
                {
                    $templates = $this->MTemplates->getForCategory($templateCategory['templates_categories_id']);

                    if ($templates)
                    {
                        $newTemplates[$templateCategory['category_name']] = $this->load->view('shared/templateframes', array('pages'=>$templates), TRUE);
                    }
                }

                if (count($newTemplates) !== 0)
                {
                    $this->data['templates'] = $newTemplates;
                }
            }
            else
            {
                $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
                if (json_decode($package['templates']) != NULL)
                {
                    // Get the template categories
                    $templateCategories = $this->MTemplates->getCategories();

                    $newTemplates = [];

                    $allTemplates = $this->MTemplates->getForCategory(0, json_decode($package['templates'], TRUE));

                    if ($allTemplates)
                    {
                        $newTemplates[$this->lang->line('sites_site_label_alltemplates')] = $this->load->view('shared/templateframes', array('pages'=>$allTemplates), TRUE);
                    }

                    foreach ($templateCategories as $templateCategory)
                    {
                        $templates = $this->MTemplates->getForCategory($templateCategory['templates_categories_id'], json_decode($package['templates'], TRUE));

                        if ($templates)
                        {
                            $newTemplates[$templateCategory['category_name']] = $this->load->view('shared/templateframes', array('pages'=>$templates), TRUE);
                        }
                    }

                    if (count($newTemplates) !== 0)
                    {
                        $this->data['templates'] = $newTemplates;
                    }
                }
            }

            /** Grab all revisions */
            $this->data['revisions'] = $this->MRevisions->getForSite($siteID, 'index');
            /** Grab pacakge details */
            $this->data['package'] = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            //print_r($this->data['package']); die();

            $this->data['builder'] = TRUE;
            $this->data['page'] = "site_builder";
            $this->data['content'] = "create";

            if ($this->session->userdata('user_type') == "Admin")
            {
                $this->data['blockCategories'] = $this->MBlocks->allBlockCategories();
            }

            $this->data['whitelabel_general'] = $this->MWhitelabel->load();

            /** Hook point */
            $this->hooks->call_hook('sites_site_post');

            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * Get and retrieve single site data with ajax
     *
     * @param   string      $siteID
     * @return  json        $return
     */
    public function siteAjax($siteID = '')
    {
        /** Hook point */
        $this->hooks->call_hook('sites_siteAjax_pre');

        /** If siteID is missing */
        if ($siteID == '' || $siteID == 'undefined')
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_siteAjax_siteID_missing_error_heading');
            $temp['content'] = $this->lang->line('sites_siteAjax_siteID_missing_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            die(json_encode($this->return));
        }

        $siteData = $this->MSites->getSite($siteID);
        /** Remove unnecessary data */
        unset($siteData['pages']);
        unset($siteData['assetFolders']);
        /** Check if ssh2 module is loaded */
        if (extension_loaded('ssh2'))
        {
            $siteData['ssh2'] = TRUE;
        }

        /** All did not go well */
        if ($siteData == FALSE)
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_siteAjax_site_save_error_heading');
            $temp['content'] = $this->lang->line('sites_siteAjax_site_save_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            /** Hook point */
            $this->hooks->call_hook('sites_siteAjax_error');

            echo json_encode($this->return);
        }
        /** All went well */
        else
        {
            $this->return = array();
            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->load->view('shared/sitedata', array('data' => $siteData), TRUE);

            /** Hook point */
            $this->hooks->call_hook('sites_siteAjax_success');

            echo json_encode($this->return);
        }
    }

    /**
     * Updates site details, submitting through ajax
     *
     * @return  json        $return
     */
    public function siteAjaxUpdate()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_siteAjaxUpdate_pre');

        $this->form_validation->set_rules('siteID', 'Site ID', 'required');
        $this->form_validation->set_rules('sites_name', 'Site name', 'required');
        /** All did not go well */
        if ($this->form_validation->run() == FALSE)
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_validation_error_heading');
            $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_validation_error_message') . validation_errors();

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            echo json_encode($this->return);
        }
        /** All good with the data, let's update */
        else
        {
            /** check if sub folder already exist */
            if (trim($this->input->post('sub_folder')) != "")
            {
                $sub_folder = $this->MSites->get_by_field_value('sub_folder', trim($this->input->post('sub_folder')));
                if (count($sub_folder) > 0)
                {
                    $arr = array_filter($sub_folder, function($ar)
                    {
                        return ($ar['sites_id'] != $this->input->post('siteID'));
                    });
                    if (count($arr) > 0)
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_sub_folder_error_heading');
                        $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_sub_folder_error_message') . validation_errors();

                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                        echo json_encode($this->return);
                        die();
                    }
                }
            }

            /** check if sub domain already exist */
            if (trim($this->input->post('sub_domain')) != "")
            {
                $sub_domain = $this->MSites->get_by_field_value('sub_domain', trim($this->input->post('sub_domain')));
                if (count($sub_domain) > 0)
                {
                    $arr = array_filter($sub_domain, function($ar)
                    {
                        return ($ar['sites_id'] != $this->input->post('siteID'));
                    });
                    if (count($arr) > 0)
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_error_heading');
                        $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_error_message') . validation_errors();

                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                        echo json_encode($this->return);
                        die();
                    }
                }
            }

            /** check if custom domain already exist */
            if (trim($this->input->post('custom_domain')) != "")
            {
                $custom_domain = $this->MSites->get_by_field_value('custom_domain', trim($this->input->post('custom_domain')));
                if (count($custom_domain) > 0)
                {
                    $arr = array_filter($custom_domain, function($ar)
                    {
                        return ($ar['sites_id'] != $this->input->post('siteID'));
                    });
                    if (count($arr) > 0)
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_custom_domain_error_heading');
                        $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_custom_domain_error_message') . validation_errors();

                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                        echo json_encode($this->return);
                        die();
                    }
                }
            }

            /** Cloudflare integration for sub domains */
            $site = $this->MSites->get_by_id($this->input->post('siteID'));
            $this->load->helper('cloudflare');
            $this->load->helper('general');

            $cloudflare_config = $this->config->item('cloudflare');

            if ( $cloudflare_config['x_auth_email'] != '' && $cloudflare_config['x_auth_key'] != '' ) {

                // Create DNS record at Cloudflare
                if (trim($this->input->post('sub_domain')) != "" && $site['sub_domain'] !== $this->input->post('sub_domain') )
                {
                    // Load all zones for this account
                    $res = json_decode(callAPI('GET', 'zones', $cloudflare_config['x_auth_email'], $cloudflare_config['x_auth_key']));

                    // Make sure we can connect to the API
                    if( isset($res->success) && !$res->success )
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_cloudflare_error_heading');
                        $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_cloudflare_error_content') . $res->errors[0]->message . "(code: " . $res->errors[0]->code . ")";

                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                        echo json_encode($this->return);
                        die();
                    }

                    $domain_info = get_domaininfo($this->config->item('base_url'));

                    // We're all good, let's fine the zone for the used domain
                    foreach( $res->result as $zone )
                    {
                        if ( $zone->name === $domain_info['domain'] )
                        {
                            // This is the correct zone, create a new DNS record for this ZONE

                            $recordArray = array();
                            $recordArray['type'] = 'A';
                            $recordArray['name'] = trim($this->input->post('sub_domain'));
                            $recordArray['content'] = $_SERVER['SERVER_ADDR'];
                            $recordArray['proxied'] = true;

                            $res = json_decode(callAPI('POST', 'zones/' . $zone->id . '/dns_records', $cloudflare_config['x_auth_email'], $cloudflare_config['x_auth_key'], json_encode($recordArray)));

                            if( isset($res->success) && !$res->success )
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_cloudflare_error_heading');
                                $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_cloudflare_error_content') . $res->errors[0]->message . " (code: " . $res->errors[0]->code . ")";

                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                                echo json_encode($this->return);
                                die();
                            }

                            // Update site record
                            $data = Array();
                            $data['cloudflare_dns'] = 1;

                            $this->MSites->update_fields($this->input->post('siteID'), $data);

                        }

                    }

                }

                // Delete DNs record at Cloudflare
                if ( (trim($this->input->post('sub_domain')) == "" && $site['cloudflare_dns'] == 1) || (trim($this->input->post('sub_domain')) != $site['sub_domain'] && $site['cloudflare_dns'] == 1 ) )
                {

                    // Load all zones for this account
                    $res = json_decode(callAPI('GET', 'zones', $cloudflare_config['x_auth_email'], $cloudflare_config['x_auth_key']));

                    // Make sure we can connect to the API
                    if( isset($res->success) && !$res->success )
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_cloudflare_error_heading');
                        $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_sub_domain_cloudflare_error_content') . $res->errors[0]->message . "(code: " . $res->errors[0]->code . ")";

                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                        echo json_encode($this->return);
                        die();
                    }

                    $domain_info = get_domaininfo($this->config->item('base_url'));

                    // We're all good, let's fine the zone for the used domain
                    foreach( $res->result as $zone )
                    {
                        if ( $zone->name === $domain_info['domain'] )
                        {
                            // Found the zone, now list all the DNS records
                            $res = json_decode(callAPI('GET', 'zones/' . $zone->id . "/dns_records", $cloudflare_config['x_auth_email'], $cloudflare_config['x_auth_key']));

                            // find the record to delete
                            foreach ( $res->result as $record )
                            {
                                if ( $record->name === $site['sub_domain'] . '.' .$domain_info['domain'] )
                                {
                                    callAPI("DEL", 'zones/' . $zone->id . "/dns_records/" . $record->id, $cloudflare_config['x_auth_email'], $cloudflare_config['x_auth_key']);
                                }
                            }

                        }
                    }

                    // Update site record
                    $data = Array();
                    $data['cloudflare_dns'] = 0;

                    $this->MSites->update_fields($this->input->post('siteID'), $data);

                }

            }

            /** If this site is home page then remove all home page flag first */
            if ($this->input->post('home_page') == 1)
            {
                $this->MSites->remove_home_page();
            }

            $update = $this->MSites->updateSiteData($this->input->post());
            if ($update['return'])
            {
                $temp = array();
                $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_save_success_heading');
                if ($update['ftp_ok'] == 1)
                {
                    $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_save_ftp_success_message');
                }
                else
                {
                    $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_save_ftp_error_message');
                }

                $this->return = array();
                $this->return['responseCode'] = 1;
                $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);


    			if(isset($update['favicon_upload_error']))
				{
					$temp['header']  = $this->lang->line('settings_update_favicon_error1_message');
					$temp['content'] = $update['favicon_upload_error'];
					$this->return['responseHTML'] .= $this->load->view('shared/error', array('data'=>$temp), TRUE);
				}

                if ($update['ftp_ok'] == 1)
                {
                    $this->return['ftpOK'] = 1;
                }
                else
                {
                    $this->return['ftpOK'] = 0;
                }

                /** We'll send back the updated site data as well */
                $siteData = $this->MSites->getSite($this->input->post('siteID'));
                $this->return['responseHTML2'] = $this->load->view('shared/sitedata', array('data'=>$siteData), TRUE);
                $this->return['siteName'] = $siteData['site']->sites_name;
                $this->return['siteID'] = $siteData['site']->sites_id;
                $this->return['siteSubFolder'] = $siteData['site']->sub_folder;

                /** Hook point */
                $this->hooks->call_hook('sites_siteAjaxUpdate_success');

                echo json_encode($this->return);
            }
            else
            {
                $temp = array();
                $temp['header'] = $this->lang->line('sites_siteAjaxUpdate_save_error_heading');
                $temp['content'] = $this->lang->line('sites_siteAjaxUpdate_save_error_message');

                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                /** Hook point */
                $this->hooks->call_hook('sites_siteAjaxUpdate_error');

                echo json_encode($this->return);
            }
        }
    }

    /**
     * Gets the content of a saved frame and sends it back to the browser
     *
     * @param   integer     $frameID
     * @return  void
     */
    public function getframe($frameID)
    {
        $frame = $this->MSites->getSingleFrame($frameID);
        echo $frame->frames_content;
    }

    /**
     * Publishes a site via FTP/sFTP with new export method
     *
     * @return  void
     */
    public function publish($site_id)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_publish_pre');

        $this->load->helper('base64');

        /** Prevent timeout */
        set_time_limit(0);

        $cssUrls = [];
        $jsUrls = [];
        $pageImages = [];
        $fonts = [];

        $theSite = $this->MSites->siteData($site_id);

        foreach ($_POST['pages'] as $page)
        {
            /** Get page meta */
            $pageMeta = $this->MPages->getSinglePage($site_id, $page);

            /** Get page content */
            $pageContent = $this->MPages->load_page($pageMeta->pages_id);

            if (trim($pageContent) == '')
            {
                $pageContent = '<html><head></head><body></body></html>';
            }

            /** Fix up bits in the <head> */
            $pageContent = str_replace('<html><head>', "<html>\n<head>", $pageContent);
            $pageContent = str_replace('</head>', "\n</head>", $pageContent);
            $pageContent = str_replace('<style', "\n\t<style", $pageContent);
            $pageContent = str_replace('</style><link', "</style>\n\t<link", $pageContent);


            if ($pageMeta)
            {
                /** Insert title, meta keywords and meta description */
				$meta = '';
				if($theSite->favicon != '')
				{
					$favicon = '<link rel="shortcut icon" href="'. $this->config->item('images_uploadDir'). '/' . $theSite->users_id . '/'. 'favicon/'. $theSite->favicon . '" />' . "\r\n";
					$meta .= $favicon;
				}
                $meta .= "\t".'<title>' . $pageMeta->pages_title . '</title>' . "\r\n";
                $meta .= "\t".'<meta name="description" content="' . $pageMeta->pages_meta_description . '">' . "\r\n";
                $meta .= "\t".'<meta name="keywords" content="' . $pageMeta->pages_meta_keywords . '">';

                $pageContent = str_replace('<!--pageMeta-->', $meta, $pageContent);
				/** Insert site favicon */


                /** Insert header includes; */
                $includesPlusCss = '';
                if ($pageMeta->pages_header_includes != '')
                {
                    $includesPlusCss .= $pageMeta->pages_header_includes;
                }
                if ($pageMeta->pages_css != '')
                {
                    $includesPlusCss .= "\n\t<style>" . $pageMeta->pages_css . "</style>\n";
                }
                if ($theSite->global_css != '')
                {
                    $includesPlusCss .= "\n\t<style>" . $theSite->global_css . "</style>\n";
                }

                /** Google fonts */
                if ($pageMeta->google_fonts !== '' && $pageMeta->google_fonts !== '[]')
                {
                    $googleFonts = json_decode($pageMeta->google_fonts);
                    $apiString = $this->config->item('google_font_api');

                    if (is_array($googleFonts))
                    {
                        foreach ($googleFonts as $font)
                        {
                            $apiString .= $font->api_entry;

                            $apiString .= '|';
                        }
                    }

                    $apiString = '<link href="' . $apiString . '" rel="stylesheet" type="text/css">';

                    $pageContent = str_replace("</head>", $apiString . "\n</head>", $pageContent);
                }

                /** Deal with global JS */
                if ($theSite->global_js != '')
                {
                    $custom_js = trim(strip_tags($theSite->global_js, '<script>'));
                    $pos = strpos($custom_js, '<script');
                    if ($pos !== FALSE && $pos == 0)
                    {
                        $pageContent = str_replace("</head>", "\n" . $custom_js . "\n</head>", $pageContent);
                    }
                    else
                    {
                        $pageContent = str_replace("</head>", "\n<script>\n" . $custom_js . "\n</script>\n</head>", $pageContent);
                    }
                }

                /** Insert header includes */
                $pageContent = str_replace('<!--headerIncludes-->', $includesPlusCss, $pageContent);
            }

            /** Remove frameCovers */
            $pageContent = str_replace('<div class="frameCover" data-type="video"></div>', "", $pageContent);

            /** This is needed for correct exports */
            $pageContent = str_replace('src="../', 'src="', $pageContent);
            $pageContent = str_replace("src='../", "src='", $pageContent);
            $pageContent = str_replace('url(../', 'url(', $pageContent);
            $pageContent = str_replace("url('../", "url('", $pageContent);
            $pageContent = str_replace('url("../', 'url("', $pageContent);
            $pageContent = str_replace('&quot;../bundles/', '&quot;bundles/', $pageContent);//FF needs this

            // if ($this->config->item('google_api') !== '')
            // {
            //     $pageContent = str_replace('</body>', '<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . $this->config->item('google_api') . '&callback=initMap"></script>' . '</body>' . "\n", $pageContent);
            // }

            /** setup of htmLawed helper */
            $this->load->helper('htmlawed');

            $config = array(
                'tidy' => '1t1n',
                'direct_nest_list' => 1,
                'keep_bad' => 2,
                'css_expression' => 1,
                'elements' => '*',
                'lc_std_val' => 0,
                'make_tag_strict' => 0,
                'no_deprecated_attr' => 0,
                'style_pass' => 1,
                'unique_ids' => 0,
                'parent' => 'body',
                'hook_tag' => 'my_tag_function',
                'balance' => 0
            );

            /** Simle html dom library */
            $this->load->library('Simple_html_dom');
            $raw = str_get_html($pageContent, true, true, DEFAULT_TARGET_CHARSET, false);

            /** body first */
            $body = $raw->find('body')[0];
            $sanitzedBody = htmLawed($body, $config);
            $raw->find('body')[0]->innertext = $sanitzedBody;

            /** extract CSS urls */
            foreach ($raw->find('link[rel="stylesheet"]') as $cssLink)
            {
                /** extract CSS link, no need for blob uhrls */
                if (substr($cssLink->href, 0, 4) != 'blob')
                {
                    $cssUrls[] = $cssLink->href;

                    /** extract images from CSS */
                    if (substr($cssLink->href, 0, 4) != 'http' && substr($cssLink->href, 0, 2) != '//')
                    {
                        $CSS = file_get_contents("./".$this->config->item('elements_dir')."/".$cssLink->href);

                        $re = '/url\(\s*[\'"]?(\S*\.(?:jpe?g|gif|png))[\'"]?\s*\)[^;}]*?/i';
                        if (preg_match_all($re, $CSS, $matches))
                        {
                            foreach ($matches[1] as $img)
                            {
                                $pageImages[] = $img;
                            }
                        }
                    }

                    /** extract fonts from CSS */
                    $re = '/url\(([\'"]?.[^\'"]*\.(eot|woff|woff2|ttf)[\'"]?)\)/i';
                    if (preg_match_all($re, $CSS, $matches))
                    {
                        foreach ($matches[1] as $font)
                        {
                            $fonts[] = $font;
                        }
                    }
                }
            }

            /** extract JS urls */
            foreach ($raw->find('script[src]') as $jsLink)
            {
                $jsUrls[] = $jsLink->src;
            }

            /** extract <image> src */
            foreach ($body->find('img') as $image)
            {
                $pageImages[] = $image->src;
            }

            /** extract CSS background images */
            $re = '/url\(\s*[\'"]?(\S*\.(?:jpe?g|gif|png))[\'"]?\s*\)[^;}]*?/i';
            if (preg_match_all($re, str_replace("&quot;", "'", $raw), $matches))
            {
                foreach ($matches[1] as $img)
                {
                    $pageImages[] = $img;
                }
            }

            /** extract parallax data-image-src files */
            foreach ($body->find('*[data-parallax]') as $element)
            {
                $pageImages[] = $element->getAttribute('data-image-src');
            }

            /** last minute clean up of <script> tags */
            $raw = str_replace("\n\t\t \n\t</script>", "</script>", $raw);
            $raw = str_replace("\t</body>", "</body>", $raw);

            $final_html = stripslashes($raw);
            if ( ! is_dir(FCPATH . 'tmp/ftp/' . $site_id))
            {
                mkdir(FCPATH . 'tmp/ftp/' . $site_id, 0777, TRUE);
            }
            $handle = fopen(FCPATH . 'tmp/ftp/' . $site_id . '/' . $page . '.html', 'w');
            fwrite($handle, $final_html);
        }

        /** add items in the $pageImages to the tmp folder */
        $pageImages = array_unique($pageImages);

        if (count($pageImages) > 0)
        {
            foreach ($pageImages as $image)
            {
                $temp = explode('?', $image);

                $image = $temp[0];
                $script_file_info = new SplFileInfo($image);

                //$info = new SplFileInfo('./img/exaple-image.jpg');

                if (substr($image, 0, 1) == '/')
                {
                    $image_ = "." . $image;
                }
                /** image located in the user upload folder */
                elseif (substr($image, 0, mb_strlen($this->config->item('images_uploadDir'))) == $this->config->item('images_uploadDir'))
                {
                    $image_ = "./" . $image;
                }
                /** stuff located in the bundles folder */
                else
                {
                    if (strpos($image, 'bundles') !== false)
                    {
                        $image_ = './' . $this->config->item('elements_dir') . '/' . $image;
                    }
                    else
                    {
                        $image_ = './' . $image;
                    }

                }

                $real_file_info = new SplFileInfo($image_);

                /** copy current file to tmp folder */
                if (file_exists($real_file_info->getRealPath()))
                {
                    if ( ! is_dir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPath()))
                    {
                        mkdir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPath(), 0777, TRUE);
                    }
                    copy($real_file_info->getRealPath(), FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPathname());
                }
            }
        }

		/** add favicon file*/
		if($theSite->favicon != '')
		{
			$faviconPath = $this->config->item('images_uploadDir'). '/' . $theSite->users_id . '/'. 'favicon/'. $theSite->favicon;
			$faviconFile =  new SplFileInfo($faviconPath);
			if (file_exists($faviconFile->getRealPath()))
			{
				if ( ! is_dir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $faviconFile->getPath()))
				{
					mkdir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $faviconFile->getPath(), 0777, TRUE);
				}
				copy($faviconFile->getRealPath(), FCPATH . 'tmp/ftp/' . $site_id . '/' . $faviconFile->getPathname());
			}

		}

        /** add items in the $cssUrls to the tmp folder */
        $cssUrls = array_unique($cssUrls);

        if (count($cssUrls) > 0)
        {
            foreach ($cssUrls as $cssUrl)
            {
                $temp = explode('?', $cssUrl);

                $cssUrl = $temp[0];
                $script_file_info = new SplFileInfo($cssUrl);

                $cssUrl_ = './' . $this->config->item('elements_dir') . '/' . $cssUrl;
                $real_file_info = new SplFileInfo($cssUrl_);

                /** copy current file to tmp folder */
                if (file_exists($real_file_info->getRealPath()))
                {
                    if ( ! is_dir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPath()))
                    {
                        mkdir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPath(), 0777, TRUE);
                    }
                    copy($real_file_info->getRealPath(), FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPathname());
                }
            }
        }

        /** add items in the $jsUrls to the tmp folder */
        $jsUrls = array_unique($jsUrls);

        if (count($jsUrls) > 0)
        {
            foreach ($jsUrls as $jsUrl)
            {
                $temp = explode('?', $jsUrl);

                $jsUrl = $temp[0];
                $script_file_info = new SplFileInfo($jsUrl);

                $jsUrl_ = './' . $this->config->item('elements_dir') . '/' . $jsUrl;
                $real_file_info = new SplFileInfo($jsUrl_);

                /** copy current file to tmp folder */
                if (file_exists($real_file_info->getRealPath()))
                {
                    if ( ! is_dir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPath()))
                    {
                        mkdir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPath(), 0777, TRUE);
                    }
                    copy($real_file_info->getRealPath(), FCPATH . 'tmp/ftp/' . $site_id . '/' . $script_file_info->getPathname());
                }
            }
        }

        /** Add folder structure */
        /** Prep path to assets array */
        /** Only web fonts are still added the old way */
        $temp = explode("|", $this->config->item('export_pathToAssets'));

        foreach ($temp as $thePath)
        {
            /** Create recursive directory iterator */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($thePath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file)
            {
                if ($file->getFilename() != '.' && $file->getFilename() != '..' && (strpos($file->getFilename(), ".woff") || strpos($file->getFilename(), ".woff2") || strpos($file->getFilename(), ".ttf") || strpos($file->getFilename(), ".eot")))
                {
                    /** Get real path for current file */
                    $filePath = $file->getRealPath();

                    if (strpos($file,'images/') === FALSE)
                    {
                        $temp = explode("/", $name);
                        array_shift( $temp );
                        $newName = implode("/", $temp);
                    }
                    else
                    {
                        $newName = $name;
                    }
                    $file_info = new SplFileInfo($newName);

                    /** copy current file to tmp folder */
                    if (file_exists($file->getRealPath()))
                    {
                        if ( ! is_dir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $file_info->getPath()))
                        {
                            mkdir(FCPATH . 'tmp/ftp/' . $site_id . '/' . $file_info->getPath(), 0777, TRUE);
                        }
                        copy($file->getRealPath(), FCPATH . 'tmp/ftp/' . $site_id . '/' . $file_info->getPathname());
                    }
                }
            }
        }

        function my_tag_function($element, $attribute_array=0)
        {
            if (is_numeric($attribute_array))
            {
                if ($element == 'script')
                {
                    return "</$element>";
                }
                else
                {
                    return "</$element>";
                }
            }

            $string = '';
            foreach ($attribute_array as $k=>$v)
            {
                $string .= " {$k}=\"{$v}\"";
            }

            if ($element == 'script')
            {
                return "<{$element}{$string}> ";
            }
            else
            {
                return "<{$element}{$string}>";
            }
        }

        /** Upload files to FTP location */
        $config['hostname'] = $theSite->ftp_server;
        $config['username'] = $theSite->ftp_user;
        $config['password'] = $theSite->ftp_password;
        $config['path'] = $theSite->ftp_path;
        $config['type'] = $theSite->ftp_type;
        $config['port'] = $theSite->ftp_port;

        if ($config['type'] == 'ftp')
        {
            $this->load->library('ftp');
            if ($this->ftp->connect($config))
            {
                if ($this->ftp->mirror(FCPATH . 'tmp/ftp/' . $site_id . '/', $config['path'] . '/'))
                {
                    /** All went well */
                    $this->MSites->published($site_id);

                    $this->load->helper('file');
                    delete_files(FCPATH . 'tmp/ftp/' . $site_id, true , false, 1);

                    $this->return = array();
                    $this->return['responseCode'] = 1;

                    die(json_encode($this->return));
                }
            }
        }
        else
        {
            $this->load->library('sftp');
            if ($this->sftp->connect($config))
            {
                if ($this->sftp->mirror(FCPATH . 'tmp/ftp/' . $site_id . '/', $config['path'] . '/'))
                {
                    /** All went well */
                    $this->MSites->published($site_id);

                    $this->load->helper('file');
                    delete_files(FCPATH . 'tmp/ftp/' . $site_id, true , false, 1);

                    $this->return = array();
                    $this->return['responseCode'] = 1;

                    die(json_encode($this->return));
                }
            }
        }

        /** Hook point */
        $this->hooks->call_hook('sites_export_post');

        exit;
    }

    /**
     * Exports a site
     *
     * @return  void
     */
    public function export()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_export_pre');

        $this->load->helper('base64');

        $userID = $this->session->userdata('user_id');

        $zip = new ZipArchive();
        $zip->open("./tmp/" . $this->config->item('export_fileName'), ZipArchive::CREATE);

        $cssUrls = [];
        $jsUrls = [];
        $pageImages = [];
        $fonts = [];

        $theSite = $this->MSites->siteData($_POST['siteID']);
        foreach ($_POST['pages'] as $page=>$content)
        {
            /** Get page meta */
            $pageMeta = $this->MPages->getSinglePage($_POST['siteID'], $page);

            // decode the markup
            $content = $this->MPages->load_page($pageMeta->pages_id);
            if(!$content)
				continue;
            /** fix up bits in the <head> */
            $pageContent = str_replace('<html><head>', "<html>\n<head>", $content);
            $pageContent = str_replace('</head>', "\n</head>", $pageContent);
            $pageContent = str_replace('<style', "\n\t<style", $pageContent);
            $pageContent = str_replace('</style><link', "</style>\n\t<link", $pageContent);

            if ($pageMeta)
            {
				$meta = '';
				/** add favicon */
				if($theSite->favicon != '')
				{
					$favicon = '<link rel="shortcut icon" href="'. $this->config->item('images_uploadDir'). '/' . $theSite->users_id . '/'. 'favicon/'. $theSite->favicon . '" />' . "\r\n";
					$meta .= $favicon;
				}
                /** Insert title, meta keywords and meta description */
                $meta .= "\t".'<title>' . $pageMeta->pages_title . '</title>' . "\r\n";
                $meta .= "\t".'<meta name="description" content="' . $pageMeta->pages_meta_description . '">' . "\r\n";
                $meta .= "\t".'<meta name="keywords" content="' . $pageMeta->pages_meta_keywords . '">';
                $pageContent = str_replace('<!--pageMeta-->', $meta, $pageContent);

                /** Insert header includes; */
                $includesPlusCss = '';
                if ($pageMeta->pages_header_includes != '')
                {
                    $includesPlusCss .= $pageMeta->pages_header_includes;
                }
                if ($pageMeta->pages_css != '')
                {
                    $includesPlusCss .= "\n\t<style>" . $pageMeta->pages_css . "</style>\n";
                }
                if ($theSite->global_css != '')
                {
                    $includesPlusCss .= "\n\t<style>" . $theSite->global_css . "</style>\n";
                }

                /** Google fonts */
                if ($pageMeta->google_fonts !== '' && $pageMeta->google_fonts !== '[]')
                {
                    $googleFonts = json_decode($pageMeta->google_fonts);
                    $apiString = $this->config->item('google_font_api');

                    if (is_array($googleFonts))
                    {
                        foreach ($googleFonts as $font)
                        {
                            $apiString .= $font->api_entry;
                            $apiString .= '|';
                        }
                    }

                    $apiString = '<link href="' . $apiString . '" rel="stylesheet" type="text/css">';

                    $pageContent = str_replace("</head>", $apiString . "\n</head>", $pageContent);
                }

                /** Deal with global JS */
                if ($theSite->global_js != '')
                {
                    $custom_js = trim(strip_tags($theSite->global_js, '<script>'));
                    $pos = strpos($custom_js, '<script');
                    if ($pos !== FALSE && $pos == 0)
                    {
                        $pageContent = str_replace("</head>", "\n" . $custom_js . "\n</head>", $pageContent);
                    }
                    else
                    {
                        $pageContent = str_replace("</head>", "\n<script>\n" . $custom_js . "\n</script>\n</head>", $pageContent);
                    }
                }

                /** Insert header includes */
                $pageContent = str_replace('<!--headerIncludes-->', $includesPlusCss, $pageContent);
            }
            else
            {
                $pageContent = $content;
            }

            /** Remove frameCovers */
            $pageContent = str_replace('<div class="frameCover" data-type="video"></div>', "", $pageContent);

            /** This is needed for correct exports */
            $pageContent = str_replace('src="../', 'src="', $pageContent);
            $pageContent = str_replace("src='../", "src='", $pageContent);
            $pageContent = str_replace('url(../', 'url(', $pageContent);
            $pageContent = str_replace("url('../", "url('", $pageContent);
            $pageContent = str_replace('url("../', 'url("', $pageContent);
            $pageContent = str_replace('&quot;../bundles/', '&quot;bundles/', $pageContent);//FF needs this

            if ($this->config->item('google_api') !== '')
            {
                $pageContent = str_replace('</body>', '<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . $this->config->item('google_api') . '&callback=initMap"></script>' . '</body>' . "\n", $pageContent);
            }

            /** setup of htmLawed helper */
            $this->load->helper('htmlawed');

            $config = array(
                'tidy' => '1t1n',
                'direct_nest_list' => 1,
                'keep_bad' => 2,
                'css_expression' => 1,
                'elements' => '*',
                'lc_std_val' => 0,
                'make_tag_strict' => 0,
                'no_deprecated_attr' => 0,
                'style_pass' => 1,
                'unique_ids' => 0,
                'parent' => 'body',
                'hook_tag' => 'my_tag_function',
                'balance' => 0
            );

            /** simle html dom library */
            $this->load->library('Simple_html_dom');
            $raw = str_get_html($pageContent, true, true, DEFAULT_TARGET_CHARSET, false);

            /** body first */
            $body = $raw->find('body')[0];
            $sanitzedBody = htmLawed($body, $config);
            $raw->find('body')[0]->innertext = $sanitzedBody;

            /** extract CSS urls */
            foreach ($raw->find('link[rel="stylesheet"]') as $cssLink)
            {
                /** extract CSS link, no need for blob uhrls */
                if (substr($cssLink->href, 0, 4) != 'blob')
                {
                    $cssUrls[] = $cssLink->href;

                    /** extract images from CSS */
                    if (substr($cssLink->href, 0, 4) != 'http' && substr($cssLink->href, 0, 2) != '//')
                    {
                        $CSS = file_get_contents("./".$this->config->item('elements_dir')."/".$cssLink->href);

                        $re = '/url\(\s*[\'"]?(\S*\.(?:jpe?g|gif|png))[\'"]?\s*\)[^;}]*?/i';
                        if (preg_match_all($re, $CSS, $matches))
                        {
                            foreach ($matches[1] as $img)
                            {
                                $pageImages[] = $img;
                            }
                        }
                    }

                    /** extract fonts from CSS */
                    $re = '/url\(([\'"]?.[^\'"]*\.(eot|woff|woff2|ttf)[\'"]?)\)/i';
                    if (preg_match_all($re, $CSS, $matches))
                    {
                        foreach ($matches[1] as $font)
                        {
                            $fonts[] = $font;
                        }
                    }
                }
            }

            /** extract JS urls */
            foreach ($raw->find('script[src]') as $jsLink)
            {
                $jsUrls[] = $jsLink->src;
            }

            /** extract <image> src */
            foreach ($body->find('img') as $image)
            {
                $pageImages[] = $image->src;
            }

            /** extract CSS background images */
            $re = '/url\(\s*[\'"]?(\S*\.(?:jpe?g|gif|png))[\'"]?\s*\)[^;}]*?/i';
            if (preg_match_all($re, str_replace("&quot;", "'", $raw), $matches))
            {
                foreach ($matches[1] as $img)
                {
                    $pageImages[] = $img;
                }
            }

            /** extract parallax data-image-src files */
            foreach ($body->find('*[data-parallax]') as $element)
            {
                $pageImages[] = $element->getAttribute('data-image-src');
            }

            /** last minute clean up of <script> tags */
            $raw = str_replace("\n\t\t \n\t</script>", "</script>", $raw);
            $raw = str_replace("\t</body>", "</body>", $raw);

            // die($raw);

            $zip->addFromString($page . ".html", $_POST['doctype'] . "\n" . stripslashes($raw));
            // echo $content;
        }

        /** add items in the $pageImages to the ZIP */
        $pageImages = array_unique($pageImages);

        if (count($pageImages) > 0)
        {
            foreach ($pageImages as $image)
            {
                $temp = explode('?', $image);

                $image = $temp[0];

                $info = new SplFileInfo('./img/exaple-image.jpg');

                if (substr($image, 0, 1) == '/')
                {
                    $image_ = "." . $image;
                }
                /** image located in the user upload folder */
                elseif (substr($image, 0, mb_strlen($this->config->item('images_uploadDir'))) == $this->config->item('images_uploadDir'))
                {
                    $image_ = "./" . $image;
                }
                /** stuff located in the bundles folder */
                else
                {
                    if (strpos($image, 'bundles') !== false)
                    {
                        $image_ = './'.$this->config->item('elements_dir').'/'.$image;
                    }
                    else
                    {
                        $image_ = './'.$image;
                    }

                }

                $info = new SplFileInfo($image_);

                /** Add current file to archive */
                if (file_exists($info->getRealPath()))
                {
                    $zip->addFile($info->getRealPath(), $image);
                }
            }
        }

		/** add favicon file */
		if($theSite->favicon != '')
		{

			$faviconPath = $this->config->item('images_uploadDir'). '/' . $theSite->users_id . '/'. 'favicon/'. $theSite->favicon;
			$faviconFile =  new SplFileInfo($faviconPath);
			if (file_exists($faviconFile->getRealPath()))
			{
				$zip->addFile($faviconFile->getRealPath(), $faviconPath);
			}
		}


        /** add items in the $cssUrls to the ZIP */
        $cssUrls = array_unique($cssUrls);

        if (count($cssUrls) > 0)
        {
            foreach ($cssUrls as $cssUrl)
            {
                $temp = explode('?', $cssUrl);

                $cssUrl = $temp[0];

                $cssUrl_ = './' . $this->config->item('elements_dir') . '/' . $cssUrl;

                $info = new SplFileInfo($cssUrl_);

                /** Add current file to archive */
                if (file_exists($info->getRealPath()))
                {
                    $zip->addFile($info->getRealPath(), $cssUrl);
                }
            }
        }

        /** add items in the $jsUrls to the ZIP */
        $jsUrls = array_unique($jsUrls);

        if (count($jsUrls) > 0)
        {
            foreach ($jsUrls as $jsUrl)
            {
                $temp = explode('?', $jsUrl);

                $jsUrl = $temp[0];

                $jsUrl_ = './' . $this->config->item('elements_dir') . '/' . $jsUrl;

                $info = new SplFileInfo($jsUrl_);

                /** Add current file to archive */
                if (file_exists($info->getRealPath()))
                {
                    $zip->addFile($info->getRealPath(), $jsUrl);
                }
            }
        }

        //die();

        /** Add folder structure */
        /** Prep path to assets array */
        /** Only web fonts are still added the old way */
        $temp = explode("|", $this->config->item('export_pathToAssets'));

        foreach ($temp as $thePath)
        {
            /** Create recursive directory iterator */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($thePath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file)
            {
                if ($file->getFilename() != '.' && $file->getFilename() != '..' && (strpos($file->getFilename(), ".woff") || strpos($file->getFilename(), ".woff2") || strpos($file->getFilename(), ".ttf") || strpos($file->getFilename(), ".eot")))
                {
                    /** Get real path for current file */
                    $filePath = $file->getRealPath();

                    if (strpos($file,'images/') === FALSE)
                    {
                        $temp = explode("/", $name);
                        array_shift( $temp );
                        $newName = implode("/", $temp);
                    }
                    else
                    {
                        $newName = $name;
                    }

                    /** Add current file to archive */
                    $zip->addFile($filePath, $newName);
                }
            }
        }

        function my_tag_function($element, $attribute_array=0)
        {
            if (is_numeric($attribute_array))
            {
                if ($element == 'script')
                {
                    return "</$element>";
                }
                else
                {
                    return "</$element>";
                }
            }

            $string = '';
            foreach ($attribute_array as $k=>$v)
            {
                $string .= " {$k}=\"{$v}\"";
            }

            if ($element == 'script')
            {
                return "<{$element}{$string}> ";
            }
            else
            {
                return "<{$element}{$string}>";
            }
        }

        $zip->close();
        $yourfile = $this->config->item('export_fileName');
        $file_name = basename($yourfile);

        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Length: " . filesize("./tmp/" . $yourfile));

        readfile("./tmp/" . $yourfile);

        unlink('./tmp/' . $yourfile);

        /** Hook point */
        $this->hooks->call_hook('sites_export_post');

        exit;
    }

    /**
     * Moves a single site to the trash bin
     *
     * @param   string      $site_id
     * @return  json        $return
     */
    public function trash($site_id = '')
    {
        /** Hook point */
        $this->hooks->call_hook('sites_trash_pre');

        $temp = array();

        if ($site_id == '' || $site_id == 'undefined')
        {
            $temp['header'] = $this->lang->line('sites_trash_no_site_error_heading');
            $temp['content'] = $this->lang->line('sites_trash_no_site_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            die(json_encode($this->return));
        }

        /** All good, move to trash */
        if ($this->session->userdata('user_type') == "Admin")
        {
            if ( !$this->MSites->trash($site_id) )
            {
                $temp['header'] = $this->lang->line('sites_trash_no_site_error_heading');
                $temp['content'] = $this->lang->line('sites_trash_no_site_error_message');

                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                die(json_encode($this->return));
            }
        }
        else
        {
            if ( !$this->MSites->trash($site_id, $this->session->userdata('user_id')) )
            {
                $temp['header'] = $this->lang->line('sites_trash_no_site_error_heading');
                $temp['content'] = $this->lang->line('sites_trash_no_site_error_message');

                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                die(json_encode($this->return));
            }
        }

        $temp['header'] = $this->lang->line('sites_trash_success_heading');
        $temp['content'] = $this->lang->line('sites_trash_success_message');

        $this->return = array();
        $this->return['responseCode'] = 1;
        $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

        /** Hook point */
        $this->hooks->call_hook('sites_trash_post');

        die(json_encode($this->return));
    }

    /**
     * Updates page meta data with ajax call
     *
     * @return  json        $return
     */
    public function updatePageData()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_updatePageData_pre');

        if ($_POST['siteID'] == '' || $_POST['siteID'] == 'undefined' || ! isset($_POST))
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_updatePageData_no_site_error_heading');
            $temp['content'] = $this->lang->line('sites_updatePageData_no_site_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            die(json_encode($this->return));
        }

        /** Update page data */
        $this->pagemodel->updatePageData($_POST);

        /** Return page data as well */
        $this->return = array();
        $pagesData = $this->pagemodel->getPageData($_POST['siteID']);
        if ($pagesData)
        {
            $this->return['pagesData'] = $pagesData;
        }

        $temp = array();
        $temp['header'] = $this->lang->line('sites_updatePageData_success_heading');
        $temp['content'] = $this->lang->line('sites_updatePageData_success_message');

        $this->return['responseCode'] = 1;
        $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

        /** Hook point */
        $this->hooks->call_hook('sites_updatePageData_post');

        die(json_encode($this->return));
    }

    /**
     * Function generates a live preview of current changes
     *
     * @return  void
     */
    public function livepreview()
    {
        $this->load->helper('base64');

        /** Hook point */
        $this->hooks->call_hook('sites_livepreview_pre');

        if (isset($_POST['siteID']) && $_POST['siteID'] != '')
        {
            $siteData = $this->MSites->siteData($_POST['siteID']);
        }

        $meta = '';
		/** Favicon */

		if($siteData->favicon != '')
		{
			$favicon = '<link rel="shortcut icon" href="'. $this->config->item('images_uploadDir'). '/' . $siteData->users_id . '/'. 'favicon/'. $siteData->favicon . '" />';
			$meta .= $favicon;
		}
        /** Page title */
        if (isset($_POST['meta_title']) && $_POST['meta_title'] != '')
        {
            $meta .= '<title>' . $_POST['meta_title'] . '</title>' . "\n";
        }
        /** Page meta description */
        if (isset($_POST['meta_description']) && $_POST['meta_description'] != '')
        {
            $meta .= '<meta name="description" content="' . $_POST['meta_description'] . '"/>' . "\n";
        }
        /** Page meta keywords */
        if (isset($_POST['meta_keywords']) && $_POST['meta_keywords'] != '')
        {
            $meta .= '<meta name="keywords" content="' . $_POST['meta_keywords'] . '"/>' . "\n";
        }
        /** Replace meta value */
        $content = str_replace('<!--pageMeta-->', $meta, "<!DOCTYPE html>\n" . custom_base64_decode($_POST['page']));

        /** Replace both inline css image url and image tag src */
        $content = str_replace('../bundles', 'bundles', $content);

        $head = '';
        /** Page header includes */
        if (isset($_POST['header_includes']) && $_POST['header_includes'] != '')
        {
            $head .= $_POST['header_includes'] . "\n";
        }

        /** Deal with global CSS and page CSS **/
        $custom_css = "";
        if (isset($siteData->global_css) && $siteData->global_css != '')
        {
            $custom_css .= $siteData->global_css . "\n";
        }
        if (isset($_POST['page_css']) && $_POST['page_css'] != '')
        {
            $custom_css .= $_POST['page_css'];
        }

        if ($custom_css !== '')
        {
            $content = str_replace("</head>", "\n<style>\n" . $custom_css . "\n</style>\n</head>", $content);
        }

        /** Deal with global JS **/
        if (isset($siteData->global_js) && $siteData->global_js != '')
        {
            $custom_js = trim(strip_tags($siteData->global_js, '<script>'));
            $pos = strpos($custom_js, '<script');
            if ($pos !== FALSE && $pos == 0)
            {
                $content = str_replace("</head>", "\n" . $custom_js . "\n</head>", $content);
            }
            else
            {
                $content = str_replace("</head>", "\n<script>\n" . $custom_js . "\n</script>\n</head>", $content);
            }
        }

        /** Custom header to deal with XSS protection */
        header("X-XSS-Protection: 0");

        /** Hook point */
        $this->hooks->call_hook('sites_livepreview_post');

        echo str_replace('<!--headerIncludes-->', $head, $content);
    }

    /**
     * Delete template
     *
     * @param   integer     $site_id
     * @param   integer     $page_id
     * @return  void
     */
    public function deltempl($site_id, $page_id)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_deltempl_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            die($this->lang->line('admin_permission_error'));
        }
        $this->MPages->deleteTemplate($site_id, $page_id);
        $return = array();
        $this->session->set_flashdata('success', $this->lang->line('sites_deltempl_delete_success'));

        /** Hook point */
        $this->hooks->call_hook('sites_deltempl_post');

        redirect('sites/' . $site_id, 'refresh');
    }

    /**
     * Attempts to retrieve a preview for a revision
     *
     * @param   string      $site_id
     * @param   string      $revisionStamp
     * @return  void
     */
    public function rpreview($site_id = '', $revisionStamp = '')
    {
        /** Hook point */
        $this->hooks->call_hook('sites_rpreview_pre');

        if ($site_id == '' || $revisionStamp == '' || $_GET['p'] == '')
        {
            die($this->lang->line('sites_rpreview_error'));
        }
        $page = $_GET['p'];
        $this->revisionOutput = $this->MRevisions->buildRevision($site_id, $revisionStamp, $page);

        /** Hook point */
        $this->hooks->call_hook('sites_rpreview_post');

        echo $this->revisionOutput;
    }

    /**
     * Updates revisions for a certain page with ajax call
     *
     * @param   string      $site_id
     * @param   string      $page
     * @return  void
     */
    public function getRevisions($site_id = '', $page = '')
    {
        /** Hook point */
        $this->hooks->call_hook('sites_getRevisions_pre');

        if ($site_id != '' && $page != '')
        {
            $this->revisions = $this->MRevisions->getForSite( $site_id, $page );

            /** Hook point */
            $this->hooks->call_hook('sites_getRevisions_post');

            $this->load->view('shared/revisions', array('revisions'=>$this->revisions, 'page'=>$page, 'siteID'=>$site_id));
        }
    }

    /**
     * Deletes a revision with ajax call
     *
     * @param   string      $site_id
     * @param   string      $timestamp
     * @param   string      $page
     * @return  json        $this->return
     */
    public function deleterevision($site_id = '', $timestamp = '', $page = '')
    {
        /** Hook point */
        $this->hooks->call_hook('sites_deleterevision_pre');

        $this->return = array();
        if ($site_id == '' || $timestamp == '' || $page == '')
        {
            $this->return['code'] = 0;
            $this->return['message'] = $this->lang->line('sites_deleterevision_delete_error');
            die(json_encode($this->return));
        }
        $this->MRevisions->delete($site_id, $timestamp, $page);
        $this->return['code'] = 1;
        $this->return['message'] = $this->lang->line('sites_deleterevision_delete_success');

        /** Hook point */
        $this->hooks->call_hook('sites_deleterevision_post');

        echo json_encode($this->return);
    }

    /**
     * Restores a revision for a specific page
     *
     * @param   string      $site_id
     * @param   string      $timestamp
     * @param   string      $page
     * @return  void
     */
    public function restorerevision($site_id = '', $timestamp = '', $page = '')
    {
        /** Hook point */
        $this->hooks->call_hook('sites_restorerevision_pre');

        if ($site_id == '' || $timestamp == '' || $page == '')
        {
            die($this->lang->line('sites_restorerevision_error'));
        }
        $this->MRevisions->restore($site_id, $timestamp, $page);

        /** Hook point */
        $this->hooks->call_hook('sites_restorerevision_post');

        redirect('sites/' . $site_id . "?p=" . $page, 'location');
    }

    /**
     * Loads a single page so a screenshot can be generated
     *
     * @param   integer     $page_id
     * @return  void
     */
    public function loadsinglepage($page_id)
    {
        die($this->MPages->load_page($page_id));
    }

    /**
     * Loads a single frame so a screenshot can be generated
     *
     * @param   integer     $frame_id
     * @return  void
     */
    public function loadsingleframe($frame_id)
    {
        die($this->MFrames->load_frame($frame_id));
    }

    /**
     * Connects FTP and returns a list of files/folders
     *
     * @return  json        $this->return
     */
    public function connect()
    {
        if ($this->input->post('ftp_type') == 'ftp')
        {
            $this->load->library('ftp');

            $config['hostname'] = $this->input->post('ftp_server');
            $config['username'] = $this->input->post('ftp_user');
            $config['password'] = $this->input->post('ftp_password');
            $config['port'] = $this->input->post('ftp_port');
            $config['debug'] = FALSE;

            if ($this->ftp->connect($config))
            {
                $path = ($this->input->post('ftp_path') != '') ? $this->input->post('ftp_path') : "/";

                $list = $this->ftp->list_files($path);

                if ($list)
                {
                    $temp = array();
                    $temp['list'] = $list;
                    $temp['data'] = $this->input->post();
                    $this->return = array();
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/ftplist', array('data'=>$temp), true);
                    die(json_encode($this->return));
                }
                else
                {
                    $temp = array();
                    $temp['header'] = $this->lang->line('sites_connect_path_error_heading');
                    $temp['content'] = $this->lang->line('sites_connect_path_error_message');
                    $this->return = array();
                    $this->return['responseCode'] = 0;
                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), true);
                    die(json_encode($this->return));
                }
            }
            else
            {
                $temp = array();
                $temp['header'] = $this->lang->line('sites_connect_details_error_heading');
                $temp['content'] = $this->lang->line('sites_connect_details_error_message');
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), true);
                die(json_encode($this->return));
            }
        }
        else
        {
            $this->load->library('sftp');

            $config['hostname'] = $this->input->post('ftp_server');
            $config['username'] = $this->input->post('ftp_user');
            $config['password'] = $this->input->post('ftp_password');
            $config['port'] = $this->input->post('ftp_port');
            $config['debug'] = FALSE;

            if ($this->sftp->connect($config))
            {
                $path = ($this->input->post('ftp_path') != '') ? $this->input->post('ftp_path') : '/';

                $list = $this->sftp->list_files($path);

                if ($list)
                {
                    $temp = array();
                    $temp['list'] = $list;
                    $temp['data'] = $this->input->post();
                    $this->return = array();
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/ftplist', array('data'=>$temp), true);
                    die(json_encode($this->return));
                }
                else
                {
                    $temp = array();
                    $temp['header'] = $this->lang->line('sites_connect_path_error_heading');
                    $temp['content'] = $this->lang->line('sites_connect_path_error_message');
                    $this->return = array();
                    $this->return['responseCode'] = 0;
                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), true);
                    die(json_encode($this->return));
                }
            }
            else
            {
                $temp = array();
                $temp['header'] = $this->lang->line('sites_connect_details_error_heading');
                $temp['content'] = $this->lang->line('sites_connect_details_error_message');
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), true);
                die(json_encode($this->return));
            }
        }
    }

    /**
     * Tests an FTP connection and verifies it's details
     *
     * @return  json        $this->return
     */
    public function test_ftp()
    {
        $path = ($this->input->post('ftp_path') != '') ? $this->input->post('ftp_path') : "/";

        $result = $this->MFtp->test($this->input->post('ftp_server'), $this->input->post('ftp_user'), $this->input->post('ftp_password'), $this->input->post('ftp_port'), $path, $this->input->post('ftp_type'));

        $this->return = array();

        /** All good */
        if ($result['connection'])
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_test_success_heading');
            $temp['content'] = $this->lang->line('sites_test_success_message');
            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), true);
            die(json_encode($this->return));
        }
        else
        {
            /** Connection details failed */
            if ($result['problem'] == 'connection')
            {
                $temp = array();
                $temp['header'] = $this->lang->line('sites_test_error_details_heading');
                $temp['content'] = $this->lang->line('sites_test_error_details_message');
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), true);
                die(json_encode($this->return));
            }
            /** Path failed */
            elseif ($result['problem'] == 'path')
            {
                $temp = array();
                $temp['header'] = $this->lang->line('sites_test_error_path_heading');
                $temp['content'] = $this->lang->line('sites_test_error_path_message');
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), true);
                die(json_encode($this->return));
            }
        }
    }

    /**
     * Clone enitire site
     *
     * @param   integer     $site_id
     * @return  void
     */
    public function clone_site($site_id)
    {
        /** If a user wants to clone a site */
        if ($this->session->userdata('user_type') != "Admin")
        {
            /** Check if user package support create new site */
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            $sites = $this->MSites->site_by_user($this->session->userdata('user_id'));

            /** User has some sites */
            if (count($sites) > 0)
            {
                /** User's site is more or equal its package number */
                if (count($sites) >= $package['sites_number'])
                {
                    $this->session->set_flashdata('error', $this->lang->line('sites_create_site_exceed'));
                    redirect('sites', 'refresh');
                }
            }
        }

        $site = $this->MSites->get_by_id($site_id);
        $site_name = $site['sites_name'] . ' - Clone';
        $pages = $this->MPages->get_all($site_id);
        $this->MSites->clone_site($site, $pages);

        $this->session->set_flashdata('success', $this->lang->line('sites_clone_confirm'));
        redirect('sites', 'refresh');
    }

    /**
     * Clones a block
     *
     * @return  void
     */
    public function cloneoriginal_block()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_cloneoriginal_block_pre');

        $this->load->helper('file');
        $this->load->helper('string');
        $this->load->helper('base64');

        $this->data['return'] = array();

        $this->data['return']['responseCode'] = 1;
        $this->data['return']['content'] = "It's all good!";

        $this->form_validation->set_rules('frames_content', 'Frame content', 'required');
        $this->form_validation->set_rules('frames_height', 'Block height', 'required');
        $this->form_validation->set_rules('frames_width', 'Block width', 'required');
        $this->form_validation->set_rules('category', 'Category ID', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->data['return']['responseCode'] = 0;
            $this->data['return']['content'] = $this->lang->line('sites_cloneoriginal_block_error') . validation_errors();
        }
        else
        {
            if ( ! file_exists('./' . $this->config->item('elements_dir') . '/' .$this->config->item('cloned_folder'))) {
                if ( ! mkdir('./' . $this->config->item('elements_dir') . '/' .$this->config->item('cloned_folder'), 0777, true))
                {
                    $this->data['return']['responseCode'] = 0;
                    $this->data['return']['content'] = $this->lang->line('sites_cloneoriginal_block_error2');
                    die(json_encode($this->data['return']));
                }
            }

            $theNewFile = $this->config->item('elements_dir') . '/' . $this->config->item('cloned_folder') . '/clone_' . random_string('numeric', 10) . '.html';

            $content_decoded = custom_base64_decode($this->input->post('frames_content'));
            $content_prepped = $this->MSites->processFrameContent($content_decoded);

            if (write_file('./' . $theNewFile, $content_prepped) )
            {
                // Make the screenshot
                $screenshot_url = base_url($theNewFile);
                $filename = 'blockthumb_' . random_string('numeric', 12) . '.jpg';

                $this->load->library('screenshot_library');
                $screenshot = $this->screenshot_library->make_screenshot($screenshot_url, $filename, $this->input->post('frames_width') . 'x0', $this->config->item('screenshot_blockhumbs_folder'), 'page', 'false');

                $newBlock['blocks_category'] = $this->input->post('category');
                $newBlock['blocks_url'] = $theNewFile;
                $newBlock['blocks_height'] = $this->input->post('frames_height');
                $newBlock['blocks_thumb'] = $this->config->item('screenshot_blockhumbs_folder') . $screenshot;

                $this->MBlocks->addBlock($newBlock);

                $this->data['return']['responseCode'] = 1;
                $this->data['return']['content'] = $this->lang->line('sites_cloneoriginal_block_success');
            }
            else
            {
                $this->data['return']['responseCode'] = 0;
                $this->data['return']['content'] = $this->lang->line('sites_cloneoriginal_block_error2');
            }
        }

        /** Hook point */
        $this->hooks->call_hook('sites_cloneoriginal_block_post');

        echo json_encode($this->data['return']);
    }

    /**
     * Deletes original block from system
     *
     * @return  void
     */
    public function deleteoriginal_block()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_deleteoriginal_block_pre');

        $this->data['return'] = array();

        $this->form_validation->set_rules('frames_url', 'Block template url', 'required|valid_url');

        if ($this->form_validation->run() == FALSE)
        {
            $this->data['return']['responseCode'] = 0;
            $this->data['return']['content'] = $this->lang->line('sites_deleteoriginal_block_error') . validation_errors();
        }
        else
        {
            $file = str_replace($this->config->item('base_url'), '', $this->input->post('frames_url'));

            // All good, delete file
            if ($this->MBlocks->deleteBlockForUrl($file))
            {
                $this->data['return']['responseCode'] = 1;
                $this->data['return']['content'] = $this->lang->line('sites_deleteoriginal_block_success');
            }
            else
            {
                $this->data['return']['responseCode'] = 0;
                $this->data['return']['content'] = $this->lang->line('sites_deleteoriginal_block_error2');
            }
        }

        /** Hook point */
        $this->hooks->call_hook('sites_deleteoriginal_block_post');

        echo json_encode($this->data['return']);
    }

    /**
     * Updates the block's original template file
     *
     * @return  void
     */
    public function updateoriginal_block()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_updateoriginal_block_pre');

        $this->data['return'] = array();

        $this->load->helper('string');
        $this->load->helper('base64');

        $this->form_validation->set_rules('frames_content', 'Frame content', 'required');
        $this->form_validation->set_rules('frames_url', 'Block template url', 'required|valid_url');
        $this->form_validation->set_rules('frames_height', 'Block height', 'required');
        $this->form_validation->set_rules('frames_width', 'Block width', 'required');

        /** All did not go well */
        if ($this->form_validation->run() == FALSE)
        {
            $this->data['return']['responseCode'] = 0;
            $this->data['return']['content'] = $this->lang->line('sites_updateoriginal_block_error') . validation_errors();
        }
        else
        {
            $content_decoded = custom_base64_decode($this->input->post('frames_content'));
            $content_prepped = $this->MSites->processFrameContent($content_decoded);

            if ($this->MBlocks->updateOriginal($this->input->post('frames_url'), $content_prepped))
            {
                $this->data['return']['responseCode'] = 1;
                $this->data['return']['content'] = $this->lang->line('sites_updateoriginal_block_success');

                // Screenshot time
                $screenshot_url = $this->input->post('frames_url');
                $filename = 'blockthumb_' . random_string('numeric', 12) . '.jpg';

                $this->load->library('screenshot_library');

                $height = ($this->input->post('frames_height') == '90vh')? round(0.66 * $this->input->post('frames_width')) : $this->input->post('frames_height');

                $screenshot = $this->screenshot_library->make_screenshot($screenshot_url, $filename, $this->input->post('frames_width') . 'x' . $height, $this->config->item('screenshot_blockhumbs_folder'));

                // Update the database
                $this->MBlocks->updateScreenshotWithUrl(
                    str_replace($this->config->item('base_url'), '', $this->input->post('frames_url')),
                    $this->config->item('screenshot_blockhumbs_folder') . $screenshot
                );
            }
            else
            {
                $this->data['return']['responseCode'] = 0;
                $this->data['return']['content'] = $this->lang->line('sites_updateoriginal_block_error2');
            }
        }

        /** Hook point */
        $this->hooks->call_hook('sites_updateoriginal_block_post');

        echo json_encode($this->data['return']);
    }

    /**
     * Save favourite block
     *
     * @return array    $this->data
     */
    public function favourite_block()
    {
        if ($this->input->post())
        {
            /** Hook point */
            $this->hooks->call_hook('sites_favourite_block_pre');
            $this->data['return'] = [];

            $frame_id = $this->MFrames->insert_frames_as_fav($this->input->post());
            $block_id = $this->MBlocksFav->insert($frame_id, $this->input->post());
            $screenshot_url = base_url() . 'loadsingleframe/' . $frame_id;
            $filename = 'blockthumb_' . $block_id . '.jpg';

            $this->load->library('screenshot_library');
            $screenshot = $this->screenshot_library->make_screenshot($screenshot_url, $filename, $this->input->post('frames_width') . 'x0', $this->config->item('screenshot_blockhumbs_folder'), 'page', 'false');

            if ($screenshot)
            {
                // resize the image
                $config['source_image'] = $this->config->item('screenshot_blockhumbs_folder') . $screenshot;
                $config['width'] = 520;

                $this->load->library('image_lib', $config);

                $this->image_lib->resize();

                $blockthumb = $this->config->item('screenshot_blockhumbs_folder') . $screenshot;
                $this->MBlocksFav->update_field($block_id, 'blocks_thumb', $blockthumb);

                $this->data['return']['block'] = $this->MBlocksFav->getsingle($block_id);

                $this->data['return']['responseCode'] = 1;
            }
            else
            {
                // screenshot failed, remove the frame and fav block
                $this->MFrames->delete_frame($frame_id);
                $this->MBlocksFav->delete($block_id);

                $this->data['return']['responseCode'] = 0;
            }

            /** Hook point */
            $this->hooks->call_hook('sites_favourite_block_post');

            die(json_encode($this->data['return']));
        }
    }

    /**
     * Delete favourite block
     *
     * @param   integer         $block_id
     * @return  void
     */
    public function favourite_block_del($block_id)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_favourite_block_del_pre');

        $this->MBlocksFav->delete($block_id);

        $this->data['return']['responseCode'] = 1;

        /** Hook point */
        $this->hooks->call_hook('sites_favourite_block_del_post');

        die(json_encode($this->data['return']));
    }

    /**
     * Changes the owner of a website
     *
     * @param   integer         $siteID
     * @param   integer         $newOwnerID
     * @return  void
     */
    public function changeOwner($siteID, $newOwnerID)
    {
        /** Hook point */
        $this->hooks->call_hook('sites_changeOwner_pre');

        $this->data['return'] = array();
		
		$curOwnerID = $this->MSites->getUserIdBySiteId($siteID);

        if ($this->session->userdata('user_type') == 'Admin')
        {
            if ($siteID && $newOwnerID && $siteID != 'null' && $newOwnerID != '' && $siteID != '' && $newOwnerID != '')
            {
                if ($this->MSites->changeOwner($siteID, $newOwnerID))
                {
					//move favicon
					$site = $this->MSites->get_by_id($siteID);
					if($site['favicon'] != '')
					{
						$fav_old_path = './' . $this->config->item('images_uploadDir') . '/' . $curOwnerID . '/favicon/' . $site['favicon'];		
						$fav_new_path = './' . $this->config->item('images_uploadDir') . '/' . $newOwnerID . '/favicon/' . $site['favicon'];
						//create directory if doesn't exist
						if (!file_exists('./' . $this->config->item('images_uploadDir') . '/' . $newOwnerID .'/favicon'))
						{
							mkdir('./' . $this->config->item('images_uploadDir') . '/' . $newOwnerID .'/favicon', 0777, TRUE);
						}
						rename($fav_old_path, $fav_new_path);
					}
					
                    /** Hook point */
                    $this->hooks->call_hook('sites_changeOwner_success');

                    $this->data['return']['responseCode'] = 1;
                    $this->data['return']['responseHTML'] = $this->lang->line('sites_changeowner_success');
                }
                else
                {
                    /** Hook point */
                    $this->hooks->call_hook('sites_changeOwner_error2');

                    $this->data['return']['responseCode'] = 0;
                    $this->data['return']['responseHTML'] = $this->lang->line('sites_changeowner_error2');
                }
            }
            else
            {
                /** Hook point */
                $this->hooks->call_hook('sites_changeOwner_error');

                $this->data['return']['responseCode'] = 0;
                $this->data['return']['responseHTML'] = $this->lang->line('sites_changeowner_error');
            }

            die(json_encode($this->data['return']));
        }
    }

    /**
     * Controller desctruct method for custom hook point
     *
     * @return  void
     */
    public function __destruct()
    {
        /** Hook point */
        $this->hooks->call_hook('sites_destruct');
    }


    public function getLimitSites()
    {
        $page = $this->input->post('page');

        $this->data['title'] = $this->lang->line('sites_index_title');
        $this->data['content'] = 'sites';
        $this->data['page'] = 'site';
        /** Grab us some sites */
        $offset = ($page - 1) * $this->perPage;

        $this->data['sites'] = $this->MSites->get_all_limit('', $this->perPage, $offset);
        /** Get all users */
        $this->data['users'] = $this->MUsers->get_all();
        $gateway = $this->MPayments->get_by_name('payment_gateway');
        $this->data['packages'] = $this->MPackages->get_all($gateway[0]->value, true);
        $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
        $sites = $this->MSites->site_by_user($this->session->userdata('user_id'));

        if (count($package) > 0)
        {
            $user_sites = count($sites);
            $package_sites = (int)$package['sites_number'];
            if ($user_sites > 0)
            {
                /** User's site is more or equal to its package number */
                if ($user_sites >= $package_sites)
                {
                    $this->data['site_limitation'] = $this->lang->line('sites_index_reach_site_number');
                }
                else if ($user_sites + 2 >= $package_sites)
                {
                    $this->data['site_limitation'] = $this->lang->line('sites_index_almost_reach_site_number');
                }
            }
        }

        if ($this->session->userdata('user_type') == 'Admin')
        {
            // Get the template categories
            $templateCategories = $this->MTemplates->getCategories();
            $sendToBrowser = [];
            $newTemplates = [];

            $noCat = $this->MTemplates->allWithNoCategory();

            if ($noCat)
            {
                $temp = [];
                $temp['category_name'] = $this->lang->line('sites_index_no_template_cat');
                $temp['templates_categories_id'] = 0;

                $sendToBrowser[] = $temp;

                foreach ($noCat as $template)
                {
                    $newTemplates[] = $template;
                }
            }

            foreach ($templateCategories as $templateCategory)
            {
                $templates = $this->MTemplates->all($templateCategory['templates_categories_id']);

                if ($templates)
                {
                    $sendToBrowser[] = $templateCategory;

                    foreach ($templates as $template)
                    {
                        $newTemplates[] = $template;
                    }
                }
            }

            $this->data['templateCategories'] = $sendToBrowser;
            $this->data['templates'] = $newTemplates;
        }
        else
        {
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            if (json_decode($package['templates']) != NULL)
            {
                // Get the template categories
                $templateCategories = $this->MTemplates->getCategories();
                $sendToBrowser = [];
                $newTemplates = [];

                $noCat = $this->MTemplates->allWithNoCategory(json_decode($package['templates']));

                if ($noCat)
                {
                    $temp = [];
                    $temp['category_name'] = $this->lang->line('sites_index_no_template_cat');
                    $temp['templates_categories_id'] = 0;

                    $sendToBrowser[] = $temp;

                    foreach ($noCat as $template)
                    {
                        $newTemplates[] = $template;
                    }
                }

                foreach ($templateCategories as $templateCategory)
                {
                    $templates = $this->MTemplates->all($templateCategory['templates_categories_id'], json_decode($package['templates']));

                    if ($templates)
                    {
                        $sendToBrowser[] = $templateCategory;

                        foreach ($templates as $template)
                        {
                            $newTemplates[] = $template;
                        }
                    }
                }

                $this->data['templateCategories'] = $sendToBrowser;
                $this->data['templates'] = $newTemplates;
            }
        }

        die(json_encode($this->data));
    }


    public function getSites($page)
    {
        $page = $this->input->post('page');
        $this->data['title'] = $this->lang->line('sites_index_title');
        $this->data['content'] = 'sites';
        $this->data['page'] = 'site';
        /** Grab us some sites */
        $this->data['sites'] = $this->MSites->get_all('', $this->perPage, $page);
        /** Get all users */
        $this->data['users'] = $this->MUsers->get_all();
        $gateway = $this->MPayments->get_by_name('payment_gateway');
        $this->data['packages'] = $this->MPackages->get_all($gateway[0]->value, true);
        $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
        $sites = $this->MSites->site_by_user($this->session->userdata('user_id'));

        if (count($package) > 0)
        {
            $user_sites = count($sites);
            $package_sites = (int)$package['sites_number'];
            if ($user_sites > 0)
            {
                /** User's site is more or equal to its package number */
                if ($user_sites >= $package_sites)
                {
                    $this->data['site_limitation'] = $this->lang->line('sites_index_reach_site_number');
                }
                else if ($user_sites + 2 >= $package_sites)
                {
                    $this->data['site_limitation'] = $this->lang->line('sites_index_almost_reach_site_number');
                }
            }
        }

        if ($this->session->userdata('user_type') == 'Admin')
        {
            // Get the template categories
            $templateCategories = $this->MTemplates->getCategories();
            $sendToBrowser = [];
            $newTemplates = [];

            $noCat = $this->MTemplates->allWithNoCategory();

            if ($noCat)
            {
                $temp = [];
                $temp['category_name'] = $this->lang->line('sites_index_no_template_cat');
                $temp['templates_categories_id'] = 0;

                $sendToBrowser[] = $temp;

                foreach ($noCat as $template)
                {
                    $newTemplates[] = $template;
                }
            }

            foreach ($templateCategories as $templateCategory)
            {
                $templates = $this->MTemplates->all($templateCategory['templates_categories_id']);

                if ($templates)
                {
                    $sendToBrowser[] = $templateCategory;

                    foreach ($templates as $template)
                    {
                        $newTemplates[] = $template;
                    }
                }
            }

            $this->data['templateCategories'] = $sendToBrowser;
            $this->data['templates'] = $newTemplates;
        }
        else
        {
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            if (json_decode($package['templates']) != NULL)
            {
                // Get the template categories
                $templateCategories = $this->MTemplates->getCategories();
                $sendToBrowser = [];
                $newTemplates = [];

                $noCat = $this->MTemplates->allWithNoCategory(json_decode($package['templates']));

                if ($noCat)
                {
                    $temp = [];
                    $temp['category_name'] = $this->lang->line('sites_index_no_template_cat');
                    $temp['templates_categories_id'] = 0;

                    $sendToBrowser[] = $temp;

                    foreach ($noCat as $template)
                    {
                        $newTemplates[] = $template;
                    }
                }

                foreach ($templateCategories as $templateCategory)
                {
                    $templates = $this->MTemplates->all($templateCategory['templates_categories_id'], json_decode($package['templates']));

                    if ($templates)
                    {
                        $sendToBrowser[] = $templateCategory;

                        foreach ($templates as $template)
                        {
                            $newTemplates[] = $template;
                        }
                    }
                }

                $this->data['templateCategories'] = $sendToBrowser;
                $this->data['templates'] = $newTemplates;
            }
        }

        return $this->data;

//        die(json_encode($this->data));
    }

}
