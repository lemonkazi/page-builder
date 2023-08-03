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
class Packages extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Is the API enabled?
        if ( !$this->config->item('rest_on') )
        {
            $this->response(['API disabled'], 400);
        }

        $model_list = [
            'user/Users_model' => 'MUsers',
            'package/Packages_model' => 'MPackages',
            'settings/Payment_settings_model' => 'MPayments'
        ];
        $this->load->model($model_list);

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
    }

    public function index_get($package_id = false)
    {

        $error = false;
        $errors = Array();

        if ( $package_id )
        {
            $this->db->from('packages');
            $this->db->where('id', $package_id);
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "package ID does not belong to a valid package";
            }
        }

        if ( !$error )
        {

            if ( !$package_id ) // All packages
            {
                $this->db->from('packages');
                $q = $this->db->get();

                $this->response( $q->result() );
            }
            else // Single package
            {
                $this->db->from('packages');
                $this->db->where('id', $package_id);
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

        /** check the name */
        if ( !isset($parameters['name']) || preg_replace('/\s+/', '', $parameters['name']) === '' ) 
        {
            $error = true;
            $errors[] = "name missing or incorrect; should be a valid string value";
        }

        /** check the sites_number */
        if ( !isset($parameters['sites_number']) || preg_replace('/\s+/', '', $parameters['sites_number']) === '' || !is_numeric($parameters['sites_number']) ) 
        {
            $error = true;
            $errors[] = "sites_number missing or incorrect; should be a valid number value";
        }

        /** check the price */
        if ( !isset($parameters['price']) || preg_replace('/\s+/', '', $parameters['price']) === '' || !is_numeric($parameters['price']) ) 
        {
            $error = true;
            $errors[] = "price missing or incorrect; should be a valid number value";
        }

        /** check the currency */
        if ( !isset($parameters['currency']) || preg_replace('/\s+/', '', $parameters['currency']) === '' || strlen($parameters['currency']) !== 3 ) 
        {
            $error = true;
            $errors[] = "currency missing or incorrect; should be a valid, three character string representing a currency.";
        }

        /** check the subscription */
        if ( !isset($parameters['subscription']) || preg_replace('/\s+/', '', $parameters['subscription']) === '' || ( $parameters['subscription'] != 'Weekly' && $parameters['subscription'] != 'Monthly' && $parameters['subscription'] != 'Yearly' && $parameters['subscription'] != 'Every 3 months' && $parameters['subscription'] != 'Every 6 months' ) ) 
        {
            $error = true;
            $errors[] = "subscription missing or incorrect; should be a valid string value (either 'Weekly', 'Monthly', 'Yearly', 'Every 3 months' or 'Every 6 months')";
        }

        /** check the hosting_option */
        if ( isset($parameters['hosting_option']) ) 
        {

            $hosting_options = json_decode($parameters['hosting_option'], TRUE);
            //die(print_r($hosting_options));

            if ( !is_array($hosting_options) )
            {
                $error = true;
                $errors[] = "hosting_option incorrect; should be a valid array with three possible values: 'Sub-Folder', 'Sub-Domain' or 'Custom Domain'";
            }
            else
            {
                foreach( $hosting_options as $hosting_option )
                {
                    if ( $hosting_option != 'Sub-Folder' && $hosting_option != 'Sub-Domain' && $hosting_option != 'Custom Domain' )
                    {
                        $error = true;
                        $errors[] = "hosting_option values incorrect; should be a valid array with three possible values: 'Sub-Folder', 'Sub-Domain' or 'Custom Domain'";
                    }
                }

                $hosting_options = array_unique($hosting_options);
                $parameters['hosting_option'] = json_encode($hosting_options);

            }

        }
        else{
            $parameters['hosting_option'] = 'null';
        }

        /** check the export_site */
        if ( !isset($parameters['export_site']) || preg_replace('/\s+/', '', $parameters['export_site']) === '' || ( $parameters['export_site'] != 'yes' && $parameters['export_site'] != 'no' ) ) 
        {
            $error = true;
            $errors[] = "export_site missing or incorrect; should be a valid string value (either 'yes' or 'no')";
        }

        /** check the ftp_publish */
        if ( !isset($parameters['ftp_publish']) || preg_replace('/\s+/', '', $parameters['ftp_publish']) === '' || ( $parameters['ftp_publish'] != 'yes' && $parameters['ftp_publish'] != 'no' ) ) 
        {
            $error = true;
            $errors[] = "ftp_publish missing or incorrect; should be a valid string value (either 'yes' or 'no')";
        }

        /** check the disk_space */
        if ( isset($parameters['disk_space']) ) 
        {

            if ( preg_replace('/\s+/', '', $parameters['disk_space']) === '' || !is_numeric($parameters['disk_space']) )
            {
                $error = true;
                $errors[] = "disk_space incorrect; should be a valid numeric value";
            }

        }

        /** check the templates */
        if ( isset($parameters['templates']) ) 
        {

            $templates = json_decode($parameters['templates'], TRUE);

            if ( !is_array($templates) )
            {
                $error = true;
                $errors[] = 'templates incorrect; should be a valid array with with a template ID (number) as each value (for example: ["10", "20"])';
            }
            else
            {
                if ( count($templates) > 0 ) {

                    foreach( $templates as $template ) //make sure each ID is a numeric value
                    {
                        if ( !is_numeric($template) )
                        {
                            $error = true;
                            $errors[] = 'templates values incorrect; should be a valid array with a template ID for each entry (for example: ["10", "20"])';
                        }
                    }

                    /** make sure the provided template ID's are valid */
                    $this->db->from('pages');
                    $this->db->select('pages_id');
                    $this->db->where('pages_template', 1);
                    $this->db->where_in('pages_id', $templates);

                    $q = $this->db->get();

                    $temp = Array();

                    foreach( $q->result() as $row )
                    {
                        $temp[] = $row->pages_id;
                    }

                    foreach( $templates as $template )
                    {
                        if ( !in_array($template, $temp) )
                        {
                            $error = true;
                            $errors[] = 'templates ID incorrect; the provided template ID ' . $template . ' does not match any existing templates';
                        }
                    }

                }
                else
                {
                    $error = true;
                    $errors[] = 'templates incorrect; should be a valid array with with a template ID (number) as each value (for example: ["10", "20"])';
                }

            }

        }
        else 
        {
            $templates = null;
        }

        /** check the blocks */
        if ( isset($parameters['blocks']) )
        {

            $blocks = json_decode($parameters['blocks'], TRUE);

            if ( !is_array($blocks) )
            {
                $error = true;
                $errors[] = 'blocks incorrect; should be a valid array with with a block category ID (number) as each value (for example: ["10", "20"]) or an empty array ([])';
            }
            else
            {
                if ( count($blocks) > 0 ) {

                    foreach( $blocks as $blockcat ) //make sure each ID is a numeric value
                    {
                        if ( !is_numeric($blockcat) ) 
                        {
                            $error = true;
                            $errors[] = 'blocks values incorrect; should be a valid array with a block category ID for each entry (for example: ["10", "20"]) or an empty array ([])';
                        }
                    }

                    /** make sure the provided category ID's are valid */
                    $this->db->from('blocks_categories');
                    $this->db->select('blocks_categories_id');
                    $this->db->where_in('blocks_categories_id', $blocks);

                    $q = $this->db->get();

                    $temp = Array();

                    foreach( $q->result() as $row )
                    {
                        $temp[] = $row->blocks_categories_id;
                    }

                    foreach( $blocks as $blockcat )
                    {
                        if ( !in_array($blockcat, $temp) )
                        {
                            $error = true;
                            $errors[] = 'block category ID incorrect; the provided ID ' . $blockcat . ' does not match any existing block category';
                        }
                    }

                }
            }

        }
        else
        {
            $blocks = null;
        }

        /** check the status */
        if ( !isset($parameters['status']) || preg_replace('/\s+/', '', $parameters['status']) === '' || ( $parameters['status'] != 'Active' && $parameters['status'] != 'Inactive' ) ) 
        {
            $error = true;
            $errors[] = "status missing or incorrect; should be a valid string value (either 'Active' or 'Inactive')";
        }

        if ( !$error )
        {

            /** Check payment gateway */
            $gateway = $this->MPayments->get_by_name('payment_gateway');
            if ($gateway[0]->value == 'stripe')
            {

                $stripe_secret_key = $this->MPayments->get_by_name('stripe_secret_key');

                if ($stripe_secret_key[0]->value != "" && $parameters['price'] != 0)
                {
                    /** Custom id prefix for product and plan */
                    $custom_id = str_replace(' ', '_', strtolower(trim($parameters['name']))) . '_' . trim($parameters['sites_number']) . '_' . str_replace(' ', '_', strtolower($parameters['subscription']));

                    /** Create product */
                    $product_opt = Array();
                    $product_opt['id'] = $custom_id . '_product';
                    $product_opt['name'] = trim($parameters['name']);
                    $product_opt['type'] = 'service';
                    $product_opt['statement_descriptor'] = $this->lang->line('package_create_statement_descriptor');

                    try
                    {
                        /** Create product in Stripe */
                        $this->stripe->add_product($product_opt);
                    }
                    catch (\Stripe\Error\Base $e)
                    {
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }
                    catch (\Stripe\Error\Exception $e)
                    {
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }

                    /** Create plan */
                    $plan_opt['id'] = $custom_id . '_plan';
                    $plan_opt['amount'] = $parameters['price'] * 100;
                    $plan_opt['currency'] = $parameters['currency'];
                    if ($parameters['subscription'] == 'Weekly')
                    {
                        $plan_opt['interval'] = 'week';
                    }
                    else if ($parameters['subscription'] == 'Monthly')
                    {
                        $plan_opt['interval'] = 'month';
                    }
                    else if ($parameters['subscription'] == 'Yearly')
                    {
                        $plan_opt['interval'] = 'year';
                    }
                    else if ($parameters['subscription'] == 'Every 3 months')
                    {
                        $plan_opt['interval'] = 'month';
                        $plan_opt['interval_count'] = 3;
                    }
                    else if ($parameters['subscription'] == 'Every 6 months')
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
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }
                    catch (\Stripe\Error\Exception $e)
                    {
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }

                    // Create the package in the database
                    $data = array(
                        'gateway'           => 'stripe',
                        'stripe_id'         => $plan_opt['id'],
                        'name'              => trim($parameters['name']),
                        'sites_number'      => $parameters['sites_number'],
                        'hosting_option'    => $parameters['hosting_option'],
                        'export_site'       => $parameters['export_site'],
                        'ftp_publish'       => $parameters['ftp_publish'],
                        'disk_space'        => $parameters['disk_space'],
                        'templates'         => json_encode($templates),
                        'blocks'            => json_encode($blocks),
                        'price'             => $parameters['price'],
                        'currency'          => $parameters['currency'],
                        'subscription'      => $parameters['subscription'],
                        'status'            => $parameters['status'],
                        'created_at'        => date('Y-m-d H:i:s', time())
                    );

                    $this->db->insert('packages', $data);

                    $packageID = $this->db->insert_id();

                }
                else // Free package
                {
                    // Create the package in the database
                    $data = array(
                        'gateway'           => 'stripe',
                        'name'              => trim($parameters['name']),
                        'sites_number'      => $parameters['sites_number'],
                        'hosting_option'    => $parameters['hosting_option'],
                        'export_site'       => $parameters['export_site'],
                        'ftp_publish'       => $parameters['ftp_publish'],
                        'disk_space'        => $parameters['disk_space'],
                        'templates'         => ($templates !==  null)? json_encode($templates) : $templates,
                        'blocks'            => ($blocks !== null)? json_encode($blocks) : $blocks,
                        'price'             => $parameters['price'],
                        'currency'          => $parameters['currency'],
                        'subscription'      => $parameters['subscription'],
                        'status'            => $parameters['status'],
                        'created_at'        => date('Y-m-d H:i:s', time())
                    );

                    $this->db->insert('packages', $data);

                    $packageID = $this->db->insert_id();

                }

            }
            else // PayPal package
            {

                /** Create the package in Bloxby system */
                $data = array(
                    'gateway'           => 'paypal',
                    'name'              => trim($parameters['name']),
                    'sites_number'      => $parameters['sites_number'],
                    'hosting_option'    => $parameters['hosting_option'],
                    'export_site'       => $parameters['export_site'],
                    'ftp_publish'       => $parameters['ftp_publish'],
                    'disk_space'        => $parameters['disk_space'],
                    'templates'         => ($templates !==  null)? json_encode($templates) : $templates,
                    'blocks'            => ($blocks !== null)? json_encode($blocks) : $blocks,
                    'price'             => $parameters['price'],
                    'currency'          => $parameters['currency'],
                    'subscription'      => $parameters['subscription'],
                    'status'            => $parameters['status'],
                    'created_at'        => date('Y-m-d H:i:s', time())
                );

                $this->db->insert('packages', $data);

                $packageID = $this->db->insert_id();

            }

            $response = Array();
            $response['status'] = 'success';
            $response['message'] = 'The new package was created successfully';
            $response['data']['package'] = $this->MPackages->get_by_id($packageID);

            $this->response($response);

        }
        else
        {
            $this->response($errors, 400);
        }

    }

    public function index_put($package_id = false)
    {

        $error = false;
        $errors = Array();
        $update = Array();

        $parameters = $this->put();

        /** Make sure the user_id is all kosher */
        if ( !$package_id )
        {
            $error = true;
            $errors[] = "package ID is missing from API url";
        }
        else
        {
            $this->db->from('packages');
            $this->db->where('id', $package_id);
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "package ID does not belong to a valid package";
            }
        }

        /** check the name */
        if ( isset( $parameters['name'] ) )
        {
            if ( preg_replace('/\s+/', '', $parameters['name']) === '' ) 
            {
                $error = true;
                $errors[] = "name is empty of invalid; should be a valid string value";
            }
            else
            {
                $update['name'] = $parameters['name'];
            }
        }

        /** check the sites_number */
        if ( isset( $parameters['sites_number'] ) )
        {
            if ( preg_replace('/\s+/', '', $parameters['sites_number']) === '' || !is_numeric($parameters['sites_number']) ) 
            {
                $error = true;
                $errors[] = "sites_number missing or incorrect; should be a valid number value";
            }
            else
            {
                $update['sites_number'] = $parameters['sites_number'];
            }
        }

        /** check the hosting_option */
        if ( isset($parameters['hosting_option']) ) 
        {

            $hosting_options = json_decode($parameters['hosting_option'], TRUE);
            $hosting_options_good = true;
            //die(print_r($hosting_options));

            if ( !is_array($hosting_options) )
            {
                $error = true;
                $errors[] = "hosting_option incorrect; should be a valid array with three possible values: 'Sub-Folder', 'Sub-Domain' or 'Custom Domain'";
                $hosting_options_good = false;
            }
            else
            {
                foreach( $hosting_options as $hosting_option )
                {
                    if ( $hosting_option != 'Sub-Folder' && $hosting_option != 'Sub-Domain' && $hosting_option != 'Custom Domain' )
                    {
                        $error = true;
                        $errors[] = "hosting_option values incorrect; should be a valid array with three possible values: 'Sub-Folder', 'Sub-Domain' or 'Custom Domain'";
                        $hosting_options_good = false;
                    }
                }

                $hosting_options = array_unique($hosting_options);
                $parameters['hosting_options'] = json_encode($hosting_options);
            }

            if ( $hosting_options_good )
            {
                $update['hosting_option'] = $parameters['hosting_options'];
            }

        }

        /** check the export_site */
        if ( isset( $parameters['export_site'] ) )
        {
            if ( preg_replace('/\s+/', '', $parameters['export_site']) === '' || ( $parameters['export_site'] != 'yes' && $parameters['export_site'] != 'no' ) ) 
            {
                $error = true;
                $errors[] = "export_site missing or incorrect; should be a valid string value (either 'yes' or 'no')";
            }
            else
            {
                $update['export_site'] = $parameters['export_site'];
            }
        }

        /** check the ftp_publish */
        if ( isset( $parameters['ftp_publish'] ) )
        {
            if ( preg_replace('/\s+/', '', $parameters['ftp_publish']) === '' || ( $parameters['ftp_publish'] != 'yes' && $parameters['ftp_publish'] != 'no' ) ) 
            {
                $error = true;
                $errors[] = "ftp_publish missing or incorrect; should be a valid string value (either 'yes' or 'no')";
            }
            else
            {
                $update['ftp_publish'] = $parameters['ftp_publish'];
            }
        }

        /** check the disk_space */
        if ( isset($parameters['disk_space']) ) 
        {

            if ( preg_replace('/\s+/', '', $parameters['disk_space']) === '' || !is_numeric($parameters['disk_space']) )
            {
                $error = true;
                $errors[] = "disk_space incorrect; should be a valid numeric value";
            }
            else
            {
                $update['disk_space'] = $parameters['disk_space'];
            }

        }

        /** check the templates */
        if ( isset($parameters['templates']) ) 
        {

            $templates = json_decode($parameters['templates'], TRUE);
            $templates_good = true;

            if ( !is_array($templates) )
            {
                $error = true;
                $errors[] = 'templates incorrect; should be a valid array with with a template ID (number) as each value (for example: ["10", "20"])';
                $templates_good = false;
            }
            else
            {
                if ( count($templates) > 0 ) {

                    foreach( $templates as $template ) //make sure each ID is a numeric value
                    {
                        if ( !is_numeric($template) )
                        {
                            $error = true;
                            $errors[] = 'templates values incorrect; should be a valid array with a template ID for each entry (for example: ["10", "20"])';
                            $templates_good = false;
                        }
                    }

                    /** make sure the provided template ID's are valid */
                    $this->db->from('pages');
                    $this->db->select('pages_id');
                    $this->db->where('pages_template', 1);
                    $this->db->where_in('pages_id', $templates);

                    $q = $this->db->get();

                    $temp = Array();

                    foreach( $q->result() as $row )
                    {
                        $temp[] = $row->pages_id;
                    }

                    foreach( $templates as $template )
                    {
                        if ( !in_array($template, $temp) )
                        {
                            $error = true;
                            $errors[] = 'templates ID incorrect; the provided template ID ' . $template . ' does not match any existing templates';
                            $templates_good = false;
                        }
                    }

                }
                else
                {
                    $error = true;
                    $errors[] = 'templates incorrect; should be a valid array with with a template ID (number) as each value (for example: ["10", "20"])';
                    $templates_good = false;
                }

            }

            if ( $templates_good )
            {
                $update['templates'] = json_encode($templates);
            }

        }

        /** check the blocks */
        if ( isset($parameters['blocks']) )
        {

            $blocks = json_decode($parameters['blocks'], TRUE);
            $blocks_good = true;

            if ( !is_array($blocks) )
            {
                $error = true;
                $errors[] = 'blocks incorrect; should be a valid array with with a block category ID (number) as each value (for example: ["10", "20"]) or an empty array ([])';
                $blocks_good = false;
            }
            else
            {
                if ( count($blocks) > 0 ) {

                    foreach( $blocks as $blockcat ) //make sure each ID is a numeric value
                    {
                        if ( !is_numeric($blockcat) ) 
                        {
                            $error = true;
                            $errors[] = 'blocks values incorrect; should be a valid array with a block category ID for each entry (for example: ["10", "20"]) or an empty array ([])';
                            $blocks_good = false;
                        }
                    }

                    /** make sure the provided category ID's are valid */
                    $this->db->from('blocks_categories');
                    $this->db->select('blocks_categories_id');
                    $this->db->where_in('blocks_categories_id', $blocks);

                    $q = $this->db->get();

                    $temp = Array();

                    foreach( $q->result() as $row )
                    {
                        $temp[] = $row->blocks_categories_id;
                    }

                    foreach( $blocks as $blockcat )
                    {
                        if ( !in_array($blockcat, $temp) )
                        {
                            $error = true;
                            $errors[] = 'block category ID incorrect; the provided ID ' . $blockcat . ' does not match any existing block category';
                            $blocks_good = false;
                        }
                    }

                }
                else
                {
                    $update['blocks'] = "[]";
                }
            }

            if ( $blocks_good )
            {
                $update['blocks'] = json_encode($blocks);
            }

        }

        /** check the status */
        if ( isset( $parameters['status'] ) )
        {
            if ( preg_replace('/\s+/', '', $parameters['status']) === '' || ( $parameters['status'] != 'Active' && $parameters['status'] != 'Inactive' ) ) 
            {
                $error = true;
                $errors[] = "status missing or incorrect; should be a valid string value (either 'Active' or 'Inactive')";
            }
            else
            {
                $update['status'] = $parameters['status'];
            }
        }



        if ( !$error )
        {

            /** Update the package in Bloxby system */
            $this->db->where('id', $package_id);
            $this->db->update('packages', $update);

            $package = $this->MPackages->get_by_id($package_id);

            $response = Array();
            $response['status'] = 'success';
            $response['message'] = 'The user account was successfully updated';
            $response['data']['package'] = $package;

            $this->response($response);

        }
        else
        {
            $this->response($errors, 400);
        }
    }

    public function index_delete($package_id = false)
    {
        
        $error = false;
        $errors = Array();

        /** Make sure the user_id is all kosher */
        if ( !$package_id )
        {
            $error = true;
            $errors[] = "package ID is missing from API url";
        }
        else
        {
            $this->db->from('packages');
            $this->db->where('id', $package_id);
            $q = $this->db->get();

            if ( $q->num_rows() == 0 )
            {
                $error = true;
                $errors[] = "package ID does not belong to a valid package";
            }
        }



        if ( !$error )
        {

            /** Check payment gateway */
            $gateway = $this->MPayments->get_by_name('payment_gateway');
            if ($gateway[0]->value == 'stripe')
            {
                /** Delete Stripe plan if needed */
                $package = $this->MPackages->get_by_id($package_id);
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
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }
                    catch (\Stripe\Error\Base $e)
                    {
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }
                    catch (\Stripe\Error\Exception $e)
                    {
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }

                    /** Delete product */
                    $product_id = str_replace('_plan', '_product', $plan_id);
                    try
                    {
                        $this->stripe->delete_product($product_id);
                    }
                    catch (\Stripe\Error\Base $e)
                    {
                        $error = true;
                        $errors[] = "Stripe API error: " . $e->getMessage();
                    }
                }
            }

            $this->MPackages->delete($package_id);

            $response = Array();
            $response['status'] = 'success';
            $response['message'] = 'The package was successfully deleted';

            $this->response($response);

        }
        else
        {
            $this->response($errors, 400);
        }

    }

}
