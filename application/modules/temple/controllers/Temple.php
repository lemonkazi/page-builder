<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temple extends MY_Controller {

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
			'user/Users_model' => 'MUsers',
			'sites/Pages_model' => 'MPages'
		];
		$this->load->model($model_list);

		if ( ! $this->session->has_userdata('user_id'))
		{
			redirect('auth', 'refresh');
		}

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];

		/** Hook point */
		$this->hooks->call_hook('temple_construct');

	}

	/**
	 * Grabs all the frames for a single page, mixes these into the skeleton.html file and echos the final output
	 *
	 * @param 	integer 	$page_id
	 * @return 	void
	 */
	public function index($page_id)
	{
		/** Hook point */
		$this->hooks->call_hook('temple_index_pre');

		die($this->MPages->load_page($page_id));
	}

	/**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('temple_destruct');
	}

}

/* End of file temple.php */
/* Location: ./application/modules/temple/controllers/Temple.php */