<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Auth {

    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance()->controller;
    }

    public function auth_construct ()
    {
        echo "Pre Controller";
    }

    public function auth_index_pre()
    {
        die();
    }

    public function auth_index_post ()
    {
        //$this->CI->data['title'] = $this->CI->lang->line('auth_register_title');
        $this->CI->data['title'] = 'Test Hooks';
    }

    public function auth_destruct ()
    {
        //echo "Post Controller";
    }

}


