<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_settings_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get payment settings values by name
     * @return object   $q->result
     */
    public function get_by_name($name)
    {
        $this->db->where('name', $name);
        $this->db->limit(1);
	    $q = $this->db->get('payment_settings');
	    return $q->result();
    }

    /**
     * Get all payment settings values from the DB
     * @return object   $q->result
     */
    public function get_all()
    {
        $q = $this->db->get('payment_settings');
        return $q->result();
    }

    /**
     * updates the payment settings settings
     * @param  array    $value
     */
    public function update($value)
    {

	    //stripe_secret_key
	    $data = array(
        	'value' => $value['stripe_secret_key']
        );
        $this->db->where('id', 1);
        $this->db->update('payment_settings', $data);

        //stripe_publishable_key
	    $data = array(
        	'value' => $value['stripe_publishable_key']
        );
        $this->db->where('id', 2);
        $this->db->update('payment_settings', $data);

        //stripe_test_mode
        $data = array(
            'value' => $value['stripe_test_mode']
        );
        $this->db->where('id', 3);
        $this->db->update('payment_settings', $data);

        //paypal_api_username
        $data = array(
            'value' => $value['paypal_api_username']
        );
        $this->db->where('id', 4);
        $this->db->update('payment_settings', $data);

        //paypal_api_password
        $data = array(
            'value' => $value['paypal_api_password']
        );
        $this->db->where('id', 5);
        $this->db->update('payment_settings', $data);

        //paypal_api_signature
        $data = array(
            'value' => $value['paypal_api_signature']
        );
        $this->db->where('id', 6);
        $this->db->update('payment_settings', $data);

        //paypal_test_mode
        $data = array(
            'value' => $value['paypal_test_mode']
        );
        $this->db->where('id', 7);
        $this->db->update('payment_settings', $data);

        //payment gateway
        $data = array(
            'value' => $value['payment_gateway']
        );
        $this->db->where('id', 8);
        $this->db->update('payment_settings', $data);
    }

}