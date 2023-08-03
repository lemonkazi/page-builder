<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

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
			'sites/Sites_model' => 'MSites',
			'sites/Pages_model' => 'MPages',
			'settings/Apps_settings_model' => 'MApps',
			'settings/Payment_settings_model' => 'MPayments',
			'settings/Core_settings_model' => 'MCores',
			'settings/Whitelabel_model' => 'MWhitelabel',
			'settings/Settings_model' => 'MSettings'
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
		$this->hooks->call_hook('settings_construct');
	}

	/**
	 * Load the settings page
	 *
	 * @return 	void
	 */
	public function index()
	{
		/** Hook point */
		$this->hooks->call_hook('settings_index_pre');

		if ($this->session->userdata('user_type') != "Admin")
		{
			redirect('site', 'refresh');
		}

		/** Check if the license_key field exist, if not create a blank one */
		$core = $this->MCores->get_by_name('license_key');
		if (count($core) == 0)
		{
			$name = 'license_key';
			$description = '<h5>License Key</h5>
			<p>
			License Key, without this key you are unable to get further updates.
			</p>';
			$required = 1;
			$this->MCores->create($name, $description, $required);
		}

		/** Grab all three settings value */
		$this->data['apps'] = $this->MApps->get_all();
		$this->data['payments'] = $this->MPayments->get_all();
		$this->data['cores'] = $this->MCores->get_all();

		$this->data['rest_api_keys'] = $this->MSettings->get_rest_keys();

		$tempStyles = json_decode($this->MSettings->get_by_name('colors')['value'], true);
		$tempStyles = $tempStyles['style'];

		$styleArray = [];

		if ( $tempStyles && count($tempStyles) > 0 )
		{
			foreach( $tempStyles as $styleArray1 ) 
			{

				foreach( $styleArray1 as $selector => $style ) {

					$temp = explode(": ", $style);

					$str =  "$selector: $temp[0]";

					$styleArray[$str] = $temp[1];

				} 

			}
			$this->data['whitelabel_settings'] = $styleArray;
		}

		$this->data['whitelabel_logo_image'] = $this->MSettings->get_by_name('logo_image')['value'];
		$this->data['whitelabel_logo_text'] = $this->MSettings->get_by_name('logo_text')['value'];
		$this->data['whitelabel_custom_css'] = $this->MSettings->get_by_name('custom_css')['value'];

		$this->data['page'] = "settings";

		/** Hook point */
		$this->hooks->call_hook('settings_index_post');

		$this->load->view('settings/settings', $this->data);
	}

	/**
	 * Updates the apps settings
	 *
	 * @return 	void
	 */
	public function update()
	{
		/** Hook point */
		$this->hooks->call_hook('settings_update_pre');

		$this->form_validation->set_rules('elements_dir', 'elements_dir', 'required');
		$this->form_validation->set_rules('images_dir', 'images_dir', 'required');
		$this->form_validation->set_rules('images_uploadDir', 'images_uploadDir', 'required');
		$this->form_validation->set_rules('upload_max_size' , 'upload_max_size','required|numeric');
		$this->form_validation->set_rules('export_fileName', 'export_fileName', 'required');
		$this->form_validation->set_rules('language', 'language', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			/** Some errors :( */
				$this->session->set_flashdata('error', $this->lang->line('settings_update_error') . validation_errors());
			}
			else
			{
				/** All good :) Update the data */
				$this->MApps->update($this->input->post());
				$this->session->set_flashdata('success', $this->lang->line('settings_update_success'));
			}

			/** Hook point */
			$this->hooks->call_hook('settings_update_post');

			redirect('settings', 'location');
		}

	/**
	 * Update the payment settings
	 *
	 * @return 	void
	 */
	public function update_payment()
	{
		/** Hook point */
		$this->hooks->call_hook('settings_update_payment_pre');

		if ($this->input->post())
		{
			/** Update the data */
			$this->MPayments->update($this->input->post());
			$this->session->set_flashdata('success', $this->lang->line('settings_update_success'));
		}

		/** Hook point */
		$this->hooks->call_hook('settings_update_payment_post');

		redirect('settings', 'location');
	}

	/**
	 * Update the core settings
	 *
	 * @return 	void
	 */
	public function update_core()
	{
		/** Hook point */
		$this->hooks->call_hook('settings_update_core_pre');

		if ($this->input->post())
		{
			/** Update the data */
			$this->MCores->update($this->input->post());

			/** License key validation */
			$url = $this->config->item('license_uri') . trim($this->input->post('license_key'));
			/** curl */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$output = curl_exec($ch);
			curl_close($ch);
			if ($output == 'valid')
			{
				$this->session->set_flashdata('success', $this->lang->line('settings_update_success'));
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('settings_update_invalid_key_error'));
			}

		}

		/** Hook point */
		$this->hooks->call_hook('settings_update_core_post');

		redirect('settings', 'location');
	}

	/**
	 * Update the core settings
	 *
	 * @return 	void
	 */
	public function update_whitelabel()
	{

		/** Hook point */
		$this->hooks->call_hook('settings_update_whitelabel_pre');

		$this->data['settings'] = [];
		$this->load->helper('base64');

		$skipInput = ['textLogoText', 'inputLogoFile', 'textAreaCustomCSS', 'inputLogoFile_'];

		//die(phpinfo());


		// Logo image upload
		if ( isset($_FILES['inputLogoFile']) && $_FILES['inputLogoFile']['name'] != '' ) {

			$this->load->helper(array('form', 'url'));

        	$this->load->library('upload', $this->config->item('logo_upload_config'));

        	if ( ! $this->upload->do_upload('inputLogoFile'))
        	{
        		$this->session->set_flashdata('error', $this->lang->line('settings_update_whitelabel_error1_message') . $this->upload->display_errors());
        	}
        	else
        	{
        		$this->data['settings']['logo_image'] = $this->upload->data('file_name');
        	}

    	}
    	else if ( $this->input->post('inputLogoFile_') != '' )
    	{
    		$this->data['settings']['logo_image'] = $this->input->post('inputLogoFile_');
    	}
    	else
    	{
    		$this->data['settings']['logo_image'] = '';
    	}

    	// Logo text
    	if ( $this->input->post('textLogoText') != '' )
    	{
    		$this->data['settings']['logo_text'] = $this->input->post('textLogoText');
    	}
    	else
    	{
    		$this->data['settings']['logo_text'] = '';
    	}

    	// Colors
    	$styles = [];

    	foreach($_POST as $key => $val)
    	{
    		if ( !in_array($key, $skipInput) )
    		{

    			$decoded = custom_base64_decode($key);
    			$decoded_array = json_decode($decoded);

    			foreach($decoded_array as $selector => $attribute)
    			{
    				$temp = [];
    				$temp[$selector] = "$attribute: $val";

    				array_push($styles, $temp);
    			}

    		}
    	}

    	$final = [];
    	$final['style'] = $styles;
    	
    	$this->data['settings']['colors'] = json_encode($final);

    	if ( $this->input->post('textAreaCustomCSS') != '' )
    	{

    		$this->data['settings']['custom_css'] = custom_base64_decode($this->input->post('textAreaCustomCSS'));

    	}
    	else
    	{
    		$this->data['settings']['custom_css'] = '';
    	}

    	$this->MSettings->update($this->data['settings']);

    	$this->session->set_flashdata('success', $this->lang->line('settings_update_success'));

		/** Hook point */
		$this->hooks->call_hook('settings_update_whitelabel_post');

		redirect('settings', 'location');
	}

	/**
	 * Update the integration settings
	 *
	 * @return 	void
	 */
	public function update_integration()
	{

		/** Hook point */
		$this->hooks->call_hook('settings_update_integration_pre');

		if ($this->input->post())
		{
			/** Update the data */
			$this->MSettings->update_integration($this->input->post());
			$this->session->set_flashdata('success', $this->lang->line('settings_update_success'));
		}

		/** Hook point */
		$this->hooks->call_hook('settings_update_integration_post');

		redirect('settings', 'location');
	}

	/* Test Section */
	public function license_check()
	{
		$key = 'abc';
		$url = 'http://bloxbylicense.dev/api/verify_key/' . $key;
		//echo $url;
		/** curl **/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		echo $output;
	}
	public function cust_add()
	{
		$options['mode'] = 'test';
		$options['stripe_secret_key'] = 'sk_test_47Czw1072NYOn9b5d7N4kJXY';
		//$options['stripe_secret_key'] = 'sk_test_47Czw1072NYOn9b5d7N'; // wrong sk
		$options['stripe_publishable_key'] = 'pk_test_LCBPFcRtihTBXZ9XCeZtdsgR';
		$this->load->library('Stripe', $options);
		$customer['email'] = 'tapan@innovativebd.net';
		$customer['description'] = 'Test Customer';
		$cus_id = $this->stripe->addCustomer($customer);
		echo $cus_id;
	}

	public function cust_list()
	{
		$options['mode'] = 'test';
		$options['stripe_secret_key'] = 'sk_test_47Czw1072NYOn9b5d7N4kJXY';
		//$options['stripe_secret_key'] = 'sk_test_47Czw1072NYOn9b5d7N'; // wrong sk
		$options['stripe_publishable_key'] = ' pk_test_LCBPFcRtihTBXZ9XCeZtdsgR';
		$this->load->library('Stripe', $options);
		$customer = array('limit' => 3);
		$cus = $this->stripe->listCustomers($customer);
		var_dump($cus['data'][0]['id']);
	}

	public function pack_sub()
	{
		$options['mode'] = 'test';
		$options['stripe_secret_key'] = 'sk_test_47Czw1072NYOn9b5d7N4kJXY';
		//$options['stripe_secret_key'] = 'sk_test_47Czw1072NYOn9b5d7N'; // wrong sk
		$options['stripe_publishable_key'] = ' pk_test_LCBPFcRtihTBXZ9XCeZtdsgR';
		$this->load->library('Stripe', $options);

		$customer = array('limit' => 3);
		$cus = $this->stripe->listCustomers($customer);
		// var_dump($cus['data'][0]['id']);
		// die();

		// $plan_opt['id'] = 'plan_03';
		// $plan_opt['amount'] = 1000;
		// $plan_opt['currency'] = 'USD';
		// $plan_opt['interval'] = 'month';
		// $plan_opt['name'] = '3 months subscription';
		// $plan_opt['interval_count'] = 3;
		// $plan_opt['statement_descriptor'] = 'Builder Subscription';

		// $plan = $this->stripe->addPlan($plan_opt);
		// var_dump($plan);
		// die();

		//$sub_opt['customer'] = $cus['data'][0]['id'];
		$sub_opt['plan'] = 'plan_03';
		$sub_opt['metadata'] = array('plan' => '3 months');
		$sub_opt['quantity'] = 1;
		$sub_opt['source'] = array(
			'object'	=> 'card',
			'exp_month'	=> '12',
			'exp_year'	=> '2018',
			'number'	=> '4242424242424141',
			'cvc'		=> '123',
			'name'		=> 'Tapan Kumer Das'
		);
		$sub_opt['trial_end'] = 'now';

		$subscribe = $this->stripe->addSubscription($cus['data'][0]['id'], $sub_opt);
		var_dump($subscribe);
		die();
	}
	/* Test Section */

	/**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('settings_destruct');
	}

}