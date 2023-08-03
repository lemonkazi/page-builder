<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, check if user has right to access this class, loads stripe keys, loads the hook class and add a hook point
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
			'package/Packages_model' => 'MPackages',
			'settings/Payment_settings_model' => 'MPayments',
			'builder_elements/Blocks_model' => 'MBlocks',
			'settings/Whitelabel_model' => 'MWhitelabel'
		];
		$this->load->model($model_list);

		if ( ! $this->session->has_userdata('user_id'))
		{
			redirect('auth', 'refresh');
		}

		$stripe_test_mode = $this->MPayments->get_by_name('stripe_test_mode');
		$stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
		$stripe_publishable_key = $this->MPayments->get_by_name('stripe_publishable_key');
		if ($stripe_secret_key[0]->value != "")
		{
			$options['mode'] = $stripe_test_mode[0]->value;
			$options['stripe_secret_key'] = $stripe_secret_key[0]->value;
			$options['stripe_publishable_key'] = $stripe_publishable_key[0]->value;
			$this->load->library('Stripe', $options);
		}

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];
		$this->data['whitelabel_general'] = $this->MWhitelabel->load();

		/** Hook point */
		$this->hooks->call_hook('package_construct');
	}

	public function view_product()
	{
		echo $this->stjhtripe->get_product("prod_D2Im2R1NO1uLFW");
	}

	/**
	 * Loads the package page
	 *
	 * @return void
	 */
	public function index()
	{
		/** Hook point */
		$this->hooks->call_hook('package_index_pre');

		if ($this->session->userdata('user_type') != "Admin")
		{
			redirect('sites', 'refresh');
		}

		$gateway = $this->MPayments->get_by_name('payment_gateway');
		if ($gateway[0]->value == 'paypal')
		{
			$paypal_api_username = $this->MPayments->get_by_name('paypal_api_username');
			if ($paypal_api_username[0]->value != "")
			{
				$this->data['paypal_api'] = 'yes';
			}
			else
			{
				$this->data['paypal_api'] = 'no';
			}
			$this->data['packages'] = $this->MPackages->get_all('paypal');
		}
		else if ($gateway[0]->value == 'stripe')
		{
			$stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
			if ($stripe_secret_key[0]->value != "")
			{
				$this->data['stripe_key'] = 'yes';
			}
			else
			{
				$this->data['stripe_key'] = 'no';
			}
			$this->data['packages'] = $this->MPackages->get_all('stripe');
		}

		$this->data['page'] = "package";

		$this->data['templates'] = $this->MPages->get_templates();
		$this->data['blockCategories'] = $this->MBlocks->allBlockCategories();

		/** Hook point */
		$this->hooks->call_hook('package_index_post');

		$this->load->view('package/packages', $this->data);
	}

	/**
	 * Creates a new package
	 *
	 * @return void
	 */
	public function create()
	{
		/** Hook point */
		$this->hooks->call_hook('package_create_pre');

		if ($this->session->userdata('user_type') != "Admin")
		{
			die($this->lang->line('package_admin_permission_error'));
		}

		$stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');

		$this->form_validation->set_rules('name', 'Package name', 'required');
		$this->form_validation->set_rules('sites_number', 'Number of sites', 'required');
		if ($stripe_secret_key[0]->value != "")
		{
			$this->form_validation->set_rules('price', 'Price', 'required');
		}

		if ($this->form_validation->run() == FALSE)
		{
			/** All did not go well */
			$return = array();
			$temp = array();
			$temp['header'] = $this->lang->line('package_create_form_validation_error_heading');
			$temp['content'] = $this->lang->line('package_create_form_validation_error_message') . validation_errors();
			$return['responseCode'] = 0;
			$return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
			die(json_encode($return));
		}
		else
		{
			/** Check payment gateway */
			$gateway = $this->MPayments->get_by_name('payment_gateway');
			if ($gateway[0]->value == 'stripe')
			{
				/** Create stripe plan if needed */
				if ($stripe_secret_key[0]->value != "" && $this->input->post('price') != 0)
				{
					/** Custom id prefix for product and plan */
					$custom_id = str_replace(' ', '_', strtolower(trim($this->input->post('name')))) . '_' . trim($this->input->post('sites_number')) . '_' . str_replace(' ', '_', strtolower($this->input->post('subscription')));

					/** Create product */
					$product_opt['id'] = $custom_id . '_product';
					$product_opt['name'] = trim($this->input->post('name'));
					$product_opt['type'] = 'service';
					$product_opt['statement_descriptor'] = $this->lang->line('package_create_statement_descriptor');
					try
					{
						/** Create product in Stripe */
						$this->stripe->add_product($product_opt);
					}
					catch (\Stripe\Error\Base $e)
					{
						$return = array();
						$temp = array();
						$temp['header'] = $this->lang->line('package_create_stripe_base_error_heading');
						$temp['content'] = $this->lang->line('package_create_stripe_base_error_message') . $e->getMessage();
						$return['responseCode'] = 1;
						$return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
						die(json_encode($return));
					}
					catch (\Stripe\Error\Exception $e)
					{
						$return = array();
						$temp = array();
						$temp['header'] = $this->lang->line('package_create_stripe_exception_error_heading');
						$temp['content'] = $this->lang->line('package_create_stripe_exception_error_message') . $e->getMessage();
						$return['responseCode'] = 1;
						$return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
						die(json_encode($return));
					}

					/** Create plan */
					$plan_opt['id'] = $custom_id . '_plan';
					$plan_opt['amount'] = $this->input->post('price') * 100;
					$plan_opt['currency'] = $this->input->post('currency');
					if ($this->input->post('subscription') == 'Weekly')
					{
						$plan_opt['interval'] = 'week';
					}
					else if ($this->input->post('subscription') == 'Monthly')
					{
						$plan_opt['interval'] = 'month';
					}
					else if ($this->input->post('subscription') == 'Yearly')
					{
						$plan_opt['interval'] = 'year';
					}
					else if ($this->input->post('subscription') == 'Every 3 months')
					{
						$plan_opt['interval'] = 'month';
						$plan_opt['interval_count'] = 3;
					}
					else if ($this->input->post('subscription') == 'Every 6 months')
					{
						$plan_opt['interval'] = 'month';
						$plan_opt['interval_count'] = 6;
					}
					$plan_opt['product'] = $custom_id . '_product';
					try
					{
						/** Create package in Stripe */
						$this->stripe->addPlan($plan_opt);
					}
					catch (\Stripe\Error\Base $e)
					{
						$return = array();
						$temp = array();
						$temp['header'] = $this->lang->line('package_create_stripe_base_error_heading');
						$temp['content'] = $this->lang->line('package_create_stripe_base_error_message') . $e->getMessage();
						$return['responseCode'] = 1;
						$return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
						die(json_encode($return));
					}
					catch (\Stripe\Error\Exception $e)
					{
						$return = array();
						$temp = array();
						$temp['header'] = $this->lang->line('package_create_stripe_exception_error_heading');
						$temp['content'] = $this->lang->line('package_create_stripe_exception_error_message') . $e->getMessage();
						$return['responseCode'] = 1;
						$return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
						die(json_encode($return));
					}

					/** Create package in Bloxby system */
					$package_id = $this->MPackages->create('stripe', $plan_opt['id']);
				}
				else
				{
					/** Create the package in Bloxby system */
					$this->MPackages->create('stripe');
				}
			}
			else
			{
				/** Create the package in Bloxby system */
				$this->MPackages->create('paypal');
			}

			/** All good then */
			$this->return = array();
			$temp = array();
			$temp['header'] = $this->lang->line('package_create_success_heading');
			$temp['content'] = $this->lang->line('package_create_success_message');
			/** Include packages in the return as well */
			$this->return['packages'] = $this->load->view('package/package_table', array('packages'=>$this->MPackages->get_all($gateway[0]->value)), TRUE);
			$this->return['responseCode'] = 1;
			$this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

			/** Hook point */
			$this->hooks->call_hook('package_create_post');

			die(json_encode($this->return));
		}
	}

	/**
	 * Get package details for ajax request
	 *
	 * @param  integer 	$id
	 * @return json		$package
	 */
	public function get_package($id)
	{
		$package = $this->MPackages->get_by_id($id);
		die(json_encode($package));
	}

	/**
	 * Update a package
	 *
	 * @return void
	 */
	public function update()
	{
		/** Hook point */
		$this->hooks->call_hook('package_update_pre');

		if ($this->session->userdata('user_type') != "Admin")
		{
			die($this->lang->line('package_admin_permission_error'));
		}

		$this->form_validation->set_rules('name', 'Package name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			/** All did not go well */
			$return = array();
			$temp = array();
			$temp['header'] = $this->lang->line('package_update_form_validation_error_heading');
			$temp['content'] = $this->lang->line('package_update_form_validation_error_message') . validation_errors();
			$return['responseCode'] = 0;
			$return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
			die(json_encode($return));
		}
		else
		{
			/** Get existing package details */
			$package = $this->MPackages->get_by_id($this->input->post('id'));

			/** Check payment gateway */
			$gateway = $this->MPayments->get_by_name('payment_gateway');
			if ($gateway[0]->value == 'stripe')
			{
				/** Update Stripe plan if needed */
				$stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
				if ($stripe_secret_key[0]->value != "" && $this->input->post('name') != $package['name'])
				{
					$package_name = $this->input->post('name') . ' Package';
					try
					{
						/** Update packge in Stripe */
						$this->stripe->updatePlan($package['stripe_id'], $package_name);
						/** Update package in Bloxby system */
						$this->MPackages->update();
					}
					catch (\Stripe\Error\Base $e)
					{
						$return = array();
						$return['responseCode'] = 1;
						$return['responseHTML'] = $this->lang->line('package_update_stripe_base_error_message') . $e->getMessage();
						die(json_encode($return));
					}
					catch (\Stripe\Error\Exception $e)
					{
						$return = array();
						$return['responseCode'] = 1;
						$return['responseHTML'] = $this->lang->line('package_update_stripe_exception_error_message') . $e->getMessage();
						die(json_encode($return));
					}
				}
				else
				{
					/** Update package if there is no stripe key available */
					$this->MPackages->update();
				}
			}
			else
			{
				/** Update package in Bloxby system */
				$this->MPackages->update();
			}

			/** All good then */
			$this->return = array();
			/** Include packages in the return as well **/
			$this->return['packages'] = $this->load->view('package/package_table', array('packages'=>$this->MPackages->get_all($gateway[0]->value)), TRUE);
			$this->return['responseCode'] = 1;
			$this->return['responseHTML'] = $this->lang->line('package_update_success_message');

			/** Hook point */
			$this->hooks->call_hook('package_update_post');

			die(json_encode($this->return));
		}
	}

	/**
	 * Delete package
	 *
	 * @param  integer 	$id
	 * @return void
	 */
	public function delete($id)
	{
		/** Hook point */
		$this->hooks->call_hook('package_delete_pre');

		if ($this->session->userdata('user_type') != "Admin")
		{
			die($this->lang->line('package_admin_permission_error'));
		}

		/** Check payment gateway */
		$gateway = $this->MPayments->get_by_name('payment_gateway');
		if ($gateway[0]->value == 'stripe')
		{
			/** Delete Stripe plan if needed */
			$package = $this->MPackages->get_by_id($id);
			$stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
			if ($stripe_secret_key[0]->value != "" && $package['stripe_id'] != '')
			{
				/** Delete plan first */
				$plan_id = $package['stripe_id'];
				try
				{
					$this->stripe->deletePlan($plan_id);
				}
				catch (\Stripe\Error\InvalidRequest $e)
				{
					$this->session->set_flashdata('stripe_error', $this->lang->line('package_delete_stripe_invalid_request_error'));
					redirect('packages', 'refresh');
				}
				catch (\Stripe\Error\Base $e)
				{
					$this->session->set_flashdata('stripe_error', $this->lang->line('package_delete_stripe_base_error') . $e->getMessage());
					redirect('packages', 'refresh');
				}
				catch (\Stripe\Error\Exception $e)
				{
					$this->session->set_flashdata('stripe_error', $this->lang->line('package_delete_stripe_exception_error') . $e->getMessage());
					redirect('packages', 'refresh');
				}

				/** Delete product */
				$product_id = str_replace('_plan', '_product', $plan_id);
				try
				{
					$this->stripe->delete_product($product_id);
				}
				catch (\Stripe\Error\Base $e)
				{
					$this->session->set_flashdata('stripe_error', $this->lang->line('package_delete_stripe_base_error') . $e->getMessage());
					redirect('packages', 'refresh');
				}
			}
		}

		if ($this->MPackages->delete($id))
		{
			$this->session->set_flashdata('success', $this->lang->line('package_delete_success'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('package_delete_error'));
		}

		/** Hook point */
		$this->hooks->call_hook('package_delete_post');

		redirect('packages', 'refresh');
	}

	/**
     * Enable or disable package
     *
     * @deprecated 	Toggle package status option is not using any more
     *
     * @param  		integer 	$package_id
     * @return 		void
     */
	public function toggle_status($package_id)
	{
		/** Hook point */
		$this->hooks->call_hook('package_toggle_status_pre');

		if ($this->session->userdata('user_type') != "Admin")
		{
			die($this->lang->line('package_admin_permission_error'));
		}

		/** Activate package */
		$user = $this->MPackages->get_by_id($package_id);
		if ($user['status'] == "Active")
		{
			$this->MPackages->update_status("Inactive", $package_id);
			$this->session->set_flashdata('success', $this->lang->line('package_toggle_status_inactive'));
		}
		else
		{
			$this->MPackages->update_status("Active", $package_id);
			$this->session->set_flashdata('success', $this->lang->line('package_toggle_status_active'));
		}

		/** Hook point */
		$this->hooks->call_hook('package_toggle_status_post');

		redirect('packages', 'refresh');
	}

	/**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('package_destruct');
	}

}
