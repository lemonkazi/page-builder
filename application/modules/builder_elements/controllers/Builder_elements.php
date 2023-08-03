<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Builder_elements extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, check if user has right to access this class, loads the hook class and add a hook point
     *
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();

		$model_list = [
			'builder_elements/Blocks_model' => 'MBlocks',
			'builder_elements/Components_model' => 'MComponents',
			'settings/Whitelabel_model' => 'MWhitelabel',
			'package/Packages_model' => 'MPackages'
		];
		$this->load->model($model_list);

		if ( ! $this->session->has_userdata('user_id'))
		{
			redirect('auth', 'refresh');
		}

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];
		$this->data['whitelabel_general'] = $this->MWhitelabel->load();

		/** Hook point */
		$this->hooks->call_hook('builder_elements_construct');
	}

	/**
	 * Loads and outputs all blocks and elements used by the page builder
	 *
	 * @return 	void
	 */
	public function loadAll()
	{
		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadall_pre');

		$this->data['return'] = [];

		// Blocks
		if ($this->session->userdata('user_type') == 'Admin') 
		{
			$this->data['blocks'] = $this->MBlocks->all();
			$this->data['return']['elements'] = $this->data['blocks'];
		}
		else
		{
			$package = $this->MPackages->get_by_id($this->session->userdata('package_id'));

			if ( $package['blocks'] === null)
			{ // Block restriction not activated, show all
				$this->data['blocks'] = $this->MBlocks->all();
				$this->data['return']['elements'] = $this->data['blocks'];
			}
			else if ( $package['blocks'] == '[]' )
			{ // Block restriction activated, no blocks selected

			}
			else
			{ // Block restriction activated, certain categories only
				$this->data['blocks'] = $this->MBlocks->all( json_decode($package['blocks'], true) );
				$this->data['return']['elements'] = $this->data['blocks'];
			}
		}

		// Components
		$this->data['components'] = $this->MComponents->all();
		$this->data['return']['components'] = $this->data['components'];

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadall_post');

		die(json_encode($this->data['return'] ));
	}

	/**
	 * Loads the blocks admin panel page
	 *
	 * @return 	void
	 */
	public function blocks()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_blocks_pre');

		$this->data['page'] = "elements_blocks";
		$this->data['title'] = $this->lang->line('builder_elements_blocks_pagetitle');

		$this->data['blocks'] = $this->MBlocks->allBasic();
		$this->data['blockCategories'] = $this->MBlocks->allBlockCategories();

		$this->data['templates'] = $this->MBlocks->loadTemplateFiles();

		/** Hook point */
		$this->hooks->call_hook('builder_elements_blocks_post');

		$this->load->view('builder_elements/blocks', $this->data);
	}

	/**
	 * Loads the elements admin panel page
	 *
	 * @return 	void
	 */
	public function components()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_components_pre');

		$this->data['page'] = "elements_components";
		$this->data['title'] = $this->lang->line('builder_elements_components_pagetitle');

		$this->data['components'] = $this->MComponents->allBasic();
		$this->data['componentsCategories'] = $this->MComponents->allComponentCategories();

		/** Hook point */
		$this->hooks->call_hook('builder_elements_components_post');

		$this->load->view('builder_elements/components', $this->data);
	}


	/**
	 * Loads the elements browser
	 *
	 * @return 	void
	 */
	public function browser()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_browser_pre');

		$this->data['page'] = "elements_browser";
		//$this->data['title'] = $this->lang->line('builder_elements_components_pagetitle');

		/** Hook point */
		$this->hooks->call_hook('builder_elements_browser_post');

		$this->load->view('builder_elements/browser', $this->data);
	}


	public function scan()
	{

		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_scan_pre');

		$this->load->helper('folder');

		$this->data = [];

		$this->data['dir'] = "elements";

		// Run the recursive function 
		$this->data['response'] = scan($this->data['dir']);

		$this->data['return'] = array(
			"name" => "elements",
			"type" => "folder",
			"path" => $this->data['dir'],
			"items" => $this->data['response']
		);

		header('Content-type: application/json');

		/** Hook point */
		$this->hooks->call_hook('builder_elements_scan_post');

		echo json_encode($this->data['return']);

	}


	/**
	 * Ajax call: Loads single component data from server
	 *
	 * @return 	void
	 */
	public function loadComponent($componentID)
	{
		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadComponent_pre');

		$forTemplate['component'] = $this->MComponents->loadComponent($componentID);
		$forTemplate['componentCategories'] = $this->MComponents->allComponentCategories();

		$this->data['forTemplate'] = $forTemplate;

		$this->data['return']['markup'] = $this->load->view('builder_elements/partial_componentdetails', $this->data, true);

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadComponent_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: Loads single block data from server
	 *
	 * @return 	void
	 */
	public function loadBlock($blockID)
	{
		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadBlock_pre');

		$forTemplate['block'] = $this->MBlocks->loadBlock($blockID);
		$forTemplate['blockCategories'] = $this->MBlocks->allBlockCategories();
		$forTemplate['templates'] = $this->data['templates'] = $this->MBlocks->loadTemplateFiles();

		$this->data['forTemplate'] = $forTemplate;

		$this->data['return']['markup'] = $this->load->view('builder_elements/partial_blockdetails', $this->data, true);

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadBlock_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: adds a new component category
	 *
	 * @return 	void
	 */
	public function addComponentCategory()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addComponentCategory_pre');

		if ($this->input->post('catname'))
		{
			/** Unique category name? */
			if ($this->MComponents->componentCatNameIsUnique($this->input->post('catname')))
			{
				$this->MComponents->addCategory($this->input->post('catname'));

				/** return tbody with all categories */
				$this->data['componentsCategories'] = $this->MComponents->allComponentCategories();

				/** Hook point */
				$this->hooks->call_hook('builder_elements_addComponentCategory_success');

				$this->data['return']['responseCode'] = 1;
				$this->data['return']['response'] = $this->load->view('builder_elements/componentstbody', $this->data, true);
			}
			/** Not unique */
			else
			{
				$this->data['data']['header'] = $this->lang->line('builder_elements_newccat_error_heading');
				$this->data['data']['content'] = $this->lang->line('builder_elements_newccat_error_content2');

				/** Hook point */
				$this->hooks->call_hook('builder_elements_addComponentCategory_error_notunique');

				$this->data['return']['responseCode'] = 0;
				$this->data['return']['response'] = $this->load->view('shared/error', $this->data, true);
			}
		}
		else
		{
			$this->data['data']['header'] = $this->lang->line('builder_elements_newccat_error_heading');
			$this->data['data']['content'] = $this->lang->line('builder_elements_newccat_error_content');

			/** Hook point */
			$this->hooks->call_hook('builder_elements_addComponentCategory_error_missingdata');

			$this->data['return']['responseCode'] = 0;
			$this->data['return']['response'] = $this->load->view('shared/error', $this->data, true);
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addComponentCategory_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: adds a new block category
	 *
	 * @return 	void
	 */
	public function addBlockCategory()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addBlockCategory_pre');

		if ($this->input->post('catname'))
		{
			/** Unique category name? */
			if ($this->MBlocks->blockCatNameIsUnique($this->input->post('catname')))
			{
				$this->MBlocks->addCategory($this->input->post('catname'));

				/** Return tbody with all categories */
				$this->data['blockCategories'] = $this->MBlocks->allBlockCategories();

				/** Hook point */
				$this->hooks->call_hook('builder_elements_addBlockCategory_success');

				$this->data['return']['responseCode'] = 1;
				$this->data['return']['response'] = $this->load->view('builder_elements/blockstbody', $this->data, true);
			}
			/** Not unique */
			else
			{
				$this->data['data']['header'] = $this->lang->line('builder_elements_newcat_error_heading');
				$this->data['data']['content'] = $this->lang->line('builder_elements_newcat_error_content2');

				/** Hook point */
				$this->hooks->call_hook('builder_elements_addBlockCategory_error_notunique');

				$this->data['return']['responseCode'] = 0;
				$this->data['return']['response'] = $this->load->view('shared/error', $this->data, true);
			}
		}
		else
		{
			$this->data['data']['header'] = $this->lang->line('builder_elements_newcat_error_heading');
			$this->data['data']['content'] = $this->lang->line('builder_elements_newcat_error_content');

			/** Hook point */
			$this->hooks->call_hook('builder_elements_addBlockCategory_error_missingdata');

			$this->data['return']['responseCode'] = 0;
			$this->data['return']['response'] = $this->load->view('shared/error', $this->data, true);
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addBlockCategory_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: updates an existing component category name
	 *
	 * @return 	void
	 */
	public function updateComCategory()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_updateComCategory_pre');

		if ($this->input->post('catname') && $this->input->post('catid'))
		{
			$this->MComponents->updateCategory($this->input->post('catname'), $this->input->post('catid'));

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateComCategory_success');

			$this->data['return']['responseCode'] = 1;
		}
		else
		{
			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateComCategory_error_missingdata');

			$this->data['return']['responseCode'] = 0;
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_updateComCategory_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: updates an existing category name
	 *
	 * @return 	void
	 */
	public function updateCategory()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_updateCategory_pre');

		if ($this->input->post('catname') && $this->input->post('catid'))
		{
			$this->MBlocks->updateCategory($this->input->post('catname'), $this->input->post('catid'));

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateCategory_success');

			$this->data['return']['responseCode'] = 1;
		}
		else
		{
			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateCategory_error_missingdata');

			$this->data['return']['responseCode'] = 1;
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_updateCategory_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: loads the component category edit modal markup
	 *
	 * @return 	void
	 */
	public function loadDeleteComCatModal()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadDeleteComponentCatModal_pre');

		$this->data['componentCategories'] = $this->MComponents->allComponentCategories();
		$this->data['catID'] = $this->input->get('catID');

		$this->data['return']['markup'] = $this->load->view('builder_elements/modal_deletecomcategory', $this->data, true);

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadDeleteBlockCatModal_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: loads the block category edit modal markup
	 *
	 * @return 	void
	 */
	public function loadDeleteBlockCatModal()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadDeleteBlockCatModal_pre');

		$this->data['blockCategories'] = $this->MBlocks->allBlockCategories();
		$this->data['catID'] = $this->input->get('catID');

		$this->data['return']['markup'] = $this->load->view('builder_elements/modal_deleteblockcategory', $this->data, true);

		/** Hook point */
		$this->hooks->call_hook('builder_elements_loadDeleteBlockCatModal_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: deletes component category
	 *
	 * @return 	void
	 */
	public function removeComCategory ()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_removeComCategory_pre');

		$this->data['return'] = [];

		if ($this->input->post('catID') && $this->input->post('replaceWith'))
		{
			$this->MComponents->removeCategory($this->input->post('catID'), $this->input->post('replaceWith'));

			$this->data['return']['responseCode'] = 1;
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_removeComCategory_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: deletes block category
	 *
	 * @return 	void
	 */
	public function removeCategory ()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_removeCategory_pre');

		$this->data['return'] = [];

		if ($this->input->post('catID') && $this->input->post('replaceWith'))
		{
			$this->MBlocks->removeCategory($this->input->post('catID'), $this->input->post('replaceWith'));

			$this->data['return']['responseCode'] = 1;
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_removeCategory_post');

		die(json_encode($this->data['return']));
	}

	/**
	 * Ajax call: creates a new component
	 *
	 * @return 	void
	 */
	public function addComponent()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addComponent_pre');

		$this->form_validation->set_rules('componentCategory', 'Component category ID', 'required');
		$this->form_validation->set_rules('componentMarkup', 'Component markup', 'required');

		/** All not good */
		if ($this->form_validation->run() == FALSE)
		{
			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_addcomponent_validation_error_heading');
			$temp['content'] = $this->lang->line('builder_elements_addcomponent_validation_error_message') . validation_errors();

			$this->return = array();
			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_addComponent_error_formvalidation');

			echo json_encode($this->return);
		}
		else
		{
			$this->data['componentDetails'] = [];
			$this->data['componentDetails']['components_category'] = $this->input->post('componentCategory');
			$this->data['componentDetails']['components_markup'] = $this->input->post('componentMarkup');

			/** Uploaded image? */
			if (isset($_FILES['componentThumbnail']) && $_FILES['componentThumbnail']['name'] != '')
			{
				$config = $this->config->item('component_thumbnail_upload_config');

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('componentThumbnail'))
				{
					$temp = array();
					$temp['header'] = $this->lang->line('builder_elements_upload_error_heading');
					$temp['content'] = $this->lang->line('builder_elements_upload_error_message') . $this->upload->display_errors();

					$this->return = array();
					$this->return['responseCode'] = 0;
					$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

					/** Hook point */
					$this->hooks->call_hook('builder_elements_addComponent_error_fileissues');

					die(json_encode($this->return));
				}
				else
				{
					$fileData = $this->upload->data();

					$this->data['componentDetails']['components_thumb'] = str_replace("./", "", $config['upload_path']) . "/" . $fileData['file_name'];

					/** Hook point */
					$this->hooks->call_hook('builder_elements_addComponent_fileok');
				}
			}

			$this->MComponents->addComponent($this->data['componentDetails']);

			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_addcomponent_complete_heading');
			$temp['content'] = $this->lang->line('builder_elements_addcomponent_complete_message');

			$this->return = array();
			$this->return['responseCode'] = 1;
			$this->return['responseHTML'] = $this->load->view('shared/info', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_addComponent_post');

			die(json_encode($this->return));
		}
	}

	/**
	 * Ajax call: creates a new block
	 *
	 * @return 	void
	 */
	public function addBlock()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addBlock_pre');

		$this->form_validation->set_rules('blockCategory', 'Block category ID', 'required');
		$this->form_validation->set_rules('blockUrl', 'Block template URL', 'required|valid_url');

		/** All not good */
		if ($this->form_validation->run() == FALSE)
		{
			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_addblock_validation_error_heading');
			$temp['content'] = $this->lang->line('builder_elements_addblock_validation_error_message') . validation_errors();

			$this->return = array();
			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_addblock_error_formvalidation');

			echo json_encode($this->return);
		}
		else
		{
			$this->data['blockDetails'] = [];
			$this->data['blockDetails']['blocks_category'] = $this->input->post('blockCategory');
			$this->data['blockDetails']['blocks_url'] = $this->input->post('blockUrl');

        	/** Make sure the supplied template URL is valid */
			$this->load->helper('urlcheck');

			$url = site_url($this->input->post('blockUrl'));

			if ( ! doesUrlLoad($url))
			{
				$temp = array();
				$temp['header'] = $this->lang->line('builder_elements_addblock_validation_error_heading');
				$temp['content'] = $this->lang->line('builder_elements_addblock_url_error_message');

				$this->return = array();
				$this->return['responseCode'] = 0;
				$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

				/** Hook point */
				$this->hooks->call_hook('builder_elements_updateBlock_error_formvalidation');

				die(json_encode($this->return));
			}

        	/** Full height checkbox */
			if ($this->input->post('blockFullHeight'))
			{
				$this->data['blockDetails']['blocks_height'] = "90vh";
			}
			else
			{
				$this->data['blockDetails']['blocks_height'] = "567";
			}

			$newBlockID = $this->MBlocks->addBlock($this->data['blockDetails']);

            /** Screenshot */
            if ( $this->input->post('blockHeight') && $this->input->post('blockHeight') != 0 )
            {

				$screenshotUrl = base_url($this->input->post('blockUrl'));
				$filename = 'block_' . $newBlockID . '.jpg';

				$this->load->library('screenshot_library');
				$screenshot = $this->screenshot_library->make_screenshot($screenshotUrl, $filename, '1200x0', $this->config->item('images_uploadDir') . "/", 'page', 'false');

				if ( $screenshot )
				{

					// resize the image
	                $config['source_image'] = $this->config->item('images_uploadDir') . "/" . $screenshot;
	                $config['width'] = 520;

	                $this->load->library('image_lib', $config);

	                $this->image_lib->resize();

				}

			}
			else
			{

				$screenshotUrl = base_url($this->input->post('blockUrl'));
				$filename = 'block_' . $newBlockID . '.jpg';

				$this->load->library('screenshot_library');
				$screenshot = $this->screenshot_library->make_screenshot($screenshotUrl, $filename, '1200x0', $this->config->item('images_uploadDir') . "/", 'page', 'false');

			}

			$this->data['blockDetails']['blocks_thumb'] = $this->config->item('images_uploadDir') . "/" . $screenshot;

			$this->MBlocks->updateBlock($newBlockID, $this->data['blockDetails']);

			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_addblock_complete_heading');
			$temp['content'] = $this->lang->line('builder_elements_addblock_complete_message');

			$this->return = array();
			$this->return['responseCode'] = 1;
			$this->return['responseHTML'] = $this->load->view('shared/info', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateBlock_post');

			die(json_encode($this->return));
		}
	}

	/**
	 * Ajax call: updates the details for a component
	 *
	 * @return 	void
	 */
	public function updateComponent()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_updateComponent_pre');

		$this->form_validation->set_rules('componentID', 'Component ID', 'required');
		$this->form_validation->set_rules('componentCategory', 'Component category ID', 'required');
		$this->form_validation->set_rules('componentMarkup', 'Component markup', 'required');

		/** All not good */
		if ($this->form_validation->run() == FALSE)
		{
			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_updatecomponent_validation_error_heading');
			$temp['content'] = $this->lang->line('builder_elements_updatecomponent_validation_error_message') . validation_errors();

			$this->return = array();
			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateComponent_error_formvalidation');

			echo json_encode($this->return);
		}
		/** All good */
		else
		{
			$this->data['componentDetails'] = [];
			$this->data['componentDetails']['components_category'] = $this->input->post('componentCategory');
			$this->data['componentDetails']['components_markup'] = $this->input->post('componentMarkup');

			/** Uploaded image? */
			if (isset($_FILES['componentThumbnail']) && $_FILES['componentThumbnail']['name'] != '')
			{
				$config = $this->config->item('component_thumbnail_upload_config');

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('componentThumbnail'))
				{
					$temp = array();
					$temp['header'] = $this->lang->line('builder_elements_upload_error_heading');
					$temp['content'] = $this->lang->line('builder_elements_upload_error_message') . $this->upload->display_errors();

					$this->return = array();
					$this->return['responseCode'] = 0;
					$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

					/** Hook point */
					$this->hooks->call_hook('builder_elements_updateComponent_error_fileissues');

					die(json_encode($this->return));
				}
				else
				{
					$fileData = $this->upload->data();

					$this->data['componentDetails']['components_thumb'] = str_replace("./", "", $config['upload_path']) . "/" . $fileData['file_name'];

					/** Hook point */
					$this->hooks->call_hook('builder_elements_updateComponent_fileok');
				}
			}

			$this->MComponents->updateComponent($this->input->post('componentID'), $this->data['componentDetails']);

			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_validation_complete_heading');
			$temp['content'] = $this->lang->line('builder_elements_validation_complete_message') . validation_errors();

			$forTemplate['info'] = $this->load->view('shared/info', array('data'=>$temp), TRUE);

			$forTemplate['component'] = $this->MComponents->loadComponent($this->input->post('componentID'));
			$forTemplate['componentCategories'] = $this->MComponents->allComponentCategories();

			$this->data['forTemplate'] = $forTemplate;

			$this->data['return']['responseCode'] = 1;
			$this->data['return']['responseHTML'] = $this->load->view('builder_elements/partial_componentdetails', $this->data, true);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateComponent_post');

			echo json_encode($this->data['return']);
		}
	}

	/**
	 * Ajax call: updates the details for a block
	 *
	 * @return 	void
	 */
	public function updateBlock()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_updateBlock_pre');

		$this->form_validation->set_rules('blockID', 'Block ID', 'required');
		$this->form_validation->set_rules('blockCategory', 'Block category ID', 'required');
		$this->form_validation->set_rules('blockUrl', 'Block template URL', 'required|valid_url');

		/** All not good */
		if ($this->form_validation->run() == FALSE)
		{
			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_updateblock_validation_error_heading');
			$temp['content'] = $this->lang->line('builder_elements_updateblock_validation_error_message') . validation_errors();

			$this->return = array();
			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateBlock_error_formvalidation');

			echo json_encode($this->return);
		}
		/** All good */
		else
		{
			$this->data['blockDetails'] = [];
			$this->data['blockDetails']['blocks_category'] = $this->input->post('blockCategory');
			$this->data['blockDetails']['blocks_url'] = $this->input->post('blockUrl');

			/** Uploaded image? */
			if (isset($_FILES['blockThumbnail']) && $_FILES['blockThumbnail']['name'] != '')
			{
				$config = $this->config->item('block_thumbnail_upload_config');

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('blockThumbnail'))
				{
					$temp = array();
					$temp['header'] = $this->lang->line('builder_elements_upload_error_heading');
					$temp['content'] = $this->lang->line('builder_elements_upload_error_message') . $this->upload->display_errors();

					$this->return = array();
					$this->return['responseCode'] = 0;
					$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

					/** Hook point */
					$this->hooks->call_hook('builder_elements_updateBlock_error_fileissues');

					die(json_encode($this->return));
				}
				else
				{
					$fileData = $this->upload->data();

					$this->data['blockDetails']['blocks_thumb'] = str_replace("./", "", $config['upload_path']) . "/" . $fileData['file_name'];

					/** Hook point */
					$this->hooks->call_hook('builder_elements_updateBlock_fileok');
				}
			}

        	/** Full height checkbox */
			if ( $this->input->post('blockFullHeight'))
			{
				$this->data['blockDetails']['blocks_height'] = "90vh";
			}
			else
			{
				$this->data['blockDetails']['blocks_height'] = "567";
			}

            /** Screenshot */
            if ( $this->input->post('remakeThumb') && $this->input->post('remakeThumb') == 'check') {

				$screenshotUrl = base_url($this->input->post('blockUrl'));
				$filename = 'block_' . $this->input->post('blockID') . '.jpg';

				$this->load->library('screenshot_library');
				$screenshot = $this->screenshot_library->make_screenshot($screenshotUrl, $filename, '1200x0', $this->config->item('images_uploadDir') . "/", 'page', 'false');

				if ( $screenshot )
				{

					// resize the image
	                $config['source_image'] = $this->config->item('images_uploadDir') . "/" . $screenshot;
	                $config['width'] = 520;

	                $this->load->library('image_lib', $config);

	                $this->image_lib->resize();

				}

				$this->data['blockDetails']['blocks_thumb'] = $this->config->item('images_uploadDir') . "/" . $screenshot;

			}

			$this->MBlocks->updateBlock($this->input->post('blockID'), $this->data['blockDetails']);


			$temp = array();
			$temp['header'] = $this->lang->line('builder_elements_validation_complete_heading');
			$temp['content'] = $this->lang->line('builder_elements_validation_complete_message') . validation_errors();

			$forTemplate['info'] = $this->load->view('shared/info', array('data'=>$temp), TRUE);

			$this->load->helper('urlcheck');

			$forTemplate['block'] = $this->MBlocks->loadBlock($this->input->post('blockID'));
			$forTemplate['blockCategories'] = $this->MBlocks->allBlockCategories();
			$forTemplate['templates'] = $this->data['templates'] = $this->MBlocks->loadTemplateFiles();

			$this->data['forTemplate'] = $forTemplate;

			$this->data['return']['responseCode'] = 1;
			$this->data['return']['responseHTML'] = $this->load->view('builder_elements/partial_blockdetails', $this->data, true);

			/** Hook point */
			$this->hooks->call_hook('builder_elements_updateBlock_post');

			echo json_encode($this->data['return']);
		}
	}

	/**
	 * Ajax call: deletes a block
	 *
	 * @return 	void
	 */
	public function deleteBlock($blockID)
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_deleteBlock_pre');

		$this->MBlocks->deleteBlock($blockID);

		$this->return = array();

		/** Successfully deleted */
		if ( ! $this->MBlocks->loadBlock($blockID))
		{
			$this->return['responseCode'] = 1;
		}
		/** Block stil exists, seomthing went wrong */
		else
		{
			$temp = array();
			$temp['header'] = "";
			$temp['content'] = $this->lang->line('builder_elements_deleteblock_error_message');

			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_deleteBlock_post');

		die(json_encode($this->return));
	}

	/**
	 * Ajax call: deletes a component
	 *
	 * @return 	void
	 */
	public function deleteComponent($componentID)
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_deleteComponent_pre');

		$this->MComponents->deleteComponent($componentID);

		/** Successfully deleted */
		if ( ! $this->MComponents->loadComponent($componentID))
		{

			$this->return['responseCode'] = 1;

		}
		/** Comopnent stil exists, seomthing went wrong */
		else
		{
			$temp = array();
			$temp['header'] = "";
			$temp['content'] = $this->lang->line('builder_elements_deletecomponent_error_message');

			$this->return = array();
			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_deleteComponent_post');

		die(json_encode($this->return));
	}

	/**
	 * Ajax call: loads the block category dropdown for the add block modal
	 *
	 * @return 	void
	 */
	public function catDropdown()
	{
		if ($this->session->userdata('user_type') != "Admin")
		{
			show_404();
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_catDropdown_pre');

		$this->data['blockCategories'] = $this->MBlocks->allBlockCategories();

		/** Hook point */
		$this->hooks->call_hook('builder_elements_catDropdown_post');

		$this->load->view('builder_elements/partial_blockcatdropdown', $this->data);
	}

	/**
	 * Loads the source code editor allowing the user to edit it's markup
	 *
	 * @return 	void
	 */
	public function editBlock($blockID)
	{
		
		/** Hook point */
		$this->hooks->call_hook('builder_elements_editBlock_pre');

		$this->data['block'] = $this->MBlocks->loadBlock($blockID);
		$this->data['file'] = $this->data['block']['blocks_url'];


		/** Hook point */
		$this->hooks->call_hook('builder_elements_editBlock_post');

		redirect('file_editor/open?file=' . urlencode($this->data['file']));

	}

	/**
	 * Ajax call to upload a file to the server side file system
	 *
	 * @return 	void
	 */
	public function upload()
	{

		/** Hook point */
		$this->hooks->call_hook('builder_elements_upload_pre');


		$this->return = [];


		if (isset($_FILES['inputBrowserFile']) && $_FILES['inputBrowserFile']['name'] != '')
		{
			$config = $this->config->item('browser_upload_config');
			$config['upload_path'] = './' . $this->input->post('inputPath');

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('inputBrowserFile'))
			{

				$this->return['responseCode'] = 0;
				$this->return['responseHTML'] = $this->lang->line('builder_elements_upload_error_message') . $this->upload->display_errors();

				/** Hook point */
				$this->hooks->call_hook('builder_elements_upload_error_fileissues');

				die(json_encode($this->return));
			}
			else
			{
				/** Hook point */
				$this->hooks->call_hook('builder_elements_upload_fileok');

				$this->return['responseCode'] = 1;
				$this->return['responseHTML'] = $this->lang->line('builder_elements_upload_success_content');

				die(json_encode($this->return));

			}
		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_upload_post');

	}


	/**
	 * Ajax call to add a new folder the server side file system
	 *
	 * @return 	void
	 */
	public function addFolder()
	{

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addFolder_pre');

		$this->form_validation->set_rules('folder', 'File or folder name', 'required|regex_match[/^[a-z0-9\.]+$/]');
		$this->form_validation->set_rules('path', 'Path', 'required');

		$this->return = array();

		/** All not good */
		if ($this->form_validation->run() == FALSE)
		{

			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->lang->line('builder_elements_add_folder_error') . validation_errors();

			/** Hook point */
			$this->hooks->call_hook('builder_elements_addFolder_error_formvalidation');

		}
		else
		{ // Good

			if ( !file_exists($this->input->post('path') . "/" . $this->input->post('folder')) ) 
			{ // make sure this does not yet exist

				// folder or file?
				$temp = explode(".", $this->input->post('folder'));

				if ( count($temp) == 2 ) 
				{ // File

					if ( $file = fopen($this->input->post('path') . "/" . $this->input->post('folder'), 'w') )
					{ // good

						fclose($file);
						$this->return['responseCode'] = 1;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_add_folder_success');

					}
					else
					{ // trouble

						$this->return['responseCode'] = 0;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_add_folder_error3');

					}

				}
				else
				{ // Folder

					if ( mkdir($this->input->post('path') . "/" . $this->input->post('folder')) ) 
					{ // try creating

						$this->return['responseCode'] = 1;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_add_folder_success');

					}
					else
					{  // Cant create

						$this->return['responseCode'] = 0;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_add_folder_error3');

					}

				}

			}
			else
			{ // Already exist

				$this->return['responseCode'] = 0;
				$this->return['responseHTML'] = $this->lang->line('builder_elements_add_folder_error2');

			}

		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_addFolder_post');

		echo json_encode($this->return);

	}


	/**
	 * Ajax call to remove a file from the server side file system
	 *
	 * @return 	void
	 */
	public function delFile()
	{

		/** Hook point */
		$this->hooks->call_hook('builder_elements_delFile_pre');

		$this->return = [];

		if ( $this->input->post('url') && $this->input->post('url') !== '' ) 
		{

			if ( file_exists($this->input->post('url')) )
			{

				// File or Folder
				if ( is_dir($this->input->post('url')) ) 
				{ // Folder 

					$this->load->helper('file');

					if ( delete_files("./" . $this->input->post('url'), true) )
					{ //  All good

						rmdir($this->input->post('url'));

						$this->return['responseCode'] = 1;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_del_folder_success');
					}
					else
					{ //  Something went wrong
						$this->return['responseCode'] = 0;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_del_folder_misc_error');
					}

				}
				else
				{ // File

					if ( unlink($this->input->post('url')) )
					{
						
						$this->return['responseCode'] = 1;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_del_file_success');

					}
					else
					{

						$this->return['responseCode'] = 0;
						$this->return['responseHTML'] = $this->lang->line('builder_elements_del_file_no_such_file');

					}

				}

			}
			else
			{

				$this->return['responseCode'] = 0;
				$this->return['responseHTML'] = $this->lang->line('builder_elements_del_file_no_such_file');

			}

		}
		else
		{

			$this->return['responseCode'] = 0;
			$this->return['responseHTML'] = $this->lang->line('builder_elements_del_file_data_missing');

		}

		/** Hook point */
		$this->hooks->call_hook('builder_elements_delFile_post');

		die(json_encode($this->return));

	}

	/**
	 * Get the number of pages available for that user
	 *
	 * @return 	void
	 */
	public function availablePages()
	{
		/** Hook point */
		$this->hooks->call_hook('builder_elements_pagesAvailable_pre');

		$this->data['return'] = [];
		if ($this->session->userdata('user_type') == 'Admin') 
		{	//No limits for admin users
			// -1 is Unlimited
			$this->data['return']['available_pages'] = -1;
		}
		else
		{
			$package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
			$availablePages = (int)$package['pages_number'];
			if ($availablePages === null || $availablePages === 0)
			{   // No pages limit set
				// -1 is Unlimited
				$this->data['return']['available_pages'] = -1;
			}
			else
			{	//number of pages available for that user			
				$this->data['return']['available_pages'] = $availablePages;
			}
		}
		/** Hook point */
		$this->hooks->call_hook('builder_elements_pagesAvailable_post');

		die(json_encode($this->data['return'] ));		
		
	}
	
	/**
	 * Function checks if the user can add a new page
	 *
	 * @return 	void
	 */
	public function addPage()
	{
		/** Hook point */
		$this->hooks->call_hook('builder_elements_numberOfPages_pre');

		$this->data['return'] = [];

		if ($this->session->userdata('user_type') == 'Admin') 
		{
			$this->data['return']['responseCode'] = 1;
			$this->data['return']['pages_left'] = -1;
		}
		else
		{
			$package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
			$pagesCount = (int)$this->input->post('pages_number');
			$availablePages = (int)$package['pages_number'];
			if ($availablePages === null || $availablePages === 0)
			{ // No pages limit
			  // -1 is Unlimited
				$this->data['return']['responseCode'] = 1;
				
				$this->data['return']['pages_left'] = -1;
			}
			else
			{
				if($availablePages > $pagesCount)
				{
					$this->data['return']['responseCode'] = 1;
					$pagesCount++;
					$this->data['return']['pages_left'] = $availablePages - $pagesCount;
					
				}
				else
				{
					$this->data['return']['responseCode'] = 0;
					$this->data['return']['pages_left'] = 0;					
					$this->data['return']['content'] =  $this->lang->line('builder_elements_add_page_error');
					
				}
			}
		}
		/** Hook point */
		$this->hooks->call_hook('builder_elements_numberOfPages_post');

		die(json_encode($this->data['return'] ));
	}
	
	/**
	 * Function checks if the user can edit source code
	 *
	 * @return 	void
	 */	
	public function canEditBlockSourceCode()
	{
		/** Hook point */
		$this->hooks->call_hook('builder_elements_canEditBlockSourceCode_pre');

		$this->data['return'] = [];

		if ($this->session->userdata('user_type') == 'Admin') 
		{
			$this->data['return']['responseCode'] = 1;
		}
		else
		{
			$package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
			$canEditSourceCode = $package['edit_sourcecode'];
			if ($canEditSourceCode == 'yes')
			{ // user can edit source code
				$this->data['return']['responseCode'] = 1;
			}
			else
			{
				$this->data['return']['responseCode'] = 0;
				$this->data['return']['content'] =  $this->lang->line('builder_elements_edit_sourcecode_error');
			}
		}
		/** Hook point */
		$this->hooks->call_hook('builder_elements_canEditBlockSourceCode_post');

		die(json_encode($this->data['return'] ));
	
	}
	/**
     * Controller desctruct method for custom hook point
     *
     * @return  void
     */
    public function __destruct()
    {
        /** Hook point */
        $this->hooks->call_hook('builder_elements_destruct');
    }

}