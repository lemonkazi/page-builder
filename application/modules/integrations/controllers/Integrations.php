<?php
require_once APPPATH . 'libraries/Citrixoauth/Auth/Oauth.php';
require_once APPPATH . 'libraries/Citrixoauth/Citrix.php';
use App\Citrixoauth\Auth\Oauth;
use App\Citrixoauth\Citrix;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Integrations extends MY_Controller {

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

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

		$model_list = [
			'settings/Whitelabel_model' => 'MWhitelabel',
            'integrations/integrations_model' => 'Integrations',
            'user/Users_model' => 'MUsers',
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
		$this->hooks->call_hook('integrations_construct');
	}

    /**
     * Print_r convenience function, which prints out <PRE> tags around
     * the output of given array. Similar to debug().
     */
    public function dd($var)
    {
        echo '<pre>';
        print_r($var);
        echo '<pre>';
        die;
    }

	/**
	 * Load the Integrations page
	 *
	 * @return 	void
	 */

	public function index()
	{
		/** Hook point */
		$this->hooks->call_hook('integrations_index_pre');

		$this->data['page'] = "integrations";
        $user_configs =  $this->Integrations->get_configs_by_user_id();
        foreach($user_configs as $config){
            $this->data[$config->name.'_configs'] = json_decode($config->value);
            $this->data[$config->name] = $config;
        }

//        $this->dd($this->data);
		/** Hook point */
		$this->hooks->call_hook('integrations_index_post');

		$this->load->view('integrations/integrations', $this->data);

	}

    /**
     * Create Integration configs
     */
    public function saveConfigs()
    {
        $this->hooks->call_hook('user_mail_api_config');
        $configs = $this->input->post();
        $integration_id = $configs['integration_id'];
        parse_str($configs['data'], $configs);
        $token = '';
        if($configs){
            if($configs['api_name'] == 'mailchimp'){
                $credentials = [
                    'username' => $configs['email'],
                    'api_key' => $configs['api_key'],
                ];
                $this->load->library('MailChimp', $credentials);
                $output = $this->mailchimp->get('lists/');
                if($output['lists'] and !isset($configs['list_id'])){
                    $temp = array();
                    $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                    $temp['content'] = $this->lang->line('integration_list_id_select');
                    $this->return = array();
                    /** Include user list in return as well */
                    $this->return['responseCode'] = 1;
                    $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                    $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                    $this->return['api_name'] = $configs['api_name'];
                    $this->return['lists'] = $output['lists'];
                    $this->hooks->call_hook('mailchimp_mail_api_config');
                    die(json_encode($this->return));
                }
                if($output['lists'] and isset($configs['list_id']) and $configs['list_id']){
                    $output['lists'] = $configs['list_id'];
                    $data = [
                        'configured' => 1,
                        'name' => $configs['api_name'],
                        'value' => '{"email":"'.$configs['email'].'","list_id":"'.$output['lists'].'","api_key":"'.$configs['api_key'].'"}',
                    ];
                    if(isset($output) && isset($output['lists'])){
                        if(isset($integration_id) && $integration_id > 0){
                            $data['integration_id'] = $integration_id;
                            if($this->Integrations->update_configs($data)){
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                                $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 1;
                                $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                                $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                                $this->return['api_name'] = $configs['api_name'];
                                $this->hooks->call_hook('update_mail_api_config');
                                die(json_encode($this->return));
                            }else{
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_error');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                                $this->hooks->call_hook('update_mail_api_config');
                                die(json_encode($this->return));
                            }
                        }else{
                            if($this->Integrations->set_configs($data)){
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_success');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 1;
                                $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                                $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                                $this->return['api_name'] = $configs['api_name'];
                                $this->hooks->call_hook('create_mail_api_config');
                                die(json_encode($this->return));
                            }else{
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_error');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                                $this->hooks->call_hook('create_mail_api_config');
                                die(json_encode($this->return));
                            }
                        }
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('mail_api_aut_integrations_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data'=>$temp), TRUE);
                        $this->hooks->call_hook('create_mail_api_config');
                        die(json_encode($this->return));
                    }
                }

            }
            if($configs['api_name'] == 'aweber'){
                $credentials = [
                    'consumerKey' => $configs['consumerKey'],
                    'consumerSecret' => $configs['consumerSecret'],
                ];

                if(isset($configs['verifier']) && !empty($configs['verifier'])) {
                    $aweber_credentials = $this->Integrations->get_configs_by_name('aweber');
                    $this->load->library('Aweber', $credentials);
                    $output = $this->aweber->getAllAccounts($aweber_credentials, $configs['verifier']);

                    $data = [
                        'integration_id' => $aweber_credentials['id'],
                        'name' => 'aweber',
                        'configured' => 1,
                        'value' => '{"email":"'.$output['email'].'","consumerKey":"'.$output['consumerKey'].'","consumerSecret":"'.$output['consumerSecret'].'","accessToken":"'.$output['accessToken'].'","tokenSecret":"'.$output['tokenSecret'].'"}',
                    ];

                    $data['integration_id'] = $aweber_credentials['id'];
                    if($this->Integrations->update_configs($data)){
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('mail_api_integrations_success');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['api_name'] = 'aweber';
                        $this->hooks->call_hook('update_mail_api_config');
                        die(json_encode($this->return));
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('mail_api_integrations_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('create_mail_api_config');
                        die(json_encode($this->return));
                    }
                } else {
                    $this->load->library('Aweber', $credentials);
                    $output =  $this->aweber->authorize();

                    if($output['url'] && $output['url'] != null){
                        $data = [
                            'name' => 'aweber',
                            'value' => '{"email":"'.$configs['email'].'","consumerKey":"'.$output['consumerKey'].'","consumerSecret":"'.$output['consumerSecret'].'","accessToken":"'.$output['accessToken'].'","tokenSecret":"'.$output['tokenSecret'].'"}',
                        ];
                        if(isset($integration_id) && $integration_id > 0){
                            $data['integration_id'] = $integration_id;
                            $data['configured'] = 1;
                            if($this->Integrations->update_configs($data)){
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                                $temp['content'] = $this->lang->line('integrations_verifier');
                                $this->return = array();
                                $this->return['responseCode'] = 1;
                                $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                                $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                                $this->return['url'] = $output['url'];
                                $this->return['api_name'] = 'aweber';
                                $this->hooks->call_hook('update_mail_api_config');
                                die(json_encode($this->return));
                            }else{
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_error');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                                $this->hooks->call_hook('create_mail_api_config');
                                die(json_encode($this->return));
                            }
                        }else{
                            $data['configured'] = 0;
                            if($this->Integrations->set_configs($data)){
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                                $temp['content'] = $this->lang->line('integrations_verifier');
                                $this->return = array();
                                $this->return['responseCode'] = 1;
                                $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                                $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                                $this->return['url'] = $output['url'];
                                $this->return['api_name'] = 'aweber';
                                $this->hooks->call_hook('update_mail_api_config');
                                die(json_encode($this->return));
                            }else{
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_error');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                                $this->hooks->call_hook('create_mail_api_config');
                                die(json_encode($this->return));
                            }
                        }
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('update_mail_api_config');
                        die(json_encode($this->return));
                    }
                }
            }
            if($configs['api_name'] == 'campaign_monitor'){

                $client_id = $configs['client_id'];
                $client_oauth_id = $configs['client_oauth_id'];
                $api_key = $configs['api_key'];
                $client_secret = $configs['client_secret'];
                $redirect_uri = base_url('integrations/verifierCode');
                $scope = 'ManageLists,ImportSubscribers';

                if(isset($configs['verifier']) && !empty($configs['verifier'])) {

                    $result = CS_REST_General::exchange_token(
                        $client_oauth_id,
                        $client_secret,
                        $redirect_uri,
                        $configs['verifier']
                    );

                    if ($result->was_successful()) {
                        $access_token = $result->response->access_token;
                        $expires_in = $result->response->expires_in;
                        $refresh_token = $result->response->refresh_token;

                        $data = [
                            'name' => 'campaign_monitor',
                            'value' => '{"email":"'.$configs['email'].'","accessToken":"'.$access_token.'","refreshToken":"'.$refresh_token.'","clientId":"'.$client_id.'","clientOauthId":"'.$client_oauth_id.'","clientSecret":"'.$client_secret.'","expiresIn":"'.$expires_in.'","api_key":"'.$api_key.'"}',
                            'configured' => 1
                        ];

                        if(isset($integration_id) && $integration_id > 0){
                            $data['integration_id'] = $integration_id;
                            if($this->Integrations->update_configs($data)){
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                                $temp['content'] = $this->lang->line('integrations_verifier');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 1;
                                $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                                $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                                $this->return['api_name'] = 'campaign_monitor';
                                $this->hooks->call_hook('update_mail_api_config');
                                die(json_encode($this->return));
                            }else{
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_error');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                                $this->hooks->call_hook('update_mail_api_config');
                                die(json_encode($this->return));
                            }
                        }
                        else{
                            if($this->Integrations->set_configs($data)){
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_success');
                                $this->return = array();
                                $this->return['responseCode'] = 1;
                                $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                                $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                                $this->return['api_name'] = 'campaign_monitor';
                                $this->hooks->call_hook('create_mail_api_config');
                                die(json_encode($this->return));
                            }else{
                                $temp = array();
                                $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                                $temp['content'] = $this->lang->line('mail_api_integrations_error');
                                $this->return = array();
                                /** Include user list in return as well */
                                $this->return['responseCode'] = 0;
                                $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                                $this->hooks->call_hook('create_mail_api_config');
                                die(json_encode($this->return));
                            }
                        }
                    }
                    else {
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('mail_api_integrations_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('create_mail_api_config');
                        die(json_encode($this->return));
                    }

                } else {
                    $authorize_url = CS_REST_General::authorize_url($client_oauth_id, $redirect_uri, $scope);

                    if($authorize_url){
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier');
                        $this->return = array();
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['url'] = $authorize_url;
                        $this->return['api_name'] = 'campaign_monitor';
                        $this->hooks->call_hook('update_mail_api_config');
                        die(json_encode($this->return));
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('update_mail_api_config');
                        die(json_encode($this->return));
                    }

                }

            }
            if ($configs['api_name'] == 'infusionsoft') {
                $infusionsoft = new \Infusionsoft\Infusionsoft(array(
                                'clientId'     => $configs['clientId'],
                                'clientSecret' => $configs['clientSecret'],
                                'redirectUri'  => base_url('/integrations/verifierCode')
                            ));

                if (isset($configs['verifier']) and !$infusionsoft->getToken()) {
                    $token = $infusionsoft->requestAccessToken($configs['verifier']);
                }

                if ($token) {
                    $data = [
                        'configured' => 1,
                        'name' => 'infusionsoft',
                        'value' => '{"email":"'.$configs['email'].'","clientId":"'.$configs['clientId'].'","clientSecret":"'.$configs['clientSecret'].'","verifier":"'.$configs['verifier'].'"}',
                        'token' => serialize($token),
                    ];

                   if (isset($integration_id) && $integration_id > 0) {
                       $data['integration_id'] = $integration_id;
                       if ($this->Integrations->update_configs($data)) {
                           $temp = array();
                           $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                           $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                           $this->return = array();

                           /** Include user list in return as well */
                           $this->return['responseCode'] = 1;
                           $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                           $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                           $this->return['api_name'] = 'infusionsoft';
                           $this->hooks->call_hook('update_infusionsoft_api_config');
                           die(json_encode($this->return));
                       }else{
                           $temp = array();
                           $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                           $temp['content'] = $this->lang->line('mail_api_integrations_error');
                           $this->return = array();
                           /** Include user list in return as well */
                           $this->return['responseCode'] = 0;
                           $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                           $this->hooks->call_hook('update_infusionsoft_api_config');
                           die(json_encode($this->return));
                       }
                   }else{
                       if($this->Integrations->set_configs($data)){
                           $temp = array();
                           $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                           $temp['content'] = $this->lang->line('mail_api_integrations_success');
                           $this->return = array();
                           $this->return['responseCode'] = 1;
                           $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                           $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                           $this->return['api_name'] = 'infusionsoft';
                           $this->hooks->call_hook('set_infusionsoft_configs');
                           die(json_encode($this->return));
                       }else{
                           $temp = array();
                           $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                           $temp['content'] = $this->lang->line('mail_api_integrations_error');
                           $this->return = array();
                           /** Include user list in return as well */
                           $this->return['responseCode'] = 0;
                           $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                           $this->hooks->call_hook('set_infusionsoft_configs');
                           die(json_encode($this->return));
                       }
                   }
                } else {
                    $url = $infusionsoft->getAuthorizationUrl();
                    if($url){
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier');
                        $this->return = array();
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['url'] = $url;
                        $this->return['api_name'] = 'infusionsoft';
                        $this->hooks->call_hook('infusionsoft_authorization_url');
                        die(json_encode($this->return));
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('infusionsoft_authorization_url');
                        die(json_encode($this->return));
                    }
                }
            }
            if($configs['api_name'] == 'getresponse'){
                $this->load->library('GetResponse', $configs['api_key']);
                $account = $this->getresponse->getAccountInfo();
                if($account){
                    $list_id = '';
                    if(isset($configs['list_id'])){
                        $list_id = $configs['list_id'];
                    }else{
                        $campaigns = $this->getresponse->getCampaigns();
                        if($campaigns){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('integration_list_id_select');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = 'getresponse';
                            $this->return['lists'] = $campaigns;
                            $this->hooks->call_hook('getresponse_mail_api_config');
                            die(json_encode($this->return));
                        }
                    }
                    $data = [
                        'configured' => 1,
                        'name' => 'getresponse',
                        'value' => '{"email":"'.$configs['email'].'","list_id":"'.$list_id.'","api_key":"'.$configs['api_key'].'"}',
                    ];
                    if(isset($integration_id) && $integration_id > 0){
                        $data['integration_id'] = $integration_id;
                        if($this->Integrations->update_configs($data)){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = 'getresponse';
                            $this->hooks->call_hook('update_getresponse_api_config');
                            die(json_encode($this->return));
                        }else{
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('update_getresponse_api_config');
                            die(json_encode($this->return));
                        }
                    }else{
                        if($this->Integrations->set_configs($data)){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_success');
                            $this->return = array();
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = 'getresponse';
                            $this->hooks->call_hook('set_getresponse_configs');
                            die(json_encode($this->return));
                        }else{
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('set_getresponse_configs');
                            die(json_encode($this->return));
                        }
                    }
                }
            }

            if ($configs['api_name'] == 'gotowebinar') {
                $citrix_api_key = $configs['consumer_key'];
                $citrix_api_secret = $configs['consumer_secret'];
                $redirect_uri = base_url('integrations/verifierCode');

                if (isset($configs['verifier'])) {
                    $responseKey = $configs['verifier'];
                    $auth = new Oauth($citrix_api_key, $citrix_api_secret, $redirect_uri);
                    $auth->setResponseKey($responseKey);

                    try {
                        $auth->applyCredentials();
                    } catch (Exception $e) {
                        echo 'Exception';
                        var_dump($e);
                        die();
                    }

                    $accessToken = $auth->getAccessToken();
                    $organizerKey = $auth->getOrganizerKey();
                    $refreshToken = $auth->getRefreshToken();

                    $data = [
                        'configured' => 1,
                        'name' => 'gotowebinar',
                        'value' => '{"email":"' . $configs['email'] . '","consumer_key":"' . $citrix_api_key . '","consumer_secret":"' . $citrix_api_secret.'","accessToken":"' . $accessToken.'","refreshToken":"' . $refreshToken . '","organizerKey":"' . $organizerKey . '"}',
                        'token' => serialize($auth),
                    ];

                    if (isset($integration_id) && $integration_id > 0) {
                        $data['integration_id'] = $integration_id;
                        if ($this->Integrations->update_configs($data)) {
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                            $this->return = array();

                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = $configs['api_name'];
                            $this->hooks->call_hook('update_mail_api_config');

                            die(json_encode($this->return));
                        } else {
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();

                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('update_mail_api_config');

                            die(json_encode($this->return));
                        }
                    } else {
                        if ($this->Integrations->set_configs($data)) {
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_success');
                            $this->return = array();

                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = $configs['api_name'];
                            $this->hooks->call_hook('create_mail_api_config');

                            die(json_encode($this->return));
                        } else {
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();

                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('create_mail_api_config');
                            die(json_encode($this->return));
                        }
                    }
                } else {
                       $auth = new Oauth($citrix_api_key);// Get from Citrix the Url where to insert your admin credentials
                    try {
                        $redirectUrl = $auth->getAuthorizationLogonUrl($redirect_uri);
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier');

                        $this->return = array();
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['url'] = $redirectUrl;
                        $this->return['api_name'] = 'gotowebinar';
                        $this->hooks->call_hook('update_mail_api_config');

                        die(json_encode($this->return));
                    } catch (Exception $e) {
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('integrations_verifier_error');

                        $this->return = array();
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['api_name'] = 'gotowebinar';
                        $this->hooks->call_hook('update_mail_api_config');

                        die(json_encode($this->return));
                    }
                }
            }

            if ($configs['api_name'] == 'activecampaign') {
                $ac = new ActiveCampaign($configs['api_url'], $configs['api_key']);
                if (!(int)$ac->credentials_test()) {
                    $temp = array();
                    $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                    $temp['content'] = $this->lang->line('integrations_verifier_error');
                    $this->return = array();
                    /** Include user list in return as well */
                    $this->return['responseCode'] = 0;
                    $this->return['api_name'] = $configs['api_name'];
                    $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                    $this->hooks->call_hook('update_mail_api_config');
                    die(json_encode($this->return));
                }
                $data = [
                    'configured' => 1,
                    'name' => 'activecampaign',
                    'value' => '{"email":"'.$configs['email'].'","api_url":"'.$configs['api_url'].'","api_key":"'.$configs['api_key'].'"}',
                ];
                if(isset($integration_id) && $integration_id > 0){
                    $data['integration_id'] = $integration_id;
                    if($this->Integrations->update_configs($data)){
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['api_name'] = $configs['api_name'];
                        $this->hooks->call_hook('update_mail_api_config');
                        die(json_encode($this->return));
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('mail_api_integrations_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('update_mail_api_config');
                        die(json_encode($this->return));
                    }
                }else{
                    if($this->Integrations->set_configs($data)){
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                        $temp['content'] = $this->lang->line('mail_api_integrations_success');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 1;
                        $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                        $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                        $this->return['api_name'] = $configs['api_name'];
                        $this->hooks->call_hook('create_mail_api_config');
                        die(json_encode($this->return));
                    }else{
                        $temp = array();
                        $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                        $temp['content'] = $this->lang->line('mail_api_integrations_error');
                        $this->return = array();
                        /** Include user list in return as well */
                        $this->return['responseCode'] = 0;
                        $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                        $this->hooks->call_hook('create_mail_api_config');
                        die(json_encode($this->return));
                    }
                }

            }
            if ($configs['api_name'] == 'webinarjam') {
                $credentials = [
                    'email' => $configs['email'],
                    'api_key' => $configs['api_key']
                ];

                $this->load->library('WebinarJam', $credentials);
                $jem_webinars = $this->webinarjam->getWebinarsList();

                if(isset($jem_webinars->webinars)) {
                    $data = [
                        'configured' => 1,
                        'name' => 'webinarjam',
                        'value' => '{"email":"'.$configs['email'].'","api_key":"'.$configs['api_key'].'"}'
                    ];

                    if(isset($integration_id) && $integration_id > 0){
                        $data['integration_id'] = $integration_id;
                        if($this->Integrations->update_configs($data)){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = $configs['api_name'];
                            $this->hooks->call_hook('update_mail_api_config');
                            die(json_encode($this->return));
                        }else{
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('update_mail_api_config');
                            die(json_encode($this->return));
                        }
                    }else{
                        if($this->Integrations->set_configs($data)){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_success');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = $configs['api_name'];
                            $this->hooks->call_hook('create_mail_api_config');
                            die(json_encode($this->return));
                        }else{
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('create_mail_api_config');
                            die(json_encode($this->return));
                        }
                    }
                }

            }
            if ($configs['api_name'] == 'everwebinar') {
                $credentials = [
                    'email' => $configs['email'],
                    'api_key' => $configs['api_key']
                ];

                $this->load->library('EverWebinar', $credentials);
                $ever_webinars = $this->everwebinar->getWebinarsList();

                if(isset($ever_webinars->webinars)) {
                    $data = [
                        'configured' => 1,
                        'name' => 'everwebinar',
                        'value' => '{"email":"'.$configs['email'].'","api_key":"'.$configs['api_key'].'"}'
                    ];

                    if(isset($integration_id) && $integration_id > 0){
                        $data['integration_id'] = $integration_id;
                        if($this->Integrations->update_configs($data)){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_update_integrations_success');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = $configs['api_name'];
                            $this->hooks->call_hook('update_mail_api_config');
                            die(json_encode($this->return));
                        }else{
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('update_mail_api_config');
                            die(json_encode($this->return));
                        }
                    }else{
                        if($this->Integrations->set_configs($data)){
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_success_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_success');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 1;
                            $this->return['responseHTML'] = $this->load->view('shared/success', array('data' => $temp), TRUE);
                            $this->return['label_configured'] = $this->lang->line('integrations_label_configured');
                            $this->return['api_name'] = $configs['api_name'];
                            $this->hooks->call_hook('create_mail_api_config');
                            die(json_encode($this->return));
                        }else{
                            $temp = array();
                            $temp['header'] = $this->lang->line('mail_api_integrations_error_heading');
                            $temp['content'] = $this->lang->line('mail_api_integrations_error');
                            $this->return = array();
                            /** Include user list in return as well */
                            $this->return['responseCode'] = 0;
                            $this->return['responseHTML'] = $this->load->view('shared/error', array('data' => $temp), TRUE);
                            $this->hooks->call_hook('create_mail_api_config');
                            die(json_encode($this->return));
                        }
                    }
                }

            }
        }
    }

    /**
     * Get Api Lists
     */
    public function apilist()
    {
        $api = $this->input->post('api');
        $api_details = $this->MUsers->get_api($api);
        $api_credentials = json_decode($api_details['value']);

        if($api_credentials){

            switch($api) {
                case 'mailchimp':

                    $credentials = [
                        'username' => $api_credentials->email,
                        'api_key' => $api_credentials->api_key,
                    ];
                    $this->load->library('MailChimp', $credentials);
                    $output = $this->mailchimp->get('lists/');

                    $lists = $output['lists'] ?: [];

                    $lists = array_map(function($list) {

                        $arr = [];
                        $arr['list_id'] = $list['id'];
                        $arr['name'] = $list['name'];

                        return $arr;

                    }, $lists);

                    echo json_encode($lists);
                    break;
                case 'aweber':

                    $credentials = [
                        'consumerKey' => $api_credentials->consumerKey,
                        'consumerSecret' => $api_credentials->consumerSecret,
                        'token' => $api_credentials->accessToken,
                        'token_secret' => $api_credentials->tokenSecret,
                    ];
                    $this->load->library('Aweber', $credentials);
                    $output = $this->aweber->getLists($credentials);

                    $lists = $output ?: [];

                    $lists = array_map(function($list) {

                        $arr = [];
                        $arr['list_id'] = $list['id'];
                        $arr['name'] = $list['name'];

                        return $arr;

                    }, $lists);

                    echo json_encode($lists);
                    break;
                case 'infusionsoft':

                    $infusionsoft = new \Infusionsoft\Infusionsoft(array(
                        'clientId' => $api_credentials->clientId,
                        'clientSecret' => $api_credentials->clientSecret,
                        'redirectUri' => base_url('/integrations/verifierCode')
                    ));

                    $infusionsoft->setToken(unserialize($api_details['token']));
                    $companies = '';

                    try {
                        $companies = $infusionsoft->companies()->all();
                    } catch (\Infusionsoft\TokenExpiredException $e) {
                        $infusionsoft->refreshAccessToken();

                        $data = array(
                            'value' => $api_details['value'],
                            'token' => serialize($infusionsoft->getToken()),
                            'configured' => 1,
                            'integration_id' => $api_details['id']
                        );

                        $this->Integrations->update_configs($data);
                        $companies = $infusionsoft->companies()->all();
                    }

                    $lists = $companies ? json_decode(json_encode($companies), true) : [];

                    $lists = array_map(function($list) {

                        $arr = [];
                        $arr['list_id'] = $list['id'];
                        $arr['name'] = $list['company_name'];

                        return $arr;

                    }, $lists);

                    echo json_encode($lists);
                    break;
                case 'webinarjam':
                    $credentials = [
                        'email' => $api_credentials->email,
                        'api_key' => $api_credentials->api_key
                    ];

                    $this->load->library('WebinarJam', $credentials);
                    $output = $this->webinarjam->getWebinarsList();

                    $lists = $output->webinars ?: [];

                    $lists = array_map(function($list) {

                        $arr = [];
                        $arr['list_id'] = $list->webinar_id;
                        $arr['name'] = $list->name;

                        return $arr;

                    }, $lists);

                    echo json_encode($lists);
                    break;
                case 'everwebinar':
                    $credentials = [
                        'email' => $api_credentials->email,
                        'api_key' => $api_credentials->api_key
                    ];

                    $this->load->library('EverWebinar', $credentials);
                    $output = $this->everwebinar->getWebinarsList();

                    $lists = $output->webinars ?: [];

                    $lists = array_map(function($list) {

                        $arr = [];
                        $arr['list_id'] = $list->webinar_id;
                        $arr['name'] = $list->name;

                        return $arr;

                    }, $lists);

                    echo json_encode($lists);
                    break;
                case 'getresponse':
                    $this->load->library('GetResponse', $api_credentials->api_key);
                    $account = $this->getresponse->getAccountInfo();
                    if($account){
                        $campaigns = $this->getresponse->getCampaigns();

                        $lists = (array) $campaigns ?: [];

                        $lists = array_map(function($list, $key) {

                            $arr = [];
                            $arr['list_id'] = $key;
                            $arr['name'] = $list->name;

                            return $arr;

                        }, $lists, array_keys($lists));

                        echo json_encode($lists);
                    }
                    break;
                case 'gotowebinar':
                    $redirect_uri = base_url('integrations/verifierCode');
                    $citrix_api_key = $api_credentials->consumer_key;
                    $citrix_api_secret = $api_credentials->consumer_secret;
                    $citrix_access_token = $api_credentials->accessToken;
                    $citrix_organizer_key = $api_credentials->organizerKey;
                    $citrix_refersh_token = $api_credentials->refreshToken;

                    $auth = new OAuth($citrix_api_key, $citrix_api_secret, $redirect_uri);

                    $auth->setAccessToken($citrix_access_token);
                    $auth->setOrganizerKey($citrix_organizer_key);
                    $auth->setRefreshToken($citrix_refersh_token);

                    $citrix = new Citrix($auth);
                    $webinars = $citrix->getUpcoming();

                    $webinars = json_decode($webinars);

                    if (isset($webinars->int_err_code)) {
                        try {
                            $auth->refreshTokenInfo();
                            $api_credentials->accessToken = $auth->getAccessToken();
                            $api_credentials->organizerKey = $auth->getOrganizerKey();
                            $api_credentials->refreshToken = $auth->getRefreshToken();

                            $data = array(
                                'value' => json_encode($api_credentials),
                                'token' => serialize($auth),
                                'configured' => 1,
                                'integration_id' => $api_details['id']
                            );

                            $this->Integrations->update_configs($data);

                            $webinars = $citrix->getUpcoming();

                            $webinars = json_decode($webinars);
                        } catch(\Exception $e) {

                        }
                    }

                    $lists = $webinars ? : [];
                    $lists = array_filter($lists, 'is_object');
                    $lists = array_map(function($list) {
                        $arr = [];
                        $arr['list_id'] = $list->webinarID;
                        $arr['name'] = $list->subject;

                        return $arr;
                    }, $lists);

                    echo json_encode($lists);
                    break;
                case 'activecampaign':
                    $ac = new ActiveCampaign($api_credentials->api_url, $api_credentials->api_key);

                    $contacts_view = $ac->api("list/list?ids=all", []);
                    $lists = $contacts_view->success ? (array) $contacts_view : [];
                    $lists = array_filter($lists, 'is_object');

                    $lists = array_map(function($list, $key) {

                        $arr = [];
                        $arr['list_id'] = $list->id;
                        $arr['name'] = $list->name;

                        return $arr;

                    }, $lists, array_keys($lists));

                    echo json_encode($lists);
                    break;
                case 'campaign_monitor':
                    $auth = array(
                        'access_token' => $api_credentials->accessToken,
                        'refresh_token' => $api_credentials->refreshToken
                    );
                    $wrap = new CS_REST_General($auth);
                    $result = $wrap->get_clients();

                    if (!$result->was_successful()) {

                        if ($result->response->Code == 121) {
                            list($new_access_token, $new_expires_in, $new_refresh_token) =
                                $wrap->refresh_token();

                            $api_credentials->accessToken = $new_access_token;
                            $api_credentials->refreshToken = $new_refresh_token;
                            $api_credentials->expiresIn = $new_expires_in;

                            $data = array(
                                'value' => json_encode($api_credentials),
                                'configured' => 1,
                                'integration_id' => $api_details['id']
                            );

                            $this->Integrations->update_configs($data);

                        }

                        $result = $wrap->get_clients();
                    }

                    $client = new CS_REST_Clients(
                        $api_credentials->clientId,
                        $auth);

                    $lists = $client->get_lists();

                    $lists = $lists->was_successful() ? (array) $lists->response : [];

                    $lists = array_map(function($list) {

                        $arr = [];
                        $arr['list_id'] = $list->ListID;
                        $arr['name'] = $list->Name;

                        return $arr;

                    }, $lists);

                    echo json_encode($lists);
                    break;
            }
        }
    }

    public function verifierCode(){
        /** Hook point */
        $this->hooks->call_hook('integrations_verifier_code');

        $this->data['page'] = "verifier_code";

        $this->load->view('integrations/verifier_code', $this->data);
    }
    /**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('integrations_destruct');
	}
}
