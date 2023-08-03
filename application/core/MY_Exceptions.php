<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

	public function __construct()
	{
		parent::__construct();
	}

	public function show_404($page = '', $log_error = TRUE)
	{
		$CI =& get_instance();
		if ($CI === NULL)
		{
			new CI_Controller();
			$CI =& get_instance();
		}
		$CI->output->set_status_header('404');
		$CI->load->view('shared/my404');
		echo $CI->output->get_output();
		exit;
	}
}