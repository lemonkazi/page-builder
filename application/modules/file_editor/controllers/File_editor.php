<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_editor extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, check if user has right to access this class
     *
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();


		if ( ! $this->session->has_userdata('user_id'))
		{
			redirect('auth', 'refresh');
		}
	}

	/**
	 * Check load the file editor
	 *
	 * @return 	void
	 */
	public function open()
	{
		
		/** Hook point */
		$this->hooks->call_hook('file_editor_open_pre');

		if ( isset($_GET['file']) && $this->input->get('file') !== '' && file_exists($this->input->get('file')) ) 
		{

			$this->data = [];
			$this->data['file'] = urlencode($this->input->get('file'));

			/** Hook point */
			$this->hooks->call_hook('file_editor_open_post');

			$this->load->view('file_editor/editor', $this->data);

		} 
		else 
		{

			die('No file or incorrect file');

		}

	}


	public function load_file()
	{

		/** Hook point */
		$this->hooks->call_hook('file_editor_load_file_pre');

		if ( isset($_GET['file']) && $this->input->get('file') !== '' && file_exists($this->input->get('file')) ) 
		{

			$this->load->helper('base64');

			$contents = file_get_contents($this->input->get('file'));

			die(base64_encode($contents));

			/** Hook point */
			$this->hooks->call_hook('file_editor_load_file_post');

		}

	}


	public function save_file()
	{

		/** Hook point */
		$this->hooks->call_hook('file_editor_save_file_pre');

		$this->load->helper('base64');
		$this->load->helper('file');

		$this->data = [];

		if ( $this->input->post('file') !== '' && isset($_POST['contents'])) 
		{

			if ( file_exists(urldecode($this->input->post('file'))) ) 
			{
				
				$contentDecoded = custom_base64_decode($this->input->post('contents'));

				if ( write_file(urldecode($this->input->post('file')), $contentDecoded) )
				{

					$this->data['return']['responseCode'] = 1;
					$this->data['return']['content'] = $this->lang->line('file_editor_editor_message_file_saved');

					die(json_encode($this->data['return']));

				}
				else
				{

					$this->data['return']['responseCode'] = 0;
					$this->data['return']['content'] = $this->lang->line('file_editor_editor_message_file_cantbesaved');

					die(json_encode($this->data['return']));

				}

			}
			else
			{

				$this->data['return']['responseCode'] = 0;
				$this->data['return']['content'] = $this->lang->line('file_editor_editor_message_file_doesnotexist');

				die(json_encode($this->data['return']));

			}

		} 
		else 
		{

			$this->data['return']['responseCode'] = 0;
			$this->data['return']['content'] = $this->lang->line('file_editor_editor_message_file_missing');

			die(json_encode($this->data['return']));

		}

		/** Hook point */
		$this->hooks->call_hook('file_editor_save_file_post');


	}

}