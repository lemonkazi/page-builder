<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

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
            'package/Packages_model' => 'MPackages',
            'settings/Payment_settings_model' => 'MPayments',
            'settings/Whitelabel_model' => 'MWhitelabel'
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
        $this->hooks->call_hook('user_construct');

        /** Enable profiler */
        //$this->output->enable_profiler(TRUE);
    }

    /**
     * Main user page, lists all users
     *
     * @return  void
     */
    public function index()
    {
        /** Hook point */
        $this->hooks->call_hook('user_index_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            die($this->lang->line('admin_permission_error'));
        }

        /** Get all users plus their sites */
        $this->data['users'] = $this->MUsers->getUsers();
        $gateway = $this->MPayments->get_by_name('payment_gateway');
        $this->data['packages'] = $this->MPackages->get_all($gateway[0]->value, true);
        $this->data['page'] = "user";


        /** Hook point */
        $this->hooks->call_hook('user_index_post');

        $this->load->view('user/users', $this->data);

    }

    /**
     * Create new user
     *
     * @return  json    $return
     */
    public function create()
    {
        /** Hook point */
        $this->hooks->call_hook('user_create_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            die($this->lang->line('admin_permission_error'));
        }

        if (bloxby_package() != 0 && count($this->MUsers->get_all('User', 'Active')) >= bloxby_package())
        {
            $temp = array();
            $temp['header'] = $this->lang->line('user_create_limit_error_heading');
            $temp['content'] = $this->lang->line('user_create_limit_error_message');
            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
            die(json_encode($this->return));
        }

        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            /** All did not go well */
            $temp = array();
            $temp['header'] = $this->lang->line('user_create_validation_error_heading');
            $temp['content'] = $this->lang->line('user_create_validation_error_message') . validation_errors();
            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
            die(json_encode($this->return));
        }
        else
        {
            /** Make sure the email address is allowed */
            $user = $this->MUsers->get_by_email($this->input->post('email'));
            if (count($user) > 0)
            {
                /** All did not go well */
                $temp = array();
                $temp['header'] = $this->lang->line('user_create_no_user_error_heading');
                $temp['content'] = $this->lang->line('user_create_no_user_error_message');
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                die(json_encode($this->return));
            }

            /** Create the account */

            /** If its an admin account then just create it, else we need to do some other task too */
            $is_admin = $this->input->post('is_admin');
            if (isset($is_admin))
            {
                $this->MUsers->create('Admin', NULL, 'Active');
            }
            else
            {
                /** Check if its under free plan or paid plan */
                $package = $this->MPackages->get_by_id($this->input->post('package_id'));
                if ($package['price'] != 0)
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
                            $temp = array();
                            $temp['header'] = $this->lang->line('user_create_stripe_base_error_heading');
                            $temp['content'] = $this->lang->line('user_create_stripe_base_error_message') . $e->getMessage();
                            $this->return = array();
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                            die(json_encode($this->return));
                        }
                        catch (\Stripe\Error\Exception $e)
                        {
                            $temp = array();
                            $temp['header'] = $this->lang->line('user_create_stripe_exception_error_heading');
                            $temp['content'] = $this->lang->line('user_create_stripe_exception_error_message') . $e->getMessage();
                            $this->return = array();
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                            die(json_encode($this->return));
                        }
                    }

                    $user_id = $this->MUsers->create('User', $stripe_cust['id'], 'Inactive', 'stripe');
                }
                else
                {
                    $user_id = $this->MUsers->create('User', NULL, 'Active', 'stripe');
                }

                /** Get user details for send email */
                $user = $this->MUsers->get_by_id($user_id);
                $data['user_id'] = $user['id'];
                $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                $this->email->to($user['email']);
                /** If its paid paln then send the payment link else send the login link */
                if ($package['price'] != 0)
                {
                    $this->email->subject($this->config->item('email_activation_subject'));
                    $body = $this->load->view('user/email/activation.tpl.php', $data, TRUE);
                }
                else
                {
                    $this->email->subject($this->config->item('email_login_subject'));
                    $body = $this->load->view('user/email/login.tpl.php', $data, TRUE);
                }
                $this->email->message($body);

                $this->email->send();
            }

            /** All good then */
            $temp = array();
            $temp['header'] = $this->lang->line('user_create_success_heading');
            $temp['content'] = $this->lang->line('user_create_success_message');
            $this->return = array();
            /** Include user list in return as well */
            $this->return['users'] = $this->load->view('user/user_list', array('users'=>$this->MUsers->getUsers()), TRUE);
            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

            /** Hook point */
            $this->hooks->call_hook('user_create_post');

            die(json_encode($this->return));
        }
    }

    /**
     * Updates existing user
     *
     * @return  json    $return
     */
    public function update($user_id = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('user_update_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            die($this->lang->line('admin_permission_error'));
        }

        $this->form_validation->set_rules('user_id', 'User ID', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE)
        {
            /** All did not go well */
            $temp = array();
            $temp['header'] = $this->lang->line('user_update_validation_error_heading');
            $temp['content'] = $this->lang->line('user_update_validation_error_message') . validation_errors();
            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
            die(json_encode($this->return));
        }
        else
        {
            /** Make sure the email address is allowed */
            $user = $this->MUsers->get_by_id($this->input->post('user_id'));
            $email = $this->input->post('email');

            if ($user['email'] != $email && count($user) > 0)
            {
                /** All did not go well */
                $temp = array();
                $temp['header'] = $this->lang->line('user_update_not_allowed_email_error_heading');
                $temp['content'] = $this->lang->line('user_update_not_allowed_email_error_message');
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                die(json_encode($this->return));
            }

            /** Update the account details */

            /** If its an admin just update the account, else we need to do something more. */
            $is_admin = $this->input->post('is_admin');
            if (isset($is_admin) && $is_admin == 'yes')
            {
                $this->MUsers->update($user['id'], 'Admin');
            }
            else
            {
                $user_package = $this->MPackages->get_by_id($user['package_id']);
                $package = $this->MPackages->get_by_id($this->input->post('package_id'));
                $payment = $this->MPayments->get_by_name('payment_gateway');
                /** If package changed it need to check something more either just update the user details */
                if ($user['package_id'] != $package['id'])
                {
                    /** User under a free package and update to another free package. Update the user package information in database only */
                    if ($user_package['price'] == 0 && $package['price'] == 0)
                    {
                        $this->MUsers->update($user['id'], 'User');
                    }
                    /** User under a paid package and update to a free package. Unsubscribe user from payment gateway and update user package information in database */
                    else if ($user_package['price'] != 0 && $package['price'] == 0)
                    {
                        /** Check if its Stripe */
                        if ($user['current_subscription_gateway'] == "stripe" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "stripe"))
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

                                try
                                {
                                    $stripe_cust = $this->stripe->cancelSubscription($user['stripe_sub_id']);
                                }
                                catch (\Stripe\Error\Base $e)
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_update_cancel_stripe_base_error_heading');
                                    $temp['content'] = $this->lang->line('user_update_cancel_stripe_base_error_message') . $e->getMessage();
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }
                                catch (\Stripe\Error\Exception $e)
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                                    $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . $e->getMessage();
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }

                                /** Update user information */
                                $this->MUsers->update($user['id'], 'User');
                                /** Force user status active */
                                $this->MUsers->update_field($user['id'], 'status', 'Active');
                            }
                            else
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_empty_stripe_key_heading');
                                $temp['content'] = $this->lang->line('user_update_empty_stripe_key_message');
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }
                        }
                        /** Must be Paypal */
                        else if ($user['current_subscription_gateway'] == "paypal" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "paypal"))
                        {
                            /** Cancel current subscription */
                            $paypal_Settings = $this->MPayments->get_all();
                            foreach ($paypal_Settings as $setting)
                            {
                                if ($setting->name == 'paypal_api_username')
                                {
                                    $options['username'] = $setting->value;
                                }
                                else if ($setting->name == 'paypal_api_password')
                                {
                                    $options['password'] = $setting->value;
                                }
                                else if ($setting->name == 'paypal_api_signature')
                                {
                                    $options['signature'] = $setting->value;
                                }
                                else if ($setting->name == 'paypal_test_mode')
                                {
                                    $options['sandbox'] = (($setting->value == 'test') ? true : false);
                                }
                            }

                            $this->load->library('Paypal', $options);

                            $reason = $this->lang->line('package_changed_from') . ' ' . $user['package_name'] . ' (' . $user['package_price'] . ' ' . $user['package_currency'] . ') ' . $this->lang->line('package_changed_to') .' ' . $package['name'] . ' (' . $package['price'] . ' ' . $package['currency'] . ')';

                            $response = $this->paypal->manage_recurring_profile_status($user['paypal_profile_id'], "Cancel", $reason);

                            if (isset($response['ACK']) && $response['ACK'] == "Success")
                            {
                                $this->MUsers->update_field($user['id'], 'paypal_profile_status', "Cancel");
                            }
                            else
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_cancel_paypal_error_heading');
                                $temp['content'] = $this->lang->line('user_update_cancel_paypal_error_message') . '<br><b>' . urldecode($response['L_LONGMESSAGE0']) . '</b>';
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }

                            /** Do necessary refund */

                            /** Getting last transaction ID of user */
                            $data['transactionid'] = $user['paypal_last_transaction_id'];

                            /** Calculating prorate amount */
                            $response = $this->paypal->get_recurring_profile($user['paypal_profile_id']);
                            $userdata = $this->MUsers->get_by_id($user['id']);

                            if (isset($response['LASTPAYMENTDATE']))
                            {
                                $last_payment_date = urldecode($response['LASTPAYMENTDATE']);
                            }
                            else
                            {
                                $last_payment_date = urldecode($userdata['paypal_previous_payment_date']);
                            }

                            if (isset($response['LASTPAYMENTAMT']))
                            {
                                $last_payment_amt = urldecode($response['LASTPAYMENTAMT']);
                            }
                            else
                            {
                                $last_payment_amt = urldecode($userdata['package_price']);
                            }

                            $billing_period = urldecode($response['BILLINGPERIOD']); /** Day, Week, SemiMonth, Month, Year */
                            $billing_period_in_day = 0;
                            if ($billing_period == "Day")
                            {
                                $billing_period_in_day = 1;
                            }
                            else if ($billing_period == "Week")
                            {
                                $billing_period_in_day = 7;
                            }
                            else if ($billing_period == "SemiMonth")
                            {
                                $billing_period_in_day = 15;
                            }
                            else if ($billing_period == "Month")
                            {
                                $billing_period_in_day = 30;
                            }
                            else if ($billing_period == "Year")
                            {
                                $billing_period_in_day = 365;
                            }

                            $billing_period_in_days = $billing_period_in_day * (int)urldecode($response['BILLINGFREQUENCY']);

                            $one_day_cost = ((int)$last_payment_amt)/$billing_period_in_days;

                            $date_1 = new DateTime(date("Y-m-d", strtotime($last_payment_date))); /** Last payment date */
                            $date_2 = new DateTime(date("Y-m-d")); /** Current date */
                            $subscription_days_till_date = (int)$date_2->diff($date_1)->format("%a");
                            if ($subscription_days_till_date == 0)
                            {
                                $subscription_days_till_date = 1;
                            }

                            $total_billing_amt_till_date = $subscription_days_till_date * $one_day_cost;

                            $data['amount'] = $last_payment_amt - $total_billing_amt_till_date; /** Total amount to refund */

                            $data['note'] = $this->lang->line('refund_reason');

                            /** Processing Refund */
                            $response = $this->paypal->process_refund($data);

                            $data['user_id'] = $user['id'];
                            $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                            if ($response['ACK'] != "Success")
                            {
                                /** Inform admin that Refund process was not successful. So do it manully. */
                                $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                                $this->email->to($this->config->item('email_from_address'));
                                $this->email->subject($this->config->item('refund_failed_subject'));
                                $body = $this->load->view('user/email/paypal_refund_failed.tpl.php', $data, TRUE);
                                $this->email->message($body);

                                $this->email->send();
                            }

                            /** Update user information */
                            $this->MUsers->update($user['id'], 'User');
                            /** Force user status active */
                            $this->MUsers->update_field($user['id'], 'status', 'Active');
                        }
                    }
                    /** User under a free package and update to a paid package. Subscribe user in payment gateway with new package. Update the account with status inactive and send the payment link to user email address */
                    else if ($user_package['price'] == 0 && $package['price'] != 0)
                    {
                        /** Check if its Stripe */
                        if ($user['current_subscription_gateway'] == "stripe" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "stripe"))
                        {
                            /** Check if user is already a stripe customer or not, if not create customer */
                            if ($user['stripe_cus_id'] == "")
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
                                        $temp = array();
                                        $temp['header'] = $this->lang->line('user_create_stripe_base_error_heading');
                                        $temp['content'] = $this->lang->line('user_create_stripe_base_error_message') . $e->getMessage();
                                        $this->return = array();
                                        $this->return['responseCode'] = 0;
                                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                        die(json_encode($this->return));
                                    }
                                    catch (\Stripe\Error\Exception $e)
                                    {
                                        $temp = array();
                                        $temp['header'] = $this->lang->line('user_create_stripe_exception_error_heading');
                                        $temp['content'] = $this->lang->line('user_create_stripe_exception_error_message') . $e->getMessage();
                                        $this->return = array();
                                        $this->return['responseCode'] = 0;
                                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                        die(json_encode($this->return));
                                    }

                                    $this->MUsers->update_field($user['id'], 'stripe_cus_id', $stripe_cust['id']);
                                }
                                else
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_update_empty_stripe_key_heading');
                                    $temp['content'] = $this->lang->line('user_update_empty_stripe_key_message');
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }
                            }

                            /** Update user information */
                            $this->MUsers->update($user['id'], 'User');
                            /** Force user status inactive */
                            $this->MUsers->update_field($user['id'], 'status', 'Inactive');

                            /** Get user details for send payment and activation email */
                            $data['user_id'] = $user['id'];
                            $data['name'] = $user['first_name'] . ' ' . $user['last_name'];
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($user['email']);
                            $this->email->subject($this->config->item('email_activation_subject'));
                            $body = $this->load->view('user/email/update.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }
                        /** Must be Paypal */
                        else if ($user['current_subscription_gateway'] == "paypal" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "paypal"))
                        {
                            /** Since user is upgrading to paid from free so it is similar as fresh subscription. */

                            /** Update user information */
                            $this->MUsers->update($user['id'], 'User');
                            /** Force user status inactive */
                            $this->MUsers->update_field($user['id'], 'status', 'Inactive');

                            /** Get user details for send payment and activation email */
                            $data['user_id'] = $user['id'];
                            $data['name'] = $user['first_name'] . ' ' . $user['last_name'];
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($user['email']);
                            $this->email->subject($this->config->item('email_activation_subject'));
                            $body = $this->load->view('user/email/paypal_update.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }
                    }
                    /** User under a paid package and update to another paid package. Cancel the current subscription from payment gateway. Update user account with current details and send the payment link to user email address */
                    else
                    {
                        /** Check if its Stripe */
                        if ($user['current_subscription_gateway'] == "stripe" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "stripe"))
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

                                try
                                {
                                    $stripe_cust = $this->stripe->cancelSubscription($user['stripe_sub_id']);
                                }
                                catch (\Stripe\Error\Base $e)
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_update_cancel_stripe_base_error_heading');
                                    $temp['content'] = $this->lang->line('user_update_cancel_stripe_base_error_message') . $e->getMessage();
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }
                                catch (\Stripe\Error\Exception $e)
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                                    $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . $e->getMessage();
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }

                                /** Update user information */
                                $this->MUsers->update($user['id'], 'User');
                                /** Force user status inactive */
                                $this->MUsers->update_field($user['id'], 'status', 'Inactive');

                                /** Get user details for send payment and activation email */
                                $data['user_id'] = $user['id'];
                                $data['name'] = $user['first_name'] . ' ' . $user['last_name'];
                                $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                                $this->email->to($user['email']);
                                $this->email->subject($this->config->item('email_activation_subject'));
                                $body = $this->load->view('user/email/update.tpl.php', $data, TRUE);
                                $this->email->message($body);

                                $this->email->send();
                            }
                            else
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_empty_stripe_key_heading');
                                $temp['content'] = $this->lang->line('user_update_empty_stripe_key_message');
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }
                        }
                        /** Must be Paypal */
                        else if ($user['current_subscription_gateway'] == "paypal" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "paypal"))
                        {
                            /** Cancel current subscription */
                            $paypal_Settings = $this->MPayments->get_all();
                            foreach ($paypal_Settings as $setting)
                            {
                                if ($setting->name == 'paypal_api_username')
                                {
                                    $options['username'] = $setting->value;
                                }
                                else if ($setting->name == 'paypal_api_password')
                                {
                                    $options['password'] = $setting->value;
                                }
                                else if ($setting->name == 'paypal_api_signature')
                                {
                                    $options['signature'] = $setting->value;
                                }
                                else if ($setting->name == 'paypal_test_mode')
                                {
                                    $options['sandbox'] = (($setting->value == 'test') ? true : false);
                                }
                            }

                            $this->load->library('Paypal', $options);

                            $reason = $this->lang->line('package_changed_from') . ' ' . $user['package_name'] . ' (' . $user['package_price'] . ' ' . $user['package_currency'] . ') ' . $this->lang->line('package_changed_to') . ' ' . $package['name'] . ' (' . $package['price'] . ' ' . $package['currency'] . ')';

                            $response = $this->paypal->manage_recurring_profile_status($user['paypal_profile_id'], "Cancel", $reason);

                            if (isset($response['ACK']) && $response['ACK'] == "Success")
                            {
                                $this->MUsers->update_field($user['id'], 'paypal_profile_status', "Cancel");
                            }
                            else
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                                $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . urldecode($response['L_LONGMESSAGE0']);
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }

                            /** Do necessary refund */

                            /** Getting last transaction ID of user */
                            $data['transactionid'] = $user['paypal_last_transaction_id'];

                            /** Calculating prorate amount */
                            $response = $this->paypal->get_recurring_profile($user['paypal_profile_id']);
                            $userdata = $this->MUsers->get_by_id($user['id']);

                            if (isset($response['LASTPAYMENTDATE']))
                            {
                                $last_payment_date = urldecode($response['LASTPAYMENTDATE']);
                            }
                            else
                            {
                                $last_payment_date = urldecode($userdata['paypal_previous_payment_date']);
                            }

                            if (isset($response['LASTPAYMENTAMT']))
                            {
                                $last_payment_amt = urldecode($response['LASTPAYMENTAMT']);
                            }
                            else
                            {
                                $last_payment_amt = urldecode($userdata['package_price']);
                            }

                            $billing_period = urldecode($response['BILLINGPERIOD']); /** Day, Week, SemiMonth, Month, Year */
                            $billing_period_in_day = 0;
                            if ($billing_period == "Day")
                            {
                                $billing_period_in_day = 1;
                            }
                            else if ($billing_period == "Week")
                            {
                                $billing_period_in_day = 7;
                            }
                            else if ($billing_period == "SemiMonth")
                            {
                                $billing_period_in_day = 15;
                            }
                            else if ($billing_period == "Month")
                            {
                                $billing_period_in_day = 30;
                            }
                            else if ($billing_period == "Year")
                            {
                                $billing_period_in_day = 365;
                            }

                            $billing_period_in_days = $billing_period_in_day * (int)urldecode($response['BILLINGFREQUENCY']);

                            $one_day_cost = ((int)$last_payment_amt)/$billing_period_in_days;

                            $date_1 = new DateTime(date("Y-m-d", strtotime($last_payment_date))); /** Last payment date */
                            $date_2 = new DateTime(date("Y-m-d")); /** Current date */
                            $subscription_days_till_date = (int)$date_2->diff($date_1)->format("%a");
                            if ($subscription_days_till_date == 0)
                            {
                                $subscription_days_till_date = 1;
                            }

                            $total_billing_amt_till_date = $subscription_days_till_date * $one_day_cost;

                            $data['amount'] = $last_payment_amt - $total_billing_amt_till_date; /** Total amount to refund */

                            $data['note'] = $this->lang->line('refund_reason');

                            /** Processing Refund */
                            $response = $this->paypal->process_refund($data);

                            $data['user_id'] = $user['id'];
                            $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                            if ($response['ACK'] != "Success")
                            {
                                /** Inform admin that Refund process was not successful. So do it manully. */
                                $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                                $this->email->to($this->config->item('email_from_address'));
                                $this->email->subject($this->config->item('refund_failed_subject'));
                                $body = $this->load->view('user/email/paypal_refund_failed.tpl.php', $data, TRUE);
                                $this->email->message($body);

                                $this->email->send();
                            }

                            /** Since user is upgrading to paid from cancel account so it is similar as fresh subscription. */

                            /** Update user information */
                            $this->MUsers->update($user['id'], 'User');
                            /** Force user status inactive */
                            $this->MUsers->update_field($user['id'], 'status', 'Inactive');

                            /** Get user details for send payment and activation email */
                            $data['user_id'] = $user['id'];
                            $data['name'] = $user['first_name'] . ' ' . $user['last_name'];
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($user['email']);
                            $this->email->subject($this->config->item('email_activation_subject'));
                            $body = $this->load->view('user/email/paypal_update.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }
                    }
                }
                else
                {
                    $this->MUsers->update($user['id'], 'User');
                }
            }

            /** All good then */
            $temp = array();
            $temp['header'] = $this->lang->line('user_update_success_heading');
            $temp['content'] = $this->lang->line('user_update_success_message');
            $this->return = array();
            /** Return the user details form as well */
            $userStuff = $this->MUsers->getUsers($this->input->post('user_id'));
            $packages = $this->MPackages->get_all();
            $this->return['userDetailForm'] = $this->load->view('user/user_details_form', array('user' => $userStuff[0], 'packages' => $packages), TRUE);
            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

            /** Hook point */
            $this->hooks->call_hook('user_update_post');

            die(json_encode($this->return));
        }
    }

    /**
     * Send password reset email from admin panel
     *
     * @param   integer $user_id
     * @return  void
     */
    public function send_password_reset($user_id = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('user_send_password_reset_pre');

        $user = $this->MUsers->get_by_id($user_id);

        /** Build token */
        $password_reset_key = random_string('alnum', 40);

        $this->MUsers->update_field($user['id'], 'forgot_code', $password_reset_key);

        $email_url_encode = urlencode($user['email']);
        $url = base_url() . "auth/reset_password/{$email_url_encode}/" . $password_reset_key;
        $link = '<a href="' . $url . '">Reset password</a>';

        $message = '';
        $message .= $this->lang->line('user_send_password_reset_message1');
        $message .= $this->lang->line('user_send_password_reset_message2') . $link;

        /** Send Email */
        $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
        $this->email->to($user['email']);
        $this->email->subject($this->config->item('email_reset_password_subject'));
        $this->email->message($message);
        if ($this->email->send())
        {
            $temp = array();
            $temp['header'] = $this->lang->line('user_send_password_reset_success_heading');
            $temp['content'] = $this->lang->line('user_send_password_reset_success_message');
            $this->return = array();
            $this->return['responseCode'] = 1;
            $this->return['responseHTML'] = $this->lang->line('user_send_password_reset_success_message');

            /** Hook point */
            $this->hooks->call_hook('user_send_password_reset_success');

            die(json_encode($this->return));
        }
        else
        {
            $temp = array();
            $temp['header'] = $this->lang->line('user_send_password_reset_error_heading');
            $temp['content'] = $this->lang->line('user_send_password_reset_error_message');
            $this->return = array();
            $this->return['responseCode'] = 0;
            $this->return['responseHTML'] = $this->lang->line('user_send_password_reset_error_message');

            /** Hook point */
            $this->hooks->call_hook('user_send_password_reset_error');

            die(json_encode($this->return));
        }
    }

    /**
     * Update user details
     *
     * @return  json    $return
     */
    public function details_update()
    {
        /** Hook point */
        $this->hooks->call_hook('user_details_update_pre');

        if ($this->input->post())
        {
            $this->form_validation->set_rules('id', 'User ID', 'required');
            $this->form_validation->set_rules('first_name', 'First name', 'required');
            $this->form_validation->set_rules('last_name', 'Last name', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                /** Send error message for validation fails */
                $temp = array();
                $temp['header'] = $this->lang->line('user_details_update_validation_error_heading');
                $temp['content'] = $this->lang->line('user_details_update_validation_error_message') . validation_errors();
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                die(json_encode($this->return));
            }
            else
            {
                /** All well, update user details */
                if ($this->MUsers->details_update())
                {
                    /** Update session data */
                    $this->session->unset_userdata('user_fname');
                    $this->session->unset_userdata('user_lname');
                    $data['user_fname'] = $this->input->post('first_name');
                    $data['user_lname'] = $this->input->post('last_name');
                    $this->session->set_userdata($data);
                    /** Send success message */
                    $temp = array();
                    $temp['header'] = $this->lang->line('user_details_update_success_heading');
                    $temp['content'] = $this->lang->line('user_details_update_success_message');
                    $this->return = array();
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_details_update_success');

                    die(json_encode($this->return));
                }
                else
                {
                    /** Send error message if update fails */
                    $temp = array();
                    $temp['header'] = $this->lang->line('user_details_update_error_heading');
                    $temp['content'] = $this->lang->line('user_details_update_error_heading');
                    $this->return = array();
                    $this->return['responseCode'] = 0;
                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_details_update_error');

                    die(json_encode($this->return));
                }
            }
        }
    }

    /**
     * Update user login details
     *
     * @return  json    $return
     */
    public function login_update()
    {
        /** Hook point */
        $this->hooks->call_hook('user_login_update_pre');

        if ($this->input->post())
        {
            $this->form_validation->set_rules('id', 'User ID', 'required');
            $this->form_validation->set_rules('email', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                /** All did not go well */
                $temp = array();
                $temp['header'] = $this->lang->line('user_login_update_validation_error_heading');
                $temp['content'] = $this->lang->line('user_login_update_validation_error_message') . validation_errors();
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                die(json_encode($return));
            }
            else
            {
                /** All well, update user details */
                if ($this->MUsers->login_update())
                {
                    /** Saved OK */
                    $temp = array();
                    $temp['header'] = $this->lang->line('user_login_update_success_heading');
                    $temp['content'] = $this->lang->line('user_login_update_success_message');
                    $this->return = array();
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_login_update_success');

                    die(json_encode($this->return));
                }
                else
                {
                    /** Not saved */
                    /** All did not go well */
                    $temp = array();
                    $temp['header'] = $this->lang->line('user_login_update_error_heading');
                    $temp['content'] = $this->lang->line('user_login_update_error_message');
                    $this->return = array();
                    $this->return['responseCode'] = 0;
                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_login_update_error');

                    die(json_encode($this->return));
                }
            }
        }
    }

    /**
     * Enables or disable user account
     *
     * @param   integer     $user_id
     * @return  void
     */
    public function toggle_status($user_id)
    {
        /** Hook point */
        $this->hooks->call_hook('user_toggle_status_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            die($this->lang->line('admin_permission_error'));
        }

        if (bloxby_package() != 0 && count($this->MUsers->get_all('User', 'Active')) >= bloxby_package())
        {
            $this->session->set_flashdata('error', $this->lang->line('user_toggle_status_user_limit_error'));
            redirect('user', 'refresh');
        }

        /** Activate user account */
        $user = $this->MUsers->get_by_id($user_id);
        if ($user['status'] == "Active")
        {
            $this->MUsers->update_status("Inactive", $user_id);
            $this->session->set_flashdata('success', $this->lang->line('user_toggle_status_inactive'));
        }
        else
        {
            $this->MUsers->update_status("Active", $user_id);
            $this->session->set_flashdata('success', $this->lang->line('user_toggle_status_active'));
        }

        /** Hook point */
        $this->hooks->call_hook('user_toggle_status_post');

        redirect('user', 'refresh');
    }

    /**
     * Delete user account with all sites
     *
     * @param   integer     $user_id
     * @return  void
     */
    public function delete($user_id)
    {
        /** Hook point */
        $this->hooks->call_hook('user_delete_pre');

        if ($this->session->userdata('user_type') != "Admin")
        {
            die($this->lang->line('admin_permission_error'));
        }

        /** Trash all site of the user */
        $this->MSites->deleteAllFor($user_id);
        /** Remove the user */
        $user = $this->MUsers->delete($user_id);
        if ($user)
        {
            $this->session->set_flashdata('success', $this->lang->line('user_delete_success'));
        }
        else
        {
            $this->session->set_flashdata('error', $this->lang->line('user_delete_error'));
        }

        /** Hook point */
        $this->hooks->call_hook('user_delete_post');

        redirect('user', 'refresh');
    }

    /**
     * Update subscription of user package
     *
     * @return  json    $return
     */
    public function package_update()
    {
        /** Hook point */
        $this->hooks->call_hook('user_package_update_pre');

        if ($this->input->post())
        {
            $user = $this->MUsers->get_by_id($this->input->post('user_id'));
            $package = $this->MPackages->get_by_id($this->input->post('package_id'));
            $payment = $this->MPayments->get_by_name('payment_gateway');

            if ($user['package_id'] != $package['id'])
            {
                /** User under a free package and update to another free package. Update the user package information in database only */
                if ($user['package_price'] == 0 && $package['price'] == 0)
                {
                    /** Update package in database */
                    $this->MUsers->update_field($user['id'], 'package_id', $package['id']);
                    /** Update package in session */
                    $this->session->set_userdata('package_id',$package['id']);

                    $this->return = array();
                    $temp = array();
                    $temp['header'] = $this->lang->line('user_package_update_free_free_success_heading');
                    $temp['content'] = $this->lang->line('user_package_update_free_free_success_message');
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_package_update_success');

                    die(json_encode($this->return));
                }
                /** User under a paid package and update to a free package. Unsubscribe user from payment gateway and update user package information in database */
                else if ($user['package_price'] != 0 && $package['price'] == 0)
                {
                    /** Check if its Stripe */
                    if ($user['current_subscription_gateway'] == "stripe" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "stripe"))
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

                            try
                            {
                                $stripe_cust = $this->stripe->cancelSubscription($user['stripe_sub_id']);
                            }
                            catch (\Stripe\Error\Base $e)
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_cancel_stripe_base_error_heading');
                                $temp['content'] = $this->lang->line('user_update_cancel_stripe_base_error_message') . $e->getMessage();
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }
                            catch (\Stripe\Error\Exception $e)
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                                $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . $e->getMessage();
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }

                            $this->MUsers->update_field($user['id'], 'stripe_sub_id', '');
                        }
                    }
                    /** Must be Paypal */
                    else if ($user['current_subscription_gateway'] == "paypal" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "paypal"))
                    {
                        /** Cancel current subscription */
                        $paypal_Settings = $this->MPayments->get_all();
                        foreach ($paypal_Settings as $setting)
                        {
                            if ($setting->name == 'paypal_api_username')
                            {
                                $options['username'] = $setting->value;
                            }
                            else if ($setting->name == 'paypal_api_password')
                            {
                                $options['password'] = $setting->value;
                            }
                            else if ($setting->name == 'paypal_api_signature')
                            {
                                $options['signature'] = $setting->value;
                            }
                            else if ($setting->name == 'paypal_test_mode')
                            {
                                $options['sandbox'] = (($setting->value == 'test') ? true : false);
                            }
                        }

                        $this->load->library('Paypal', $options);

                        $reason = $this->lang->line('package_changed_from') . ' ' . $user['package_name'] . ' (' . $user['package_price'] . ' ' . $user['package_currency'] . ') ' . $this->lang->line('package_changed_to') . ' ' . $package['name'] . ' (' . $package['price'] . ' ' . $package['currency'] . ')';

                        $response = $this->paypal->manage_recurring_profile_status($user['paypal_profile_id'], "Cancel", $reason);

                        if (isset($response['ACK']) && $response['ACK'] == "Success")
                        {
                            $this->MUsers->update_field($user['id'], 'paypal_profile_status', "Cancel");
                        }
                        else
                        {
                            $temp = array();
                            $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                            $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . '<br><b>' . urldecode($response['L_LONGMESSAGE0']) . '</b>';
                            $this->return = array();
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                            die(json_encode($this->return));
                        }

                        /** Do necessary refund */

                        /** Getting last transaction ID of user */
                        $data['transactionid'] = $user['paypal_last_transaction_id'];

                        /** Calculating prorate amount */
                        $response = $this->paypal->get_recurring_profile($user['paypal_profile_id']);
                        $userdata = $this->MUsers->get_by_id($user['id']);

                        if (isset($response['LASTPAYMENTDATE']))
                        {
                            $last_payment_date = urldecode($response['LASTPAYMENTDATE']);
                        }
                        else
                        {
                            $last_payment_date = urldecode($userdata['paypal_previous_payment_date']);
                        }

                        if (isset($response['LASTPAYMENTAMT']))
                        {
                            $last_payment_amt = urldecode($response['LASTPAYMENTAMT']);
                        }
                        else
                        {
                            $last_payment_amt = urldecode($userdata['package_price']);
                        }

                        $billing_period = urldecode($response['BILLINGPERIOD']); /** Day, Week, SemiMonth, Month, Year */
                        $billing_period_in_day = 0;
                        if ($billing_period == "Day")
                        {
                            $billing_period_in_day = 1;
                        }
                        else if ($billing_period == "Week")
                        {
                            $billing_period_in_day = 7;
                        }
                        else if ($billing_period == "SemiMonth")
                        {
                            $billing_period_in_day = 15;
                        }
                        else if ($billing_period == "Month")
                        {
                            $billing_period_in_day = 30;
                        }
                        else if ($billing_period == "Year")
                        {
                            $billing_period_in_day = 365;
                        }

                        $billing_period_in_days = $billing_period_in_day * (int)urldecode($response['BILLINGFREQUENCY']);

                        $one_day_cost = ((int)$last_payment_amt)/$billing_period_in_days;

                        $date_1 = new DateTime(date("Y-m-d", strtotime($last_payment_date))); /** Last payment date */
                        $date_2 = new DateTime(date("Y-m-d")); /** Current date */
                        $subscription_days_till_date = (int)$date_2->diff($date_1)->format("%a");
                        if ($subscription_days_till_date == 0)
                        {
                            $subscription_days_till_date = 1;
                        }

                        $total_billing_amt_till_date = $subscription_days_till_date * $one_day_cost;

                        $data['amount'] = $last_payment_amt - $total_billing_amt_till_date; /** Total amount to refund */

                        $data['note'] = $this->lang->line('refund_reason');

                        /** Processing Refund */
                        $response = $this->paypal->process_refund($data);

                        $data['user_id'] = $user['id'];
                        $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                        if ($response['ACK'] == "Success")
                        {
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($user['email']);
                            $this->email->subject($this->config->item('email_activation_subject'));
                            $body = $this->load->view('user/email/paypal_update.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }
                        else
                        {
                            /** Inform admin that Refund process was not successful. So do it manully. */
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($this->config->item('email_from_address'));
                            $this->email->subject($this->config->item('refund_failed_subject'));
                            $body = $this->load->view('user/email/paypal_refund_failed.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }
                    }

                    /** Update package in database */
                    $this->MUsers->update_field($user['id'], 'package_id', $package['id']);
                    /** Update package in session */
                    $this->session->set_userdata('package_id',$package['id']);

                    $temp = array();
                    $temp['header'] = $this->lang->line('user_package_update_paid_free_success_heading');
                    $temp['content'] = $this->lang->line('user_package_update_paid_free_success_message');
                    $this->return = array();
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_package_update_success');

                    die(json_encode($this->return));
                }
                /** User under a free package and update to a paid package. Subscribe user in payment gateway with new package. */
                else if ($user['package_price'] == 0 && $package['price'] != 0)
                {
                    $this->return = array();
                    $temp = array();

                    if($payment[0]->value == "stripe")
                    {
                        /** Check if user is already a stripe customer or not, if not create customer */
                        if ($user['stripe_cus_id'] == "")
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
                                $customer['email'] = $this->session->userdata('user_email');
                                $customer['description'] = $this->session->userdata('user_fname') . ' ' . $this->session->userdata('user_lname');
                                try
                                {
                                    $stripe_cust = $this->stripe->addCustomer($customer);
                                }
                                catch (\Stripe\Error\Base $e)
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_create_stripe_base_error_heading');
                                    $temp['content'] = $this->lang->line('user_create_stripe_base_error_message') . $e->getMessage();
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }
                                catch (\Stripe\Error\Exception $e)
                                {
                                    $temp = array();
                                    $temp['header'] = $this->lang->line('user_create_stripe_exception_error_heading');
                                    $temp['content'] = $this->lang->line('user_create_stripe_exception_error_message') . $e->getMessage();
                                    $this->return = array();
                                    $this->return['responseCode'] = 0;
                                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                    die(json_encode($this->return));
                                }

                                $this->MUsers->update_field($user['id'], 'stripe_cus_id', $stripe_cust['id']);
                            }
                        }

                        $this->return['redirect'] = 'user/payment_stripe/' . $user['id'] . '/' . $package['id'];
                        $temp['content'] = $this->lang->line('user_package_update_free_paid_success_message');
                    }
                    else
                    {
                        /** Paypal Code goes here, Since user is upgrading to paid from free so it is similar as fresh subscription. */

                        $this->return['redirect'] = 'user/payment_paypal/' . $user['id'] . '/' . $package['id'];
                        $temp['content'] = $this->lang->line('user_package_update_success_message');
                    }

                    $temp['header'] = $this->lang->line('user_package_update_free_paid_success_heading');

                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);
                    die(json_encode($this->return));
                }
                /** User under a paid package and update to another paid package. Cancel the current subscription from gateway and subscribe to the new package. */
                else
                {
                    $this->return = array();
                    $temp = array();

                    if ($user['current_subscription_gateway'] == "stripe")
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

                            try
                            {
                                $stripe_cust = $this->stripe->cancelSubscription($user['stripe_sub_id']);
                            }
                            catch (\Stripe\Error\Base $e)
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_cancel_stripe_base_error_heading');
                                $temp['content'] = $this->lang->line('user_update_cancel_stripe_base_error_message') . $e->getMessage();
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }
                            catch (\Stripe\Error\Exception $e)
                            {
                                $temp = array();
                                $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                                $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . $e->getMessage();
                                $this->return = array();
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                                die(json_encode($this->return));
                            }

                            $this->MUsers->update_field($user['id'], 'stripe_sub_id', '');
                        }

                        $this->return['redirect'] = 'user/payment_stripe/' . $user['id'] . '/' . $package['id'];
                        $temp['content'] = $this->lang->line('user_package_update_paid_paid_success_message');
                    }
                    else
                    {
                        /** Cancel current subscription */
                        $paypal_Settings = $this->MPayments->get_all();
                        foreach ($paypal_Settings as $setting)
                        {
                            if ($setting->name == 'paypal_api_username')
                            {
                                $options['username'] = $setting->value;
                            }
                            else if ($setting->name == 'paypal_api_password')
                            {
                                $options['password'] = $setting->value;
                            }
                            else if ($setting->name == 'paypal_api_signature')
                            {
                                $options['signature'] = $setting->value;
                            }
                            else if ($setting->name == 'paypal_test_mode')
                            {
                                $options['sandbox'] = (($setting->value == 'test') ? true : false);
                            }
                        }

                        $this->load->library('Paypal', $options);

                        $reason = $this->lang->line('package_changed_from') . ' ' . $user['package_name'] . ' (' . $user['package_price'] . ' ' . $user['package_currency'] . ') ' . $this->lang->line('package_changed_to') . ' ' . $package['name'] . ' (' . $package['price'] . ' ' . $package['currency'] . ')';

                        $response = $this->paypal->manage_recurring_profile_status($user['paypal_profile_id'], "Cancel", $reason);

                        if (isset($response['ACK']) && $response['ACK'] == "Success")
                        {
                            $this->MUsers->update_field($user['id'], 'paypal_profile_status', "Cancel");
                        }
                        else
                        {
                            $temp = array();
                            $temp['header'] = $this->lang->line('user_update_cancel_stripe_exception_error_heading');
                            $temp['content'] = $this->lang->line('user_update_cancel_stripe_exception_error_message') . '<br><b>' . urldecode($response['L_LONGMESSAGE0']) . '</b>';
                            $this->return = array();
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                            die(json_encode($this->return));
                        }

                        /** Do necessary refund */

                        /** Getting last transaction ID of user */
                        $data['transactionid'] = $user['paypal_last_transaction_id'];

                        /** Calculating prorate amount */
                        $response = $this->paypal->get_recurring_profile($user['paypal_profile_id']);
                        $userdata = $this->MUsers->get_by_id($user['id']);

                        if (isset($response['LASTPAYMENTDATE']))
                        {
                            $last_payment_date = urldecode($response['LASTPAYMENTDATE']);
                        }
                        else
                        {
                            $last_payment_date = urldecode($userdata['paypal_previous_payment_date']);
                        }

                        if (isset($response['LASTPAYMENTAMT']))
                        {
                            $last_payment_amt = urldecode($response['LASTPAYMENTAMT']);
                        }
                        else
                        {
                            $last_payment_amt = urldecode($userdata['package_price']);
                        }

                        $billing_period = urldecode($response['BILLINGPERIOD']); /** Day, Week, SemiMonth, Month, Year */
                        $billing_period_in_day = 0;
                        if ($billing_period == "Day")
                        {
                            $billing_period_in_day = 1;
                        }
                        else if ($billing_period == "Week")
                        {
                            $billing_period_in_day = 7;
                        }
                        else if ($billing_period == "SemiMonth")
                        {
                            $billing_period_in_day = 15;
                        }
                        else if ($billing_period == "Month")
                        {
                            $billing_period_in_day = 30;
                        }
                        else if ($billing_period == "Year")
                        {
                            $billing_period_in_day = 365;
                        }

                        $billing_period_in_days = $billing_period_in_day * (int)urldecode($response['BILLINGFREQUENCY']);

                        $one_day_cost = ((int)$last_payment_amt)/$billing_period_in_days;

                        $date_1 = new DateTime(date("Y-m-d", strtotime($last_payment_date))); /** Last payment date */
                        $date_2 = new DateTime(date("Y-m-d")); /** Current date */
                        $subscription_days_till_date = (int)$date_2->diff($date_1)->format("%a");
                        if ($subscription_days_till_date == 0)
                        {
                            $subscription_days_till_date = 1;
                        }

                        $total_billing_amt_till_date = $subscription_days_till_date * $one_day_cost;

                        $data['amount'] = $last_payment_amt - $total_billing_amt_till_date; /** Total amount to refund */

                        $data['note'] = $this->lang->line('refund_reason');

                        /** Processing Refund */
                        $response = $this->paypal->process_refund($data);

                        $data['user_id'] = $user['id'];
                        $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                        if ($response['ACK'] == "Success")
                        {
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($user['email']);
                            $this->email->subject($this->config->item('email_activation_subject'));
                            $body = $this->load->view('user/email/paypal_update.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }
                        else
                        {
                            /** Inform admin that Refund process was not successful. So do it manully. */
                            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                            $this->email->to($this->config->item('email_from_address'));
                            $this->email->subject($this->config->item('refund_failed_subject'));
                            $body = $this->load->view('user/email/paypal_refund_failed.tpl.php', $data, TRUE);
                            $this->email->message($body);

                            $this->email->send();
                        }

                        /** Paypal Code goes here, Since user is upgrading to paid from cancel account so it is similar as fresh subscription. **/

                        $this->return['redirect'] = 'user/payment_paypal/' . $user['id'] . '/' . $package['id'];
                        $temp['content'] = $this->lang->line('user_package_update_paid_paid_success_message');
                    }

                    //$this->MUsers->update_field($user['id'], 'package_id', $package['id']);
                    //$this->session->set_userdata('package_id',$package['id']);
                    /** Force user status inactive */
                    //$this->MUsers->update_field($user['id'], 'status', 'Inactive');

                    $temp['header'] = $this->lang->line('user_package_update_paid_paid_success_heading');

                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

                    /** Hook point */
                    $this->hooks->call_hook('user_package_update_success');

                    die(json_encode($this->return));
                }
            }
            else
            {
                $temp = array();
                $temp['header'] = $this->lang->line('user_package_update_no_change_error_heading');
                $temp['content'] = $this->lang->line('user_package_update_no_change_error_message');
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                /** Hook point */
                $this->hooks->call_hook('user_package_update_error');

                die(json_encode($this->return));
            }
        }
    }

    /**
     * Stripe paymet for package update.
     *
     * @param  integer  $user_id
     * @return void
     */
    public function payment_stripe($user_id = NULL, $package_id = NULL)
    {
        if ($this->input->post())
        {
            $user_id = $this->input->post('user_id');
            $package_id = $this->input->post('package_id');
            $cust_id = $this->input->post('cust_id');
            $plan = $this->input->post('plan');

            $stripe_test_mode = $this->MPayments->get_by_name('stripe_test_mode');
            $stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');
            $stripe_publishable_key = $this->MPayments->get_by_name('stripe_publishable_key');
            if ($stripe_secret_key[0]->value != "")
            {
                $options['mode'] = $stripe_test_mode[0]->value;
                $options['stripe_secret_key'] = $stripe_secret_key[0]->value;
                $options['stripe_publishable_key'] = $stripe_publishable_key[0]->value;
                $this->load->library('Stripe', $options);

                /** Stripe subscribe plan */
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
                    $this->session->set_flashdata('error', $this->lang->line('stripe_token_base_error') . '<b>' . $e->getMessage() . '</b>');
                    redirect('user/payment_stripe/' . $user_id . '/' . $package_id, 'refresh');
                }
                catch (\Stripe\Error\Exception $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('stripe_token_exception_error') . '<b>' . $e->getMessage() . '</b>');
                    redirect('user/payment_stripe/' . $user_id . '/' . $package_id, 'refresh');
                }

                /** Stripe subscribe plan */
                $sub_opt = [
                    'customer'      => $cust_id,
                    'items'         => [
                        [
                            'plan'  => $plan
                        ]
                    ]
                ];
                try
                {
                    $subscribe = $this->stripe->create_subscription($sub_opt);
                    $this->MUsers->update_field($user_id, 'stripe_sub_id', $subscribe['id']);
                    /** Update package in database */
                    $this->MUsers->update_field($user_id, 'package_id', $package_id);
                    /** Update package in session */
                    $this->session->set_userdata('package_id', $package_id);

                    $this->session->set_flashdata('success', $this->lang->line('user_payment_stripe_payment_success'));
                }
                catch (\Stripe\Error\Base $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('user_payment_stripe_payment_base_error') . '<b>' . $e->getMessage() . '</b>');
                    redirect('user/payment_stripe/' . $user_id . '/' . $package_id, 'refresh');
                }
                catch (\Stripe\Error\Exception $e)
                {
                    $this->session->set_flashdata('error', $this->lang->line('user_payment_stripe_payment_exception_error') . '<b>' . $e->getMessage() . '</b>');
                    redirect('user/payment_stripe/' . $user_id . '/' . $package_id, 'refresh');
                }
            }

            redirect('sites', 'refresh');
        }
        else
        {
            $this->data['content'] = 'payment_stripe';
            $this->data['page'] = 'user';
            $this->data['user'] = $this->MUsers->get_by_id($user_id);
            $this->data['package'] = $this->MPackages->get_by_id($package_id);
            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * PayPal payment for package update
     *
     * @param  string   $user_id
     * @return void
     */
    public function payment_paypal($user_id = NULL, $package_id = NULL)
    {
        if ($this->input->post())
        {
            //print_r($this->input->post()); die();
            $user = $this->MUsers->get_by_id($this->input->post('user_id'));
            $package = $this->MPackages->get_by_id($this->input->post('package_id'));

            $paypal_Settings = $this->MPayments->get_all();
            foreach ($paypal_Settings as $setting)
            {
                if ($setting->name == 'paypal_api_username')
                {
                    $options['username'] = $setting->value;
                }
                else if ($setting->name == 'paypal_api_password')
                {
                    $options['password'] = $setting->value;
                }
                else if ($setting->name == 'paypal_api_signature')
                {
                    $options['signature'] = $setting->value;
                }
                else if ($setting->name == 'paypal_test_mode')
                {
                    $options['sandbox'] = (($setting->value == 'test') ? true : false);
                }
            }

            $this->load->library('Paypal', $options);

            $requestdata = array();
            $requestdata['returnurl'] = base_url() . 'user/payment_paypal_confirmation/' . $package['id'];
            $requestdata['cancelurl'] = base_url() . 'sites';

            $requestdata['name'] = $package['name'];
            $requestdata['description'] = $package['name'];
            $requestdata['amount'] = $package['price'];
            $requestdata['qty'] = 1;
            $requestdata['currency'] = $package['currency'];
            $requestdata['subscription_info'] = $package['subscription']; /** this will show package period like Monthly */

            $response = $this->paypal->get_token($requestdata);
            //print_r($response); die();
            if ( ! is_array($response))
            {
                $this->MUsers->update_field($user_id, 'paypal_token', urldecode($response));
                $this->paypal->do_express_checkout($response);
            }
            else
            {
                $this->session->set_flashdata('error', $this->lang->line('user_payment_paypal_getting_token_error') . '<br><b>' . urldecode($response['L_LONGMESSAGE0']) . '</b>');
                redirect('sites', 'refresh');
            }
        }
        else
        {
            $this->data['content'] = 'payment_paypal';
            $this->data['page'] = 'user';
            $this->data['user'] = $this->MUsers->get_by_id($user_id);
            $this->data['package'] = $this->MPackages->get_by_id($package_id);
            $this->load->view('shared/layout', $this->data);
        }
    }

    /**
     * PayPal payment confirmation
     *
     * @param   string  $existing
     * @return  void
     */
    public function payment_paypal_confirmation($package_id = NULL)
    {
        if (isset($_GET['token']))
        {
            $token = $_GET['token'];
            $user = $this->MUsers->get_by_paypal_token($token);
            $package = $this->MPackages->get_by_id($package_id);

            $paypal_Settings = $this->MPayments->get_all();
            foreach ($paypal_Settings as $setting)
            {
                if ($setting->name == 'paypal_api_username')
                {
                    $options['username'] = $setting->value;
                }
                else if ($setting->name == 'paypal_api_password')
                {
                    $options['password'] = $setting->value;
                }
                else if ($setting->name == 'paypal_api_signature')
                {
                    $options['signature'] = $setting->value;
                }
                else if ($setting->name == 'paypal_test_mode')
                {
                    $options['sandbox'] = (($setting->value == 'test') ? true : false);
                }
            }

            $this->load->library('Paypal', $options);

            $requestdata['name'] = $package['name'];
            $requestdata['description'] = $package['name'];
            $requestdata['amount'] = $package['price'];
            $requestdata['qty'] = 1;
            $requestdata['currency'] = $package['currency'];
            $requestdata['subscription_info'] = $package['subscription']; /** this will show package period like Monthly */
            $requestdata['max_fail_payment'] = 3;

            $next_payment_days = 0;
            /** Allowed values of subscription period by Paypal are: Day, Week, SemiMonth, Month, Year */
            if ($package['subscription'] == 'Weekly')
            {
                $requestdata['billing_period'] = 'Week';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 7;
            }
            else if ($package['subscription'] == 'Monthly')
            {
                $requestdata['billing_period'] = 'Month';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 30;
            }
            else if ($package['subscription'] == 'Yearly')
            {
                $requestdata['billing_period'] = 'Year';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 365;
            }
            else if ($package['subscription'] == 'Every 3 months')
            {
                $requestdata['billing_period'] = 'Month';
                $requestdata['billing_frequency'] = "3";
                $next_payment_days = 90;
            }
            else if ($package['subscription'] == 'Every 6 months')
            {
                $requestdata['billing_period'] = 'SemiMonth';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 180;
            }
            else
            {
                $requestdata['billing_period'] = "Monthy";
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 30;
            }

            $requestdata['start_date'] = date('Y-m-d', strtotime("+$next_payment_days days"));

            $response = $this->paypal->create_recurring_profile($token, $requestdata);

            if ($response['ACK'] == "Success")
            {
                $profileid = $response['PROFILEID'];
                $profiledetails = $this->paypal->get_recurring_profile($profileid);
                $nextpayementdate = $profiledetails['NEXTBILLINGDATE'];

                /** Udating profile id used for subscription. */
                $this->MUsers->update_field($user['id'], 'paypal_profile_id', urldecode($response['PROFILEID']));
                $this->MUsers->update_field($user['id'], 'paypal_profile_status', 'Active');
                $this->MUsers->update_field($user['id'], 'paypal_last_transaction_id', urldecode($response['transactionid']));
                $this->MUsers->update_field($user['id'], 'payer_id', urldecode($response['payerid']));
                $this->MUsers->update_field($user['id'], 'paypal_next_payment_date', urldecode($nextpayementdate));
                $this->MUsers->update_field($user['id'], 'current_subscription_gateway', "paypal");
                $this->MUsers->update_field($user['id'], 'paypal_previous_payment_date', urldecode($response['previous_payment_date']));

                /** Update package in database */
                $this->MUsers->update_field($user['id'], 'package_id', $package['id']);
                /** Update package in session */
                $this->session->set_userdata('package_id', $package['id']);

                /** Setting success message */
                $this->session->set_flashdata('success', $this->lang->line('user_payment_paypal_payment_success'));
            }
            else
            {
                /** Payment done but subscription cannot be initialized. In this case we need to retry for creating subscription. */

                /** Setting success message */
                $this->session->set_flashdata('error', $this->lang->line('user_payment_paypal_subscription_error'));
            }

            redirect('sites', 'refresh');
        }
        else
        {
            redirect('auth', 'refresh');
        }
    }

    /**
     * PayPal payment fail
     *
     * @param   string  $existing
     * @return  void
     */
    public function payment_failed($existing = '')
    {
        /** In this case we will not get payment via paypal, then we need to prompt user to pay again. In future we can limit how many time user can retry for payment */

        if (isset($_GET['token']))
        {
            $token = $_GET['token'];
            $user = $this->MUsers->get_by_paypal_token($token);

            $this->session->set_flashdata('error', $this->lang->line('payment_fail_try_again'));

            if ($existing == 'existing')
            {
                redirect('subscription/paypal/' . $user['id'] . '/existing', 'refresh');
            }
            else
            {
                redirect('subscription/paypal/' . $user['id'], 'refresh');
            }
        }
        else
        {
            redirect('auth', 'refresh');
        }
    }

    /**
     * Cancle subscription of user package
     *
     * @return  json    $return
     */
    public function package_cancel()
    {
        /** Hook point */
        $this->hooks->call_hook('user_package_cancel_pre');

        if ($this->input->post())
        {
            $user = $this->MUsers->get_by_id($this->input->post('user_id'));
            $payment = $this->MPayments->get_by_name('payment_gateway');

            if($user['current_subscription_gateway'] == "stripe" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "stripe") )
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

                    try
                    {
                        $this->stripe->cancelSubscription($user['stripe_sub_id'], TRUE);
                        $temp = array();
                        $temp['header'] = $this->lang->line('user_package_cancel_success_heading');
                        $temp['content'] = $this->lang->line('user_package_cancel_success_message');
                        $this->return = array();
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);

                        /** Hook point */
                        $this->hooks->call_hook('user_package_cancel_success');

                        die(json_encode($this->return));
                    }
                    catch (\Stripe\Error\Base $e)
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('user_package_cancel_stripe_base_error_heading');
                        $temp['content'] = $this->lang->line('user_package_cancel_stripe_base_error_message') . $e->getMessage();
                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                        die(json_encode($this->return));
                    }
                    catch (\Stripe\Error\Exception $e)
                    {
                        $temp = array();
                        $temp['header'] = $this->lang->line('user_package_cancel_stripe_exception_error_heading');
                        $temp['content'] = $this->lang->line('user_package_cancel_stripe_exception_error_message') . $e->getMessage();
                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                        die(json_encode($this->return));
                    }
                }
            }
            else if($user['current_subscription_gateway'] == "paypal" || ($user['current_subscription_gateway'] == "" && $payment[0]->value == "paypal") )
            {
                /** Paypal Code goes here */

                /** Cancel subscription */

                $paypal_Settings = $this->MPayments->get_all();
                foreach ($paypal_Settings as $setting)
                {
                    if ($setting->name == 'paypal_api_username')
                    {
                        $options['username'] = $setting->value;
                    }
                    else if ($setting->name == 'paypal_api_password')
                    {
                        $options['password'] = $setting->value;
                    }
                    else if ($setting->name == 'paypal_api_signature')
                    {
                        $options['signature'] = $setting->value;
                    }
                    else if ($setting->name == 'paypal_test_mode')
                    {
                        $options['sandbox'] = (($setting->value == 'test') ? true : false);
                    }
                }

                $this->load->library('Paypal', $options);

                $reason = $this->lang->line('user_package_cancel_success_message').' '.$user['package_name'].' ('.$user['package_price'].' '.$user['package_currency'].') ';

                /** Subscription cancel */

                $response = $this->paypal->manage_recurring_profile_status($user['paypal_profile_id'], "Cancel", $reason);

                $data['user_id'] = $user['id'];
                $data['name'] = $user['first_name'] . ' ' . $user['last_name'];

                $this->return = array();
                $temp = array();

                /** Response SUCCESS */
                if($response['ACK'] == "Success")
                {
                    $this->MUsers->update_field($user['id'], 'paypal_profile_status', 'Cancel');
                    $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                    $this->email->to($user['email']);
                    $this->email->subject($this->config->item('email_sub_cancel_subject'));
                    $body = $this->load->view('user/email/paypal_cancel.tpl.php', $data, TRUE);
                    $this->email->message($body);

                    $this->email->send();

                    $temp['header'] = $this->lang->line('user_package_cancel_success_heading');
                    $temp['content'] = $this->lang->line('user_package_cancel_success_message');
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data'=>$temp), TRUE);
                }
                else
                {
                    /** Inform admin that Refund process was not successful. So do it manully. */
                    $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
                    $this->email->to($this->config->item('email_from_address'));
                    $this->email->subject($this->config->item('sub_cancel_failed_subject'));
                    $body = $this->load->view('user/email/paypal_cancel_failed.tpl.php', $data, TRUE);
                    $this->email->message($body);

                    $this->email->send();

                    $temp['header'] = $this->lang->line('user_package_cancel_error_heading');
                    $temp['content'] = $this->lang->line('user_package_cancel_error_message');
                    $this->return['responseCode'] = 0;
                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                }
                die(json_encode($this->return));
            }

            {
                $temp = array();
                $temp['header'] = $this->lang->line('user_package_cancel_error_heading');
                $temp['content'] = $this->lang->line('user_package_cancel_error_message');
                $this->return = array();
                $this->return['responseCode'] = 0;
                $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);

                /** Hook point */
                $this->hooks->call_hook('user_package_cancel_error');

                die(json_encode($this->return));
            }
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
        $this->hooks->call_hook('user_destruct');
    }

    public function change_type ($user_id) {
        $user = $this->MUsers->get_by_id($user_id);
        $field = 'type';
        $value = $user['type'] == "Admin" ? 'User' : 'Admin';

        $this->MUsers->update_field($user_id, $field, $value);

        redirect('user', 'refresh');
    }

    public function search()
    {
        $offset = $_POST['start'];
        $search_value = $_POST['search']['value'];
        $limit = 10;

        if(isset($_POST['length'])){
            $limit = $_POST['length'];
        }

        $result = $this->MUsers->search($search_value, $limit, $offset);

        $pagination_args = [
            'recordsTotal' => empty($result) ? 0 : $result['total'],
            'recordsFiltered' => empty($result) ? 0 : $result['number'],
            'data' => empty($result) ? [] : $result['data'],
            'draw' => intval($_POST['draw']),
        ];

        echo json_encode($pagination_args);
    }

    public function getPackages()
    {
        $gateway = $this->MPayments->get_by_name('payment_gateway');
        $packages = $this->MPackages->get_all($gateway[0]->value, true);

        echo json_encode($packages);
    }

    public function setPackage($user_id,$package_id)
    {
        $this->MUsers->update_field($user_id, 'package_id', $package_id);

        redirect('user', 'refresh');
    }
}