<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Users extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Is the API enabled?
        if ( !$this->config->item('rest_on') )
        {
            $this->response(['API disabled'], 400);
        }

        // User and pass
        $this->config->set_item('rest_valid_logins', [$this->config->item('integration_api_user'), $this->config->item('integration_api_pass')]);

        $model_list = [
            'user/Users_model' => 'MUsers',
            'package/Packages_model' => 'MPackages',
            'settings/Payment_settings_model' => 'MPayments',
            'sites/Sites_model' => 'MSites'
        ];
        $this->load->model($model_list);

        $this->load->helper('security');

    }

    public function index_get($user_id = false)
    {

        $error = false;
        $errors = Array();

        /** Make sure the user_id is all kosher */
        if ( $user_id )
        {
            $this->db->from('users');
            $this->db->where('id', $user_id);
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "user ID does not belong to a valid user";
            }
        }
        
        
        if ( !$error )
        {

            if ( !$user_id ) // Get all users
            {
                $this->db->from('users');
                $q = $this->db->get();

                $this->response( $q->result() );
            }
            else // Get single user
            {
                $this->db->from('users');
                $this->db->where('id', $user_id);
                $q = $this->db->get();

                $this->response( $q->result() );
            }

        }
        else
        {
            $this->response($errors, 400);
        }

    }

    public function index_post()
    {
        $error = false;
        $errors = Array();

        $parameters = $this->post();

        /** check account limits */
        if (bloxby_package() != 0 && count($this->MUsers->get_all('User', 'Active')) >= bloxby_package())
        {
            $error = true;
            $errors[] = "account limit reached; your Bloxby plan does not allow for additional end-users";
        }

        /** check the first_name */
        if ( !isset($parameters['first_name']) || preg_replace('/\s+/', '', $parameters['first_name']) === '' ) 
        {
            $error = true;
            $errors[] = "first_name missing or incorrect; should be a valid string value";
        }

        /** check the last_name */
        if ( !isset($parameters['last_name']) || preg_replace('/\s+/', '', $parameters['last_name']) === '' ) 
        {
            $error = true;
            $errors[] = "last_name missing or incorrect; should be a valid string value";
        }

        /** check the email */
        if ( !isset($parameters['email']) || preg_replace('/\s+/', '', $parameters['email']) === '' || !filter_var($parameters['email'], FILTER_VALIDATE_EMAIL) ) 
        {
            $error = true;
            $errors[] = "email missing or incorrect; should be a valid email address";
        }
        else
        {
            /** Make sure the email address is allowed */
            $user = $this->MUsers->get_by_email( $parameters['email'] );
            if (count($user) > 0)
            {
                $error = true;
                $errors[] = "email incorrect; email already used by existing user";
            }
        }

        /** check the password */
        if ( !isset($parameters['password']) || preg_replace('/\s+/', '', $parameters['password']) === '' ) 
        {
            $error = true;
            $errors[] = "password missing or incorrect; should be a valid string value";
        }

        /** check the type */
        if ( !isset($parameters['type']) || preg_replace('/\s+/', '', $parameters['type']) === '' || ( $parameters['type'] !== 'Admin' && $parameters['type'] !== 'User' ) ) 
        {
            $error = true;
            $errors[] = "type missing or incorrect (should be either 'User' or 'Admin')";
        }

        /** check the package_id */
        if ( !isset($parameters['package_id']) || preg_replace('/\s+/', '', $parameters['package_id']) === '' || !is_numeric($parameters['package_id']) ) 
        {

            $error = true;
            $errors[] = "package_id missing or incorrect; should be a valid number value";
        }
        else
        {
            /** make sure the package_id corresponds to an actual, active package */
            $this->db->from('packages');
            $this->db->where('id', $parameters['package_id']);
            $this->db->where('status', 'Active');
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "package_id incorrect; no matching package found";
            }
        }


        if ( !$error )
        {

            /** Check if its under free plan or paid plan */
            $package = $this->MPackages->get_by_id($this->input->post('package_id'));
            if ($package['price'] != 0)
            {
                
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

                    $data = array(
                        'package_id'                    => trim($parameters['package_id']),
                        'username'                      => trim($parameters['email']),
                        'email'                         => trim($parameters['email']),
                        'password'                      => substr(do_hash($parameters['password']), 0, 32),
                        'first_name'                    => trim($parameters['first_name']),
                        'last_name'                     => trim($parameters['last_name']),
                        'stripe_cus_id'                 => $stripe_cust['id'],
                        'current_subscription_gateway'  => 'stripe',
                        'type'                          => trim($parameters['type']),
                        'status'                        => 'Inactive',
                        'activation_code'               => substr(do_hash($parameters['email']), 0, 32),
                        'created_at'                    => date('Y-m-d H:i:s', time()),
                        'auto_login_token'              => random_string('alnum', 12)
                    );
                    $this->db->insert('users', $data);
                    $user_id = $this->db->insert_id();

                }
                else /** Else paypal is selected by Admin */
                {

                    $data = array(
                        'package_id'                    => trim($parameters['package_id']),
                        'username'                      => trim($parameters['email']),
                        'email'                         => trim($parameters['email']),
                        'password'                      => substr(do_hash($parameters['password']), 0, 32),
                        'first_name'                    => trim($parameters['first_name']),
                        'last_name'                     => trim($parameters['last_name']),
                        'stripe_cus_id'                 => NULL,
                        'current_subscription_gateway'  => 'paypal',
                        'type'                          => trim($parameters['type']),
                        'status'                        => 'Inactive',
                        'activation_code'               => substr(do_hash($parameters['email']), 0, 32),
                        'created_at'                    => date('Y-m-d H:i:s', time()),
                        'auto_login_token'              => random_string('alnum', 12)
                    );
                    $this->db->insert('users', $data);
                    $user_id = $this->db->insert_id();
                
                }

            }
            else
            {
                /** we can create the user here */
                $data = array(
                    'package_id'                    => trim($parameters['package_id']),
                    'username'                      => trim($parameters['email']),
                    'email'                         => trim($parameters['email']),
                    'password'                      => substr(do_hash($parameters['password']), 0, 32),
                    'first_name'                    => trim($parameters['first_name']),
                    'last_name'                     => trim($parameters['last_name']),
                    'stripe_cus_id'                 => NULL,
                    'current_subscription_gateway'  => 'stripe',
                    'type'                          => trim($parameters['type']),
                    'status'                        => 'Active',
                    'activation_code'               => substr(do_hash($parameters['email']), 0, 32),
                    'created_at'                    => date('Y-m-d H:i:s', time()),
                    'auto_login_token'              => random_string('alnum', 12)
                );
                $this->db->insert('users', $data);

                $user_id = $this->db->insert_id();
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

            $response = Array();
            $response['status'] = 'success';
            $response['message'] = 'The new user account was created successfully';
            $response['data']['user'] = $user;

            $this->response($response);

        }
        else
        {
            $this->response($errors, 400);
        }
    }

    public function index_put($user_id = false)
    {

        $error = false;
        $errors = Array();
        $update = Array();

        $parameters = $this->put();

        /** Make sure the user_id is all kosher */
        if ( !$user_id )
        {
            $error = true;
            $errors[] = "user ID is missing from API url";
        }
        else
        {
            $this->db->from('users');
            $this->db->where('id', $user_id);
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "user ID does not belong to a valid user";
            }
        }

        /** check the first_name */
        if ( isset($parameters['first_name']) )
        {
            if ( preg_replace('/\s+/', '', $parameters['first_name']) === '' )
            {
                $error = true;
                $errors[] = "first_name is empty of invalid; should be a valid string value";
            }
            else
            {
                $update['first_name'] = $parameters['first_name'];
            }
        }

        /** check the last_name */
        if ( isset($parameters['last_name']) )
        {
            if ( preg_replace('/\s+/', '', $parameters['last_name']) === '' )
            {
                $error = true;
                $errors[] = "last_name is empty of invalid; should be a valid string value";
            }
            else
            {
                $update['last_name'] = $parameters['last_name'];
            }
        }

        /** check the email */
        if ( isset($parameters['email']) )
        {
            if ( preg_replace('/\s+/', '', $parameters['email']) === '' || !filter_var($parameters['email'], FILTER_VALIDATE_EMAIL) )
            {
                $error = true;
                $errors[] = "email is empty of invalid; should be a valid email address";
            }
            else
            {   
                /** Make sure the email address is allowed */
                $user = $this->MUsers->get_by_email( $parameters['email'] );
                if (count($user) > 0)
                {
                    $error = true;
                    $errors[] = "email incorrect; email already used by existing user";
                }
                else
                {
                    $update['email'] = $parameters['email'];
                    $update['username'] = $parameters['email'];
                }
            }
        }

        /** check the password */
        if ( isset($parameters['password']) )
        {
            if ( preg_replace('/\s+/', '', $parameters['password']) === '' )
            {
                $error = true;
                $errors[] = "password is empty of invalid; should be a valid string value";
            }
            else
            {
                $update['password'] = substr(do_hash($parameters['password']), 0, 32);
            }
        }

        /** check the type */
        if ( isset($parameters['type']) )
        {
            if ( preg_replace('/\s+/', '', $parameters['type']) === '' || ( $parameters['type'] !== 'User' && $parameters['type'] !== 'Admin' ) )
            {
                $error = true;
                $errors[] = "type is empty of invalid (should be either 'User' or 'Admin')";
            }
            else
            {
                $update['type'] = $parameters['type'];
            }
        }

        /** check the status */
        if ( isset($parameters['status']) )
        {
            if ( preg_replace('/\s+/', '', $parameters['status']) === '' || ( $parameters['status'] !== 'Active' && $parameters['status'] !== 'Inactive' ) )
            {
                $error = true;
                $errors[] = "status is empty of invalid (should be either 'Active' or 'Inactive')";
            }
            else
            {
                $update['status'] = $parameters['status'];
            }
        }


        if ( !$error )
        {
            
            /** Let's update the user */
            $this->db->where('id', $user_id);
            $this->db->update('users', $update);

            $user = $this->MUsers->get_by_id($user_id);

            $response = Array();
            $response['status'] = 'success';
            $response['message'] = 'The user account was successfully updated';
            $response['data']['user'] = $user;

            $this->response($response);

        }
        else
        {
            $this->response($errors, 400);
        }
    }

    public function index_delete($user_id = false)
    {
        
        $error = false;
        $errors = Array();

        /** Make sure the user_id is all kosher */
        if ( !$user_id )
        {
            $error = true;
            $errors[] = "user ID is missing from API url";
        }
        else
        {
            $this->db->from('users');
            $this->db->where('id', $user_id);
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "user ID does not belong to a valid user";
            }
        }



        if ( !$error )
        {
            
            /** Trash all site of the user */
            $this->MSites->deleteAllFor($user_id);
            $this->MUsers->delete($user_id);

            $response = Array();
            $response['status'] = 'success';
            $response['message'] = 'The user account was successfully deleted; all connected sites were placed in the trash';

            $this->response($response);
            
        }
        else
        {
            $this->response($errors, 400);
        }

    }

}
