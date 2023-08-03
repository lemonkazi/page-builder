<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends MY_Controller {

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
            'settings/Payment_settings_model' => 'MPayments',
            'settings/Core_settings_model' => 'MCores'
        ];
        $this->load->model($model_list);

        $this->hooks = load_class('Hooks', 'core');
        $this->data = [];

        /** Hook point */
        $this->hooks->call_hook('subscription_construct');
    }

    /**
     * User subscribe for Paypal
     *
     * @param   integer     $user_id
     * @return  void
     */
    public function paypal($user_id = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('subscription_paypal_pre');

        /** Checking if we get user_id for which we need to make subscription */
        if ($user_id)
        {
            $user = $this->MUsers->get_by_id($user_id);
            if ( ! empty($user))
            {
                if ($this->input->post())
                {
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
                    $requestdata['returnurl'] = base_url() . "subscription/payment_confirmation";
                    $requestdata['cancelurl'] = base_url() . "subscription/payment_failed";
                    $requestdata['name'] = $user['package_name'];
                    $requestdata['description'] = $user['package_name'];
                    $requestdata['amount'] = $user['package_price'];
                    $requestdata['qty'] = 1;
                    $requestdata['currency'] = $user['package_currency'];
                    $requestdata['subscription_info'] = $user['package_subscription']; /** this will show package period like Monthly */

                    $response = $this->paypal->get_token($requestdata);
                    if ( ! is_array($response))
                    {
                        $this->MUsers->update_field($user_id, 'paypal_token', urldecode($response));
                        $this->paypal->do_express_checkout($response);
                    }
                    else
                    {
                        $this->session->set_flashdata('error', $this->lang->line('paypal_getting_token_error'));
                        redirect('subscription/paypal/' . $user_id, 'refresh');
                    }
                }

                $this->data['title'] = $this->lang->line('auth_index_title');
                $this->data['content'] = 'subscription';
                $this->data['page'] = 'subscription';
                $this->data['user'] = $user;

                /** Hook point */
                $this->hooks->call_hook('subscription_paypal_post');

                $this->load->view('shared/layout', $this->data);
            }
            else
            {
                redirect('auth', 'refresh');
            }
        }
        else
        {
            redirect('auth', 'refresh');
        }
    }

    /**
     * Payment Information
     *
     * @return  void
     */
    public function payment_confirmation()
    {
        /** Hook point */
        $this->hooks->call_hook('subscription_payment_confirmation_pre');

        if (isset($_GET['token']))
        {
            $token = $_GET['token'];
            $user = $this->MUsers->get_by_paypal_token($token);

            /** Update user account active and profile id. */
            $this->MUsers->update_status('Active', $user['id']);

            $paypal_Settings = $this->MPayments->get_all();
            foreach($paypal_Settings as $setting)
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

            $requestdata['name'] = $user['package_name'];
            $requestdata['description'] = $user['package_name'];
            $requestdata['amount'] = $user['package_price'];
            $requestdata['qty'] = 1;
            $requestdata['currency'] = $user['package_currency'];
            $requestdata['subscription_info'] = $user['package_subscription']; /** this will show package period like Monthly */
            $requestdata['max_fail_payment'] = 3;

            $next_payment_days = 0;
            /** Allowed values of subscription period by Paypal are: Day, Week, SemiMonth, Month, Year */
            if ($user['package_subscription'] == 'Weekly')
            {
                $requestdata['billing_period'] = 'Week';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 7;
            }
            else if ($user['package_subscription'] == 'Monthly')
            {
                $requestdata['billing_period'] = 'Month';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 30;
            }
            else if ($user['package_subscription'] == 'Yearly')
            {
                $requestdata['billing_period'] = 'Year';
                $requestdata['billing_frequency'] = "1";
                $next_payment_days = 365;
            }
            else if ($user['package_subscription'] == 'Every 3 months')
            {
                $requestdata['billing_period'] = 'Month';
                $requestdata['billing_frequency'] = "3";
                $next_payment_days = 90;
            }
            else if ($user['package_subscription'] == 'Every 6 months')
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

            $data['name'] = $user['first_name'] . ' ' . $user['last_name'];
            $data['subscription'] = false;
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

                $data['subscription'] = true;

                /** Setting success message */
                $this->session->set_flashdata('success', $this->lang->line('auth_payment_paypal_payment_success'));
            }
            else
            {
                /** Payment done but subscription cannot be initialized. In this case we need to retry for creating subscription. */

                /** Setting success message */
                $this->session->set_flashdata('success', $this->lang->line('auth_payment_paypal_subscription_error'));
            }

            $this->email->from($this->config->item('email_from_address'), $this->config->item('email_from_name'));
            $this->email->to($user['email']);
            $this->email->subject($this->config->item('email_confirmation_subject'));
            $body = $this->load->view('subscription/email/paypal_payment_confirmation.php', $data, TRUE);
            $this->email->message($body);

            $this->email->send();

            /** Hook point */
            $this->hooks->call_hook('subscription_payment_confirmation_post');

            redirect('auth', 'refresh');
        }
        else
        {
            redirect('auth', 'refresh');
        }
    }

    /**
     * Payment Fail
     *
     * @return  void
     */
    public function payment_failed()
    {
        /** Hook point */
        $this->hooks->call_hook('subscription_payment_failed_post');

        /** In this case we will not get payment via paypal, then we need to prompt user to pay again. In future we can limit how many time user can retry for payment */

        if (isset($_GET['token']))
        {
            $token = $_GET['token'];
            $user = $this->MUsers->get_by_paypal_token($token);

            $this->session->set_flashdata('error', $this->lang->line('payment_fail_try_again'));

            /** Hook point */
            $this->hooks->call_hook('subscription_payment_failed_post');

            redirect('subscription/paypal/' . $user['id'], 'refresh');
        }
        else
        {
            redirect('auth', 'refresh');
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
        $this->hooks->call_hook('subscription_destruct');
    }

}