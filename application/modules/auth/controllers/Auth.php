<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    /**
     * Class constructor
     *
     * Loads required models, loads the hook class and add a hook point
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $model_list = [
            'user/Users_model' => 'MUsers',
            'package/Packages_model' => 'MPackages',
            'sites/Sites_model' => 'MSites',
            'settings/Payment_settings_model' => 'MPayments',
            'settings/Core_settings_model' => 'MCores',
            'settings/Whitelabel_model' => 'MWhitelabel',
        ];
        $this->load->model($model_list);

        $this->hooks = load_class('Hooks', 'core');
        $this->data = [];
        $this->data['whitelabel_general'] = $this->MWhitelabel->load();

        /** Hook point */
        $this->hooks->call_hook('auth_construct');

        //$this->output->enable_profiler(TRUE);
    }

    /**
     * User login
     *
     * @return void
     */
    public function index()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_index_pre');

        $this->load->helper('cookie');

        // Do we have a login cookie?
        $cookie = get_cookie('bloxby_login_token');

        if ( $cookie )
        {

            $values = explode("|", $cookie);

            $temp = $this->MUsers->get_by_remember_token($values[0], $values[1]);

            $remember_user = $temp[0];

        }
        else
        {
            $remember_user = false;
        }

        if ( $this->input->post() || $remember_user )
        {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ( $this->form_validation->run() == FALSE && !$remember_user )
            {
                $this->session->set_flashdata('error', $this->lang->line('auth_index_validation_error'));
                redirect('auth', 'location');
            }
            else
            {
                if ( !$remember_user )
                {
                    $post = $this->security->xss_clean($this->input->post());
                    $email = $post['email'];
                    $password = substr(do_hash($post['password']), 0, 32);
                    $user_info = $this->MUsers->verify($email, $password);
                }
                else
                {
                    $user_info = $this->MUsers->verify_token($remember_user['id'], $remember_user['login_token']);
                }

                /** No such user found or user not active */
                if ( ! $user_info )
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_index_login_error'));
                    redirect('auth', 'refresh');
                }
                else
                {
                    // Remember me?
                    if ( $this->input->post('remember') == 1 || $remember_user )
                    {
                        // Generate token and store for this user
                        $this->load->helper('string');

                        $token = random_string('sha1');

                        $this->MUsers->update_field( $this->session->userdata('user_id'), 'login_token', $token );

                        // Generate login cookie
                        set_cookie('bloxby_login_token', $this->session->userdata('user_id') . "|" .$token, 604800);
                    }
                    else
                    {
                        $this->MUsers->update_field( $this->session->userdata('user_id'), 'login_token', '' );
                    }

                    if ($this->session->userdata('user_type') == 'Admin')
                    {
                        $version = file_get_contents('./config.ini');
                        $license = $this->MCores->get_by_name('license_key');
                        $users = count($this->MUsers->get_all());
                        $sites = count($this->MSites->get_all());
                        $packages = count($this->MPackages->get_all());
                        $post = [
                            'license_key'   => isset($license['value']) ? $license['value'] : 'Un-Registered',
                            'packages'      => $packages,
                            'users'         => $users,
                            'sites'         => $sites,
                            'version'       => $version,
                            'base_url'      => base_url()
                        ];
                        $curl = @curl_init();
                        $url = $this->config->item('license_api') . 'info/';
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_POST, TRUE);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

                        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, FALSE);
                        curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
                        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
                        curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);

                        curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);

                        $result = @curl_exec($curl);
                        @curl_close($curl);

                        $autoupdate = $this->MCores->get_by_name('auto_update');
                        if ($autoupdate['value'] == 'yes')
                        {
                            redirect('autoupdate', 'refresh');
                        }
                        else
                        {

                            if ( $this->input->get('url') != '' )
                            {
                                redirect(urldecode($this->input->get('url')), 'refresh');
                            }
                            else
                            {
                                redirect('sites', 'refresh');
                            }
                        }
                    }
                    else
                    {
                        /** Check Subscripiotn is cancelled */
                        $cancelled = $this->checkuserstatus();
                        if ($cancelled)
                        {
                            $this->session->unset_userdata('user_id');
                            $this->session->unset_userdata('package_id');
                            $this->session->unset_userdata('user_fname');
                            $this->session->unset_userdata('user_lname');
                            $this->session->unset_userdata('user_email');
                            $this->session->unset_userdata('user_type');
                            $this->session->sess_destroy();
                            $this->session->set_flashdata('error', $this->lang->line('user_package_cancel_success_message'));
                            redirect('auth', 'refresh');
                        }

                        redirect('sites', 'refresh');
                    }
                }
            }
        }
        else
        {
            if ($this->session->userdata('user_id') != '')
            {
                redirect('sites', 'refresh');
            }
            $this->data['title'] = $this->lang->line('auth_index_title');
            $this->data['content'] = 'login';
            $this->data['page'] = 'login';

            /** Hook point */
            $this->hooks->call_hook('auth_index_post');

            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * User auto login
     *
     * @return void
     */
    public function alogin()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_alogin_pre');

        // Is auto login enabled?
        if ( $this->config->item('alogin_on') == 0 ) {
            $this->session->set_flashdata('error', $this->lang->line('auth_alogin_disabled'));
            redirect('auth', 'refresh');
        }

        // Check the token
        if ( $this->input->get('token') == '' )
        {
            $this->session->set_flashdata('error', $this->lang->line('auth_alogin_missing_token'));
            redirect('auth', 'refresh');
        }

        $hash = md5( base_url() . $this->config->item('alogin_key') );

        if ( $this->input->get('hash') == '' || $this->input->get('hash') != $hash || $this->config->item('alogin_key') == '' )
        {
            $this->session->set_flashdata('error', $this->lang->line('auth_alogin_bad_hash'));
            redirect('auth', 'refresh');
        }

        $user_info = $this->MUsers->verify_atoken($this->input->get('token'));

        if ( !$user_info )
        {
            $this->session->set_flashdata('error', $this->lang->line('auth_alogin_bad_token'));
            redirect('auth', 'refresh');
        }
        else
        {
            if ($this->session->userdata('user_type') == 'Admin')
            {
                $version = file_get_contents('./config.ini');
                $license = $this->MCores->get_by_name('license_key');
                $users = count($this->MUsers->get_all());
                $sites = count($this->MSites->get_all());
                $packages = count($this->MPackages->get_all());
                $post = [
                    'license_key'   => isset($license['value']) ? $license['value'] : 'Un-Registered',
                    'packages'      => $packages,
                    'users'         => $users,
                    'sites'         => $sites,
                    'version'       => $version,
                    'base_url'      => base_url()
                ];
                $curl = @curl_init();
                $url = $this->config->item('license_api') . 'info/';
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, FALSE);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
                curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);

                curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);

                $result = @curl_exec($curl);
                @curl_close($curl);

                $autoupdate = $this->MCores->get_by_name('auto_update');
                if ($autoupdate['value'] == 'yes')
                {
                    redirect('autoupdate', 'refresh');
                }
                else
                {
                    redirect('sites', 'refresh');
                }
            }
            else
            {
                /** Check Subscripiotn is cancelled */
                $cancelled = $this->checkuserstatus();
                if ($cancelled)
                {
                    $this->session->unset_userdata('user_id');
                    $this->session->unset_userdata('package_id');
                    $this->session->unset_userdata('user_fname');
                    $this->session->unset_userdata('user_lname');
                    $this->session->unset_userdata('user_email');
                    $this->session->unset_userdata('user_type');
                    $this->session->sess_destroy();
                    $this->session->set_flashdata('error', $this->lang->line('user_package_cancel_success_message'));
                    redirect('auth', 'refresh');
                }

                redirect('sites', 'refresh');
            }
        }

        /** Hook point */
        $this->hooks->call_hook('auth_alogin_post');
    }

    /**
     * User register
     *
     * @return void
     */
    public function register()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_register_pre');

        if ($this->input->post())
        {
            if (bloxby_package() != 0 && count($this->MUsers->get_all('User', 'Active')) >= bloxby_package())
            {
                $this->session->set_flashdata('error', $this->lang->line('auth_register_user_limit_error'));
                redirect("auth", 'refresh');
            }

            $this->form_validation->set_rules('first_name', 'First name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('package_id', 'Package', 'required');
            $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|trim|matches[password]');
            $this->form_validation->set_rules('captcha', 'Captcha', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('error', $this->lang->line('auth_register_form_errors') . validation_errors());
                $this->session->set_flashdata('formData', $_POST);
                redirect('auth/register', 'refresh');
            }
            else
            {
                $captcha = $this->session->userdata('randomString');
                $this->session->unset_userdata('randomString');
                $user_data = $this->input->post('captcha');

                /** Check the captcha */
                if ( ! isset($user_data) || $user_data != $captcha)
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_register_captcha_error'));
                    $this->session->set_flashdata('formData', $_POST);
                    redirect("auth/register", 'refresh');
                }

                if ($this->MUsers->is_email_exist($this->input->post('email')))
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_register_email_exist_error'));
                    $this->session->set_flashdata('formData', $_POST);
                    redirect('auth/register', 'refresh');
                }
                else
                {
                    /** Check if its under free plan or paid plan */
                    $package = $this->MPackages->get_by_id($this->input->post('package_id'));
                    if ($package['price'] != 0)
                    {
                        /** Getting payment menthod choosed by Admin */
                        $selected_payment_gateway = $this->MPayments->get_by_name('payment_gateway');
                        $selected_payment_gateway = $selected_payment_gateway[0]->value;

                        /** Check if its stripe or paypal gateway */
                        if ($selected_payment_gateway == "stripe") /** If Stripe is selected by Admin */
                        {
                            $stripe_cust = '';
                            $stripe_test_mode = $this->MPayments->get_by_name('stripe_test_mode');
                            $stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
                            $stripe_publishable_key = $this->MPayments->get_by_name('stripe_publishable_key');
                            if ($stripe_secret_key[0]->value != "")
                            {
                                $options['mode'] = $stripe_test_mode[0]->value;
                                $options['stripe_secret_key'] = $stripe_secret_key[0]->value;
                                $options['stripe_publishable_key'] = $stripe_publishable_key[0]->value;
                                $this->load->library('Stripe', $options);

                                /** Stripe add customer */
                                $customer['email'] = trim($this->input->post('email'));
                                $customer['description'] = trim($this->input->post('first_name')) . ' ' . trim($this->input->post('last_name'));
                                try
                                {
                                    $stripe_cust = $this->stripe->addCustomer($customer);
                                }
                                catch (\Stripe\Error\Base $e)
                                {
                                    $this->session->set_flashdata('error', $this->lang->line('auth_register_stripe_base_error') . $e->getMessage());
                                    redirect('auth/register', 'refresh');
                                }
                                catch (\Stripe\Error\Exception $e)
                                {
                                    $this->session->set_flashdata('error', $this->lang->line('auth_register_stripe_exception_error') . $e->getMessage());
                                    redirect('auth/register', 'refresh');
                                }
                            }

                            $user_id = $this->MUsers->create('User', $stripe_cust['id'], 'Inactive', 'stripe');
                            $this->session->set_flashdata('success', $this->lang->line('auth_register_user_success'));
                            redirect('auth/payment_stripe/' . $user_id, 'refresh');
                        }
                        else /** Else paypal is selected by Admin */
                        {
                            $user_id = $this->MUsers->create('User', NULL, 'Inactive', 'paypal');
                            $this->session->set_flashdata('success', $this->lang->line('auth_register_user_success'));
                            redirect('subscription/paypal/' . $user_id, 'refresh');
                        }
                    }
                    else
                    {
                        $user_id = $this->MUsers->create('User', NULL, 'Active');
                        $this->session->set_flashdata('success', $this->lang->line('auth_register_free_user_success'));
                        redirect('auth/free_user_confirm/' . $user_id, 'refresh');
                    }
                }
            }
        }
        else
        {
            if (bloxby_package() != 0 && count($this->MUsers->get_all('User', 'Active')) >= bloxby_package())
            {
                $this->session->set_flashdata('error', $this->lang->line('auth_register_user_limit_error'));
                redirect("auth", 'refresh');
            }

            /** Create a random string */
            $randomString = random_string('alpha', 7);
            $this->session->set_userdata('randomString', $randomString);

            $vals = array(
                'word'  => $randomString,
                'img_path'  => './tmp/',
                'img_url'   => base_url() . 'tmp/',
                'font_path' => './assets/fonts/lato/lato-black.ttf',
                'img_width' => 360,
                'img_height' => 80,
                'expiration' => 7200
            );

            $cap = create_captcha($vals);
            $this->data['captcha'] = $cap['image'];
            $this->data['title'] = $this->lang->line('auth_register_title');
            $this->data['content'] = 'register';
            $this->data['page'] = 'register';
            $gateway = $this->MPayments->get_by_name('payment_gateway');
            $this->data['packages'] = $this->MPackages->get_all($gateway[0]->value, true);

            /** Hook point */
            $this->hooks->call_hook('auth_register_post');

            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * Make stripe payment
     *
     * @param  integer  $user_id
     * @return void
     */
    public function payment_stripe($user_id = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('auth_payment_stripe_pre');

        if ($this->input->post())
        {
            $user_id = $this->input->post('user_id');
            $stripe_test_mode = $this->MPayments->get_by_name('stripe_test_mode');
            $stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
            $stripe_publishable_key = $this->MPayments->get_by_name('stripe_publishable_key');
            if ($stripe_secret_key[0]->value != "")
            {
                $options['mode'] = $stripe_test_mode[0]->value;
                $options['stripe_secret_key'] = $stripe_secret_key[0]->value;
                $options['stripe_publishable_key'] = $stripe_publishable_key[0]->value;
                $this->load->library('Stripe', $options);

                /** Stripe token create */
                $cust_id = trim($this->input->post('cust_id'));
                $token_opt['card'] = [
                    'number'    => trim($this->input->post('card_number')),
                    'exp_month' => trim($this->input->post('card_month')),
                    'exp_year'  => trim($this->input->post('card_year')),
                    'cvc'       => trim($this->input->post('card_cvc'))
                ];
                try
                {
                    $token = $this->stripe->create_token($token_opt);
                    $source_opt = [
                        'source' => $token['id']
                    ];
                    $this->stripe->updateCustomer($cust_id, $source_opt);
                }
                catch (\Stripe\Error\Base $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('stripe_token_base_error') . $e->getMessage());
                    redirect('auth/payment_stripe/' . $user_id, 'refresh');
                }
                catch (\Stripe\Error\Exception $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('stripe_token_exception_error') . $e->getMessage());
                    redirect('auth/payment_stripe/' . $user_id, 'refresh');
                }

                /** Stripe subscribe plan */
                $plan = trim($this->input->post('plan'));
                $sub_opt = [
                    'customer'  => $cust_id,
                    'items'     => [
                        [
                            'plan' => $plan
                        ]
                    ]
                ];
                try
                {
                    $subscribe = $this->stripe->create_subscription($sub_opt);
                    $this->MUsers->update_field($user_id, 'stripe_sub_id', $subscribe['id']);
                    $this->session->set_flashdata('success', $this->lang->line('auth_payment_stripe_payment_success'));
                }
                catch (\Stripe\Error\Base $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_payment_stripe_payment_base_error') . $e->getMessage());
                    redirect('auth/payment_stripe/' . $user_id, 'refresh');
                }
                catch (\Stripe\Error\Exception $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_payment_stripe_payment_exception_error') . $e->getMessage());
                    redirect('auth/payment_stripe/' . $user_id, 'refresh');
                }
            }

            redirect('auth/payment_confirm/' . $user_id, 'refresh');
        }
        else
        {
            $this->data['title'] = $this->lang->line('auth_payment_stripe_title');
            $this->data['content'] = 'payment_stripe';
            $this->data['page'] = 'payments';
            $this->data['user'] = $this->MUsers->get_by_id($user_id);

            /** Hook point */
            $this->hooks->call_hook('auth_payment_stripe_post');

            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * Show confirm payment and send email to customer
     *
     * @param  integer  $user_id
     * @return void
     */
    public function payment_confirm($user_id)
    {
        /** Hook point */
        $this->hooks->call_hook('auth_payment_confirm_pre');

        $this->data['title'] = $this->lang->line('auth_payment_confirm_title');
        $this->data['content'] = 'payment_confirm';
        $this->data['page'] = 'payments';

        /** Activate user */
        $this->MUsers->update_active($user_id);
        /** Get user details for send email */
        $user = $this->MUsers->get_by_id($user_id);
        $this->data['id'] = $user['id'];
        $this->data['name'] = $user['first_name'] . ' ' . $user['last_name'];

        $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
        $this->email->to($user['email']);
        $this->email->subject($this->config->item('email_confirmation_subject'));
        $body = $this->load->view('auth/email/confirmation.tpl.php', $this->data, TRUE);
        $this->email->message($body);

        $this->email->send();

        /** Hook point */
        $this->hooks->call_hook('auth_payment_confirm_post');

        $this->load->view('shared/layout', $this->data);
    }

    /**
     * Show confirm free user and send email to customer
     *
     * @param  integer  $user_id
     * @return void
     */
    public function free_user_confirm($user_id)
    {
        /** Hook point */
        $this->hooks->call_hook('auth_free_user_confirm_pre');

        $this->data['title'] = $this->lang->line('auth_free_user_confirm_title');
        $this->data['content'] = 'payment_confirm';
        $this->data['page'] = 'payments';

        /** Get user details for send email */
        $user = $this->MUsers->get_by_id($user_id);
        $this->data['id'] = $user['id'];
        $this->data['name'] = $user['first_name'] . ' ' . $user['last_name'];

        $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
        $this->email->to($user['email']);
        $this->email->subject($this->config->item('email_confirmation_subject'));
        $body = $this->load->view('auth/email/free_user_confirmation.tpl.php', $this->data, TRUE);
        $this->email->message($body);

        $this->email->send();

        /** Hook point */
        $this->hooks->call_hook('auth_free_user_confirm_post');

        $this->load->view('shared/layout', $this->data);
    }

    /**
     * User payment if created by admin
     *
     * @param  integer  $user_id
     * @return void
     */
    public function payment_card_update($user_id = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('auth_payment_card_update_pre');

        if ($this->input->post())
        {
            $user_id = trim($this->input->post('user_id'));
            $cust_id = trim($this->input->post('cust_id'));
            $user = $this->MUsers->get_by_id($user_id);
            $stripe_test_mode = $this->MPayments->get_by_name('stripe_test_mode');
            $stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
            $stripe_publishable_key = $this->MPayments->get_by_name('stripe_publishable_key');
            if ($stripe_secret_key[0]->value != "")
            {
                $options['mode'] = $stripe_test_mode[0]->value;
                $options['stripe_secret_key'] = $stripe_secret_key[0]->value;
                $options['stripe_publishable_key'] = $stripe_publishable_key[0]->value;
                $this->load->library('Stripe', $options);
                $sub_opt['source'] = array(
                    'object'    => 'card',
                    'exp_month' => trim($this->input->post('card_month')),
                    'exp_year'  => trim($this->input->post('card_year')),
                    'number'    => trim($this->input->post('card_number')),
                    'cvc'       => trim($this->input->post('card_cvc')),
                    'name'      => trim($this->input->post('name'))
                );

                try
                {
                    $subscribe = $this->stripe->updateCustomer($cust_id, $sub_opt);
                    $this->session->set_flashdata('success', $this->lang->line('auth_payment_card_update_success'));
                }
                catch (\Stripe\Error\Base $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_payment_card_update_base_error') . $e->getMessage());
                    redirect('auth/payment_card_update/' . $user_id, 'refresh');
                }
                catch (\Stripe\Error\Exception $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_payment_card_update_exception_error') . $e->getMessage());
                    redirect('auth/payment_card_update/' . $user_id, 'refresh');
                }
            }

            redirect('auth', 'refresh');
        }
        else
        {
            $this->data['title'] = $this->lang->line('auth_payment_card_update_title');
            $this->data['content'] = 'payment_card_update';
            $this->data['page'] = 'payments';
            $this->data['user'] = $this->MUsers->get_by_id($user_id);

            /** Hook point */
            $this->hooks->call_hook('auth_payment_card_update_post');

            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * User activation
     *
     * @param  integer  $user_id
     * @param  string   $activation_code
     * @return void
     */
    public function activate($email = NULL, $activation_code = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('auth_activate_pre');

        $email = $this->security->xss_clean(urldecode($email));
        $activation_code = $this->security->xss_clean($activation_code);
        $user_id = $this->MUsers->isValidActivationKey($email, $activation_code);

        if ( ! $user_id)
        {
            $this->session->set_flashdata('error', $this->lang->line('auth_activate_key_error'));
            redirect('', 'refresh');
        }
        else
        {
            $this->MUsers->update_active($user_id);

            $this->MUsers->updateUserInfo($update_field_arr, $user_id);
            $user_info =  $this->MUsers->getUserInfo($user_id);

            unset($user_info->password);
            $this->session->set_userdata('user_id', $user_id);
            $this->session->set_userdata('company_id', $user_info->company_id);
            $this->session->set_userdata('email', $user_info->email);
            $this->session->set_userdata('user_type', $user_info->user_type);

            /** Hook point */
            $this->hooks->call_hook('auth_activate_post');

            redirect('sites', 'refresh');
        }
    }

    /**
     * Send forgot password email
     *
     * @return void
     */
    public function forgot()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_forgot_pre');

        if ($this->input->post())
        {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() === FALSE)
            {
                redirect('auth', 'refresh');
            }
            else
            {
                $email = $this->security->xss_clean(trim($this->input->post('email')));
                $user = $this->MUsers->get_by_email($email);
                //print_r($user); die();

                /** No user with this email */
                if ( ! $user)
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_forgot_email_error'));
                    redirect('auth/forgot', 'refresh');
                }

                /** If user is not active */
                if ($user['status'] != 'Active')
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_forgot_status_error'));
                    redirect('auth/forgot', 'refresh');
                }

                /** Build token */
                $password_reset_key = random_string('alnum', 40);

                $this->MUsers->update_field($user['id'], 'forgot_code', $password_reset_key);

                $email_url_encode = urlencode($email);
                $url = base_url() . "auth/reset_password/{$email_url_encode}/" . $password_reset_key;
                $link = '<a href="' . $url . '">Reset password</a>';

                $message = '';
                $message .= $this->lang->line('auth_forgot_message1');
                $message .= $this->lang->line('auth_forgot_message2') . $link;

                /** Send Email */
                $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                $this->email->to($email);
                $this->email->subject($this->config->item('email_forgot_password_subject'));
                $this->email->message($message);
                if ($this->email->send())
                {
                    $this->session->set_flashdata('success', $this->lang->line('auth_forgot_success'));
                    redirect('auth/forgot', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_forgot_error'));
                    redirect('auth/forgot', 'refresh');
                }
            }
        }
        else
        {
            $this->data['title'] = $this->lang->line('auth_forgot_title');
            $this->data['content'] = 'forgot';
            $this->data['page'] = 'login';

            /** Hook point */
            $this->hooks->call_hook('auth_forgot_post');

            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * Password reset
     *
     * @param  string   $email
     * @param  string   $forgot_code
     * @return void
     */
    public function reset_password($email = NULL, $forgot_code = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('auth_reset_password_pre');

        if ($this->input->post())
        {
            $user = $this->MUsers->get_by_reset_link($this->security->xss_clean(urldecode($this->input->post('email'))), $this->security->xss_clean($this->input->post('forgot_code')));

            /** Invalid reset key */
            if ( ! $user)
            {
                $this->session->set_flashdata('error', $this->lang->line('auth_reset_password_invalid_key_error'));
                redirect('auth', 'refresh');
            }

            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
            $this->form_validation->set_rules('re_password', 'Password Confirmation', 'required|matches[password]');

            if ($this->form_validation->run() === FALSE)
            {

            }
            else
            {
                $password = substr(do_hash($this->input->post('password')), 0, 32);

                if ( ! $this->MUsers->update_field($user['id'], 'password', $password))
                {
                    $this->session->set_flashdata('error', $this->lang->line('auth_reset_password_update_error'));
                }
                else
                {
                    $this->MUsers->update_field($user['id'], 'forgot_code', '');
                    $this->session->set_flashdata('success', $this->lang->line('auth_reset_password_update_success'));
                }
                redirect('auth', 'refresh');
            }
        }
        else
        {
            $user = $this->MUsers->get_by_reset_link($this->security->xss_clean(urldecode($email)), $this->security->xss_clean($forgot_code));
            if (count($user) > 0)
            {
                $this->data['title'] = $this->lang->line('auth_reset_password_title');
                $this->data['email'] = $email;
                $this->data['forgot_code'] = $forgot_code;
                $this->data['content'] = 'reset_password';
                $this->data['page'] = 'login';

                /** Hook point */
                $this->hooks->call_hook('auth_reset_password_valid_user');

                $this->load->view('shared/layout', $this->data);
            }
            else
            {
                $this->session->set_flashdata('error', $this->lang->line('auth_reset_password_url_error'));

                /** Hook point */
                $this->hooks->call_hook('auth_reset_password_invalid_user');

                redirect('auth', 'refresh');
            }
        }
    }

    /**
     * User logout
     *
     * @return void
     */
    public function logout()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_logout_pre');

        // Reset login token
        $this->MUsers->update_field( $this->session->userdata('user_id'), 'login_token', '' );

        // Remove the cookie
        $this->load->helper('cookie');
        delete_cookie('bloxby_login_token');


        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('package_id');
        $this->session->unset_userdata('user_fname');
        $this->session->unset_userdata('user_lname');
        $this->session->unset_userdata('user_email');
        $this->session->unset_userdata('user_type');
        $this->session->sess_destroy();

        /** Hook point */
        $this->hooks->call_hook('auth_logout_post');

        redirect('auth', 'refresh');
    }

    /**
     * To handle instant payment notification from paypal
     *
     * @return http_response
     */
    public function ipn_notifier()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_ipn_notifier_pre');

        $data = file_get_contents('php://input');
        $post_data = explode('&', $data);

        $paypal_Settings = $this->MPayments->get_all();
        $options['username'] = $paypal_Settings[3]->value; //paypal_api_username;
        $options['password'] = $paypal_Settings[4]->value; //paypal_api_password;
        $options['signature'] = $paypal_Settings[5]->value; //paypal_api_signature;
        $options['sandbox'] = (($paypal_Settings[6]->value == 'test') ? true : false);

        $this->load->library('Paypal', $options);

        $verified = $response = $this->paypal->verify_ipn($post_data);
        if ($verified)
        {
            // Process the transaction data
            $paypal_profile_id = $post_data['recurring_payment_id'];
            $txn_id = $post_data['txn_id'];

            $paypal_profile_data = $this->paypal->get_recurring_profile($paypal_profile_id);
            if ($paypal_profile_data['ACK'] == "Success")
            {
                $next_billing_date = urldecode($paypal_profile_data['NEXTBILLINGDATE']);

                $where['field'] = "paypal_profile_id";
                $where['value'] = $paypal_profile_id;

                $value['field'] = "paypal_next_payment_date";
                $value['value'] = $next_billing_date;

                $this->MUsers->update_custom_field($where, $value);

                $value['field'] = "paypal_last_transaction_id";
                $value['value'] = $txn_id;

                $this->MUsers->update_custom_field($where, $value);
            }
            else
            {
                $paypal_profile_id = $post_data['recurring_payment_id'];
                $where['field'] = "paypal_profile_id";
                $where['value'] = $paypal_profile_id;

                $value['field'] = "status";
                $value['value'] = 'Inactive';

                $this->MUsers->update_custom_field($where, $value);
            }
        }
        /** Hook point */
        $this->hooks->call_hook('auth_ipn_notifier_post');

        header("HTTP/1.1 200 OK");
    }

    /**
     * To check and de-active user account if subscription is cancelled
     *
     * @return boolean $cancel
     */
    public function checkuserstatus()
    {
        $cancel = false;
        $user_id = $this->session->userdata('user_id');
        $todaydate = date("Y-m-d");

        $userids = $this->MUsers->get_by_next_billing_date($todaydate, $user_id);
        if (isset($userids) && !empty($userids))
        {
            foreach ($userids as $user)
            {
                $this->MUsers->update_field($user['id'], 'status', 'Inactive');
                $cancel = true;
            }
        }

        return $cancel;
    }

    /**
     * Call back function for stripe transaction.
     *
     * @return http_response
     */
    public function stripe_hook()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_stripe_hook_pre');

        if ($this->input->post())
        {
            $stripe_test_mode = $this->MPayments->get_by_name('stripe_test_mode');
            $stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
            $stripe_publishable_key = $this->MPayments->get_by_name('stripe_publishable_key');
            if ($stripe_secret_key[0]->value != "")
            {
                $options['mode'] = $stripe_test_mode[0]->value;
                $options['stripe_secret_key'] = $stripe_secret_key[0]->value;
                $options['stripe_publishable_key'] = $stripe_publishable_key[0]->value;
                $this->load->library('Stripe', $options);

                /** Retrieve the request's body and parse it as JSON */
                $input = @file_get_contents("php://input");
                $event_json = json_decode($input);

                /** Do something with $event_json */

                /** Verify the event by fetching it from Stripe */
                $event = $this->stripe->getEvent($event_json->id);

                if (isset($event) && $event->type == "invoice.payment_failed")
                {
                    $customer = $this->stripe->getCustomer($event->data->object->customer);
                    $email = $customer->email;
                    $user = $this->MUsers->get_by_email($email);
                    $data['user_id'] = $user['id'];
                    $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                    /** Sending your customers the amount in pennies is weird, so convert to dollars */
                    $data['amount'] = $event->data->object->currency . ' ' . sprintf('$%0.2f', $event->data->object->amount_due / 100.0);
                    /** Get all admins for notify faild payment */
                    $admins = $this->MUsers->get_by_type('Admin');
                    foreach ($admins as $admin)
                    {
                        $bcc[] = $admin['email'];
                    }
                    $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                    $this->email->to($email);
                    $this->email->bcc($bcc);
                    $this->email->subject($this->config->item('email_confirmation_subject'));
                    $body = $this->load->view('auth/email/payment_failed.tpl.php', $data, TRUE);
                    $this->email->message($body);

                    $this->email->send();
                }
            }

            /** Hook point */
            $this->hooks->call_hook('auth_stripe_hook_post');

            http_response_code(200); // PHP 5.4 or greater
        }
    }

    /**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
    public function __destruct()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_destruct');
    }

}