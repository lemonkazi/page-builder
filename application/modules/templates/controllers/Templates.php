<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends MY_Controller {

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
            'package/Packages_model' => 'MPackages',
            'settings/Payment_settings_model' => 'MPayments',
            'sites/Blocks_fav_model' => 'MBlocksFav',
            'sites/Frames_model' => 'MFrames',
            'sites/Sites_model' => 'MSites',
            'sites/Pages_model' => 'MPages',
            'shared/Revision_model' => 'MRevisions',
            'shared/Ftp_model' => 'MFtp',
            'templates/Templates_model' => 'MTemplates',
            'settings/Whitelabel_model' => 'MWhitelabel'
        ];
        $this->load->model($model_list);

        if ( ! $this->session->has_userdata('user_id') )
        {
            redirect('auth', 'refresh');
        }

        $this->hooks = load_class('Hooks', 'core');
        $this->data = [];
        $this->data['whitelabel_general'] = $this->MWhitelabel->load();

        /** Hook point */
        $this->hooks->call_hook('templates_construct');
    }

    /**
     * Loads site's dashboard
     *
     * @return  void
     */
    public function index()
    {

        /** Hook point */
        $this->hooks->call_hook('sites_templates_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $this->data['title'] = $this->lang->line('templates_index_title');
        $this->data['content'] = 'templates';
        $this->data['page'] = 'templates';
        /** Grab us some sites */
        $this->data['templates'] = $this->MTemplates->all();

        $this->data['categories'] = $this->MTemplates->getCategories();

        /** Hook point */
        $this->hooks->call_hook('sites_templates_post');

        $this->load->view('shared/layout', $this->data);

    }

    /**
     * Ajax call to load the partial_templates view
     *
     * @param   integer     $siteID
     * @return  void
     */
    public function loadTemplatesPartial()
    {

        /** Hook point */
        $this->hooks->call_hook('templates_loadTemplatesPartial_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $this->data['templates'] = $this->MTemplates->all();

        $this->data['categories'] = $this->MTemplates->getCategories();

        /** Hook point */
        $this->hooks->call_hook('templates_loadTemplatesPartial_post');

        $this->load->view('partial_templates', $this->data);

    }


    /**
     * Get and retrieve single template
     *
     * @param   integer     $siteID
     * @return  void
     */
    public function template($templateID = false)
    {
        /** Hook point */
        $this->hooks->call_hook('templates_template_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $this->load->helper('thumb');

        $this->data = [];

        // Editing a template, we'll need a dummy site
        /** Store the session ID with this session */
        $this->session->set_userdata('templateID', $templateID);

        $siteData = [];
        $siteData['site'] = new stdClass();
        $siteData['site']->sites_id = 0;
        $siteData['site']->sites_name = $this->lang->line('sites_site_template_sitename');
        $siteData['site']->ftp_type = "";
        $siteData['site']->ftp_server = "";
        $siteData['site']->ftp_user = "";
        $siteData['site']->ftp_password = "";
        $siteData['site']->ftp_path = "\/";
        $siteData['site']->ftp_port = "21";
        $siteData['site']->ftp_ok = "0";

        $this->data['templateID'] = $templateID;

        $this->data['siteData'] = $siteData;

        /** Collect data for the image library */
        $userID = $this->session->userdata('user_id');;
        $userImages = $this->MUsers->getUserImages($userID);
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

        $this->data['categories'] = $this->MTemplates->getCategories();
        if ( $templateID ) $this->data['categoryID'] = $this->MTemplates->getTemplateCategory($templateID);

        $this->data['builder'] = TRUE;
        $this->data['page'] = "site_builder";
        $this->data['content'] = "sites/create";

        /** Hook point */
        $this->hooks->call_hook('templates_template_pre');

        $this->load->view('shared/layout', $this->data);
    }


    /**
     * creates new template
     *
     * @return  void
     */
    public function createTemplate()
    {

        /** Hook point */
        $this->hooks->call_hook('sites_createTemplate_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $page_id = $this->MTemplates->createNew();


        /** Hook point */
        $this->hooks->call_hook('sites_createTemplate_post');

        redirect('templates/' . $page_id, 'refresh');

    }


    /**
     * Ajax call to delete single template
     *
     * @return  void
     */
    public function del()
    {

        /** Hook point */
        $this->hooks->call_hook('sites_delTemplate_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }

        $this->return = [];

        if ( $this->input->post('templateID') && $this->input->post('templateID') !== '' && ctype_digit($this->input->post('templateID')) ) {

            // Delete the template
            $this->MPages->deleteTemplate(0, $this->input->post('templateID'));

            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->lang->line('sites_deltemplate_success');

        }
        else
        {

            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->lang->line('sites_deltemplate_error1');

        }


        /** Hook point */
        $this->hooks->call_hook('sites_delTemplate_post');

        die(json_encode($this->return));

    }


    /**
     * Saves page as a template for future use
     *
     * @return  json        $return
     */
    public function tsave()
    {
        /** Hook point */
        $this->hooks->call_hook('templates_tsave_pre');

    	$this->load->helper('response');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        /** Do we have some frames to save? */
        reset($_POST['pages']);
        $first_key = key($_POST['pages']);

        if ( !isset($_POST['pages'][$first_key]['blocks']) ) {

            $temp = array();
            $temp['header'] = $this->lang->line('sites_tsave_no_page_error_heading');
            $temp['content'] = $this->lang->line('sites_tsave_no_page_error_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

            die(json_encode($this->return));

        }

        $catID = ( $this->input->post('categoryID') !== null )? $this->input->post('categoryID') : 0;

        $templateID = $this->MPages->saveTemplate($_POST['pages'], $_POST['fullPage'], $_POST['templateID'], $catID);

        // $this->return = array();

        /** All good */
        if ($templateID)
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_tsave_template_save_success_heading');
            $temp['content'] = $this->lang->line('sites_tsave_template_save_success_message');

            $this->return = array();
            $this->return['responseCode'] = 1;
            $this->return['templateID'] = $templateID;
            $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

			sendResponse(json_encode($this->return));

			//All good, making thumb of the template
			$screenshotUrl = base_url() . 'loadsinglepage/' . $templateID;
            $filename = 'templatethumb_' . $templateID . '.jpg';
            $this->load->library('screenshot_library');
            $screenshot = $this->screenshot_library->make_screenshot($screenshotUrl, $filename, '1200x900', $this->config->item('screenshot_sitethumbs_folder'), 'page', 'true');

            if ($screenshot)
            {
                $data = array(
                    'pagethumb' => $this->config->item('screenshot_sitethumbs_folder') . $screenshot . "?" . time()
                );
				$this->MPages->update_fields($templateID, $data);
            }
			/** Hook point */
			$this->hooks->call_hook('templates_tsave_post');
			die();
        }
        /** Not good */
        else
        {
            $temp = array();
            $temp['header'] = $this->lang->line('sites_tsave_template_save_fail_heading');
            $temp['content'] = $this->lang->line('sites_tsave_template_save_fail_message');

            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
			/** Hook point */
			$this->hooks->call_hook('templates_tsave_post');
			die(json_encode($this->return));
        }

    }


    /**
     * Ajax call to create a new template category
     *
     * @return  void
     */
    public function addCategory()
    {

        /** Hook point */
        $this->hooks->call_hook('templates_addCategory_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $this->return = [];

        if ($this->input->post('catname'))
        {

            if ($this->MTemplates->catNameIsUnique($this->input->post('catname')))
            {

                $this->MTemplates->addCategory($this->input->post('catname'));

                /** Return tbody with all categories */
                $this->data['categories'] = $this->MTemplates->allCategories();

                /** Hook point */
                $this->hooks->call_hook('templates_addCategory_success');

                $this->data['return']['responseCode'] = 1;
                $this->data['return']['responseHTML'] = $this->lang->line('templates_newcat_success');
                $this->data['return']['categories'] = $this->load->view('templates/catstbody', $this->data, true);

            }
            else
            {
                /** Hook point */
                $this->hooks->call_hook('templates_addCategory_error_duplicatename');

                $this->data['return']['responseCode'] = 0;
                $this->data['return']['responseHTML'] = $this->lang->line('templates_newcat_error2');
            }

        }
        else
        {

            /** Hook point */
            $this->hooks->call_hook('templates_addCategory_error_missingdata');

            $this->data['return']['responseCode'] = 0;
            $this->data['return']['responseHTML'] = $this->lang->line('templates_newcat_error1');

        }

        die(json_encode($this->data['return']));

        /** Hook point */
        $this->hooks->call_hook('templates_addCategory_post');

    }

    /**
     * Ajax call: loads the template category edit modal markup
     *
     * @return  void
     */
    public function loadDeleteCatModal()
    {

        /** Hook point */
        $this->hooks->call_hook('templates_loadDeleteCatModal_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $this->data['categories'] = $this->MTemplates->allCategories();
        $this->data['catID'] = $this->input->get('catID');

        $this->data['return']['markup'] = $this->load->view('templates/modal_deletecategory', $this->data, true);

        /** Hook point */
        $this->hooks->call_hook('templates_loadDeleteCatModal_post');

        die(json_encode($this->data['return']));

    }

    /**
     * Ajax call: deletes template category
     *
     * @return  void
     */
    public function removeCategory()
    {

        /** Hook point */
        $this->hooks->call_hook('templates_removeCategory_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        $this->data['return'] = [];

        if ($this->input->post('catID') !== null && $this->input->post('replaceWith') !== null)
        {
            $this->MTemplates->removeCategory($this->input->post('catID'), $this->input->post('replaceWith'));

            $this->data['return']['responseCode'] = 1;
            $this->data['return']['responseHTML'] = $this->lang->line('templates_deletecat_success');
        }

        /** Hook point */
        $this->hooks->call_hook('templates_removeCategory_post');

        die(json_encode($this->data['return']));

    }

    /**
     * Ajax call: updates an existing category name
     *
     * @return  void
     */
    public function updateCategory()
    {

        /** Hook point */
        $this->hooks->call_hook('templates_updateCategory_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }
        if ($this->input->post('catname') && $this->input->post('catid'))
        {
            $this->MTemplates->updateCategory($this->input->post('catname'), $this->input->post('catid'));

            /** Hook point */
            $this->hooks->call_hook('templates_updateCategory_success');

            $this->data['return']['responseCode'] = 1;
            $this->data['return']['responseHTML'] = $this->lang->line('templates_updateCategory_success');

        }
        else
        {
            /** Hook point */
            $this->hooks->call_hook('templates_uupdateCategory_error_missingdata');

            $this->data['return']['responseCode'] = 0;
            $this->data['return']['responseHTML'] = $this->lang->line('templates_updateCategory_error1');

        }

        /** Hook point */
        $this->hooks->call_hook('templates_updateCategory_post');

        die(json_encode($this->data['return']));

    }

    /**
     * Ajax call: changes category for template
     *
     * @return  void
     */
    public function setCatForTemplate()
    {

        /** Hook point */
        $this->hooks->call_hook('templates_setCatForTemplate_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            redirect('sites', 'refresh');
        }

        $this->return = [];

        if ( $this->input->post('templateID') != null && $this->input->post('categoryID') != null && ctype_digit($this->input->post('templateID')) && ctype_digit($this->input->post('categoryID')) )
        {
            // All good

            $this->MTemplates->setCatForTemplate($this->input->post('templateID'), $this->input->post('categoryID'));

            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->lang->line('templates_setcategory_success');

        }
        else
        {
            // We've got issues
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->lang->line('templates_setcategory_error1');
        }

        /** Hook point */
        $this->hooks->call_hook('templates_setCatForTemplate_post');

        die(json_encode($this->return));

    }

    /**
     * Ajax call: loads data for a single page template
     *
     * @param   integer     $templateID
     * @return  void
     */
    public function templateData($templateID = false)
    {

        /** Hook point */
        $this->hooks->call_hook('templates_templateData_pre');

        $this->return = [];

        if ( !$templateID || $templateID == '' )
        {
            // We've got issues
            $this->return['responseCode'] = 0;
            $this->return['content'] = $this->lang->line('templates_templatedata_error1');
        }
        else
        {
            $templateData = $this->MTemplates->single($templateID);

            if ( !$templateData )
            {
                $this->return['responseCode'] = 0;
                $this->return['content'] = $this->lang->line('templates_templatedata_error2') . $templateID;
            }
            else
            {
                $this->return['responseCode'] = 1;
                $this->return['content'] = $this->lang->line('templates_templatedata_success');
                $this->return['templateData'] = $templateData;
            }

        }

        /** Hook point */
        $this->hooks->call_hook('templates_templateData_post');

        die(json_encode($this->return));
    }


}
