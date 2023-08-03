<?php

require_once APPPATH . 'libraries/Citrixoauth/Auth/Oauth.php';
require_once APPPATH . 'libraries/Citrixoauth/Citrix.php';
use App\Citrixoauth\Auth\Oauth;
use App\Citrixoauth\Citrix;

defined('BASEPATH') OR exit('No direct script access allowed');

class Sent extends MY_Controller {

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
			'sent/Sentapi_model' => 'MSentAPI',
			'user/Users_model' => 'MUsers',
			'integrations/integrations_model' => 'Integrations',
			'sites/Sites_model' => 'MSites',
		];
		$this->load->model($model_list);

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];

		/** Hook point */
		$this->hooks->call_hook('sent_construct');
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
	 * Blank Index
	 */
	public function index()
	{

	}

	/**
	 * Main function to send the email
	 *
	 * @param  	string 	$to
	 * @return  void
	 */
	public function api($to = '', $domain = false)
	{
		/** Hook point */
		$this->hooks->call_hook('sent_api_pre');

		$integration_status = $this->input->post('integration_name') && $this->input->post('api_list_id') ? true : false;
		$integration_success = false;

		if ($this->input->post())
		{
			/** No email address or ID? */
			if ($to == '' && ! $integration_status)
			{
				$temp = array();
				$temp['header'] = $this->lang->line('no_email_error_header_error');
				$temp['content'] = $this->lang->line('no_email_error_content');

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='".$_SERVER['HTTP_REFERER']."' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** Check if all field is empty or not */
			if ($this->MSentAPI->all_empty($_REQUEST))
			{
				$temp = array();
				$temp['header'] = $this->lang->line('empty_fields_error_header');
				$temp['content'] = $this->lang->line('empty_fields_error_content');

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** SPAM honey pot check */
			if (isset($_REQUEST['_honey']) && $_REQUEST['_honey'] != '')
			{
				/** This is not right */
				$temp = array();
				$temp['header'] = $this->lang->line('honey_error_header');
				$temp['content'] = $this->lang->line('honey_error_content');

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** Apply xss_clean filter */
			foreach ($_REQUEST as $key=>$value)
			{
				if (substr($key, 0, 1) != "_" && $key != "ci_session" && strpos($key,'wp-') === false)
				{
					// echo $value." => ".$this->security->xss_clean($value)."<br>";
					$_REQUEST[$key] = $this->security->xss_clean($value);
					/** somehow, this is the only way xss filtering works */
					if (isset($_REQUEST['_valid'][$key]))
					{
						$this->form_validation->set_rules($key, $key, "xss_clean|".$_REQUEST['_valid'][$key]);
					}
					else
					{
						$this->form_validation->set_rules($key, $key, "xss_clean");
					}
				}
			}

			if ($this->form_validation->run() === FALSE)
			{
				/** Validation fail */
				$temp = array();
				$temp['header'] = $this->lang->line('validation_error_header');
				$temp['content'] = $this->lang->line('validation_error_content') . validation_errors();

				if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/*
			 * Load upload library
			 */
			$config['upload_path']          = './tmp/attachments/';
			$config['allowed_types']        = 'png|jpg|pdf';

			$this->load->library('upload', $config);

			/** Do we have a file upload to take care off? */
			if (isset($_FILES['file']) && $_FILES['file']['name'] != '')
			{
				if ( ! $this->upload->do_upload("file"))
				{
					$temp = array();
					$temp['header'] = $this->lang->line('file_error_header');
					$temp['content'] = $this->lang->line('file_error_content') . $this->upload->display_errors();

					if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
					{
						$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
					}

					$temp['alert_type'] = "error";

					die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
				}
				else
				{
					$fileData = $this->upload->data();
					/** Setup the attachment */
					$this->email->attach( $fileData['full_path'] );
					/** Delete or save attachement */
					$removeAttachment = $fileData['full_path'];
				}
			}

			/** Have any cc? */
			if (isset($_REQUEST['cc']))
			{
				// print_r( $_REQUEST['cc'] );
				$this->email->cc($_REQUEST['cc']);
			}

			/** Have any bcc? */
			if (isset($_REQUEST['bcc']))
			{
				// print_r( $_REQUEST['bcc'] );
				$this->email->bcc($_REQUEST['bcc']);
			}

			/** Set email from address and name */
			if ($this->input->post('hiddenInputSiteID') != '')
			{ // Use the email and name belonging to the site owner
				$user = $this->MUsers->get_by_site_id($this->input->post('hiddenInputSiteID'));
				$this->email->from($this->config->item('sent_email_from_address'), $this->config->item('email_from_name'));
			}
			else
			{ //  Use the default email and name
				$this->email->from($this->config->item('sent_email_from_address'), $this->config->item('sent_email_from_name'));
			}

			/** Set email send to address */
			$this->email->to($to);

			/** Set email subject */
			if (isset($_REQUEST['_subject']) && $_REQUEST['_subject'] != '')
			{
				$this->email->subject($_REQUEST['_subject']);
			}
			else
			{
				$this->email->subject($this->config->item('sent_email_default_subject'));
			}

			/** Set reply to email */
			if (isset($_REQUEST['_replyto']) && $_REQUEST['_replyto'] != '')
			{
				if (substr($_REQUEST['_replyto'], 0, 1) == "%")
				{
					$replyTo = ltrim($_REQUEST['_replyto'],'%');
					if (isset($_REQUEST[$replyTo]) && $_REQUEST[$replyTo] != '')
					{
						$this->email->reply_to($_REQUEST[$replyTo]);
					}
					else
					{
						$this->email->reply_to($_REQUEST['_replyto']);
					}
				}
				else
				{
					$this->email->reply_to($_REQUEST['_replyto']);
				}
			}

			/** Set email body by mail type */
			if ($this->email->mailtype == 'html')
			{
				$this->email->message($this->load->view('sent/email_default', $_REQUEST, TRUE));
			}
			else
			{
				$this->email->message($this->load->view('sent/email_text', $_REQUEST, TRUE));
			}

			/** Make API call if integration hidden inputs exist */
			if ($integration_status)
			{
				$integration_name = $this->input->post('integration_name');
				$api_list_id = $this->input->post('api_list_id');
				$siteID = $this->input->post('hiddenInputSiteID');
                $userId = $this->MSites->getUserIdBySiteId($siteID);
                $integration = $this->MUsers->getApiByIntegrationNameAndUserId($integration_name, $userId);
				if(!isset($integration))
					$integration = '';

				if ($integration && $this->input->post('email'))
				{
					$temp = array();
					$api_configs = json_decode($integration['value']);

					switch ($integration_name)
					{
						case 'mailchimp':
						$api_url = 'lists/'.$api_list_id.'/members/';

						$credentials = [
							'username' => $api_configs->email,
							'api_key' => $api_configs->api_key,
						];

						$this->load->library('MailChimp', $credentials);

						$api_data = array(
							"email_address" => $this->input->post('email'),
							"status" => "subscribed",
							"merge_fields" => array(
								'FNAME' => $this->input->post('fname') ?: ($this->input->post('name') ?: ''),
								'LNAME' => $this->input->post('lname') ?: ''
							)
						);

						$output = $this->mailchimp->post($api_url, $api_data);
						if (isset($output['status']) && $output['status'] === 'subscribed')
						{
							$integration_success = true;
						}
						elseif (isset($output['status']) && $output['status'] === 400)
						{
							if (isset($output['errors']))
							{
								$temp['header'] = $output['title'];
								$temp['content'] =  $output['errors'][0]['message'];
							}
							elseif(isset($output['detail']))
							{
								$temp['header'] = $output['title'];
								$temp['content'] =   $output['detail'];
							}
							$integration_success = false;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'aweber':
						$credentials = [
							'consumerKey' => $api_configs->consumerKey,
							'consumerSecret' => $api_configs->consumerSecret,
							'token' => $api_configs->accessToken,
							'token_secret' => $api_configs->tokenSecret,
						];

						$this->load->library('Aweber', $credentials);
						$data = array(
							'email' => $this->input->post('email'),
							'name' => $this->input->post('name') ? $this->input->post('name') : ($this->input->post('fname') && $this->input->post('lname') ? $this->input->post('fname') . ' ' . $this->input->post('lname') : ''),
						);
						$output = $this->aweber->addSubscriber($data, $api_list_id);
						if($output){
							$integration_success = $output->status == 'unconfirmed' || $output->status == 'subscribed' ? true : false;
						}else{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'infusionsoft':
						$infusionsoft = new \Infusionsoft\Infusionsoft(array(
							'clientId' => $api_configs->clientId,
							'clientSecret' => $api_configs->clientSecret,
							'redirectUri' => ''
						));

						$infusionsoft->setToken(unserialize($integration['token']));

						$email = new \stdClass;
						$email->field = 'EMAIL1';
						$email->email = $this->input->post('email') ?: '';
						$company = new \stdClass;
						$company->id = $api_list_id;
						$name = $this->input->post('name') ?:
						($this->input->post('fname') || $this->input->post('lname') ?
							$this->input->post('fname') . ' ' . $this->input->post('lname') : null);
						$second_name = $this->input->post('lname') ?: '';
						$data = ['given_name' => $name, 'family_name' => $second_name, 'email_addresses' => [$email], 'company' => $company];
						$contact = '';

						try
						{
							$contact = $infusionsoft->contacts()->create($data);
						}
						catch (\Infusionsoft\TokenExpiredException $e)
						{
							$infusionsoft->refreshAccessToken();

							$data = array(
								'value' => $integration['value'],
								'token' => serialize($infusionsoft->getToken()),
								'configured' => 1,
								'integration_id' => $integration['id']
							);

							$this->Integrations->update_configs($data);
							$contact = $infusionsoft->contacts()->create($data);
						}
						if ($contact)
						{
							$integration_success = true;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'webinarjam':
						$credentials = [
							'email' => $api_configs->email,
							'api_key' => $api_configs->api_key
						];

						$this->load->library('WebinarJam', $credentials);

						$credentials = array(
							'webinar_id' => $api_list_id,
							'first_name' => $this->input->post('name') ?:
							($this->input->post('fname') || $this->input->post('lname') ?
								$this->input->post('fname') . ' ' . $this->input->post('lname') : null),
							'last_name' => $this->input->post('lname') ?: null,
							'email' => $this->input->post('email'),
							'schedule' => $this->input->post('schedule') ?: 0,
							'ip_address' => $this->input->post('ip_address') ?: null,
							'phone_country_code' => $this->input->post('phone_country_code') ?: null,
							'phone' => $this->input->post('phone') ?: null
						);
						$output = $this->webinarjam->registerToWebinar($credentials);
						if ($output)
						{
							$integration_success = $output->status == 'success' ? true : false;
						}
						elseif ( ! isset($credentials['first_name']))
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('first_name_not_exist');
							$integration_success = false;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'everwebinar':
						$credentials = [
							'email' => $api_configs->email,
							'api_key' => $api_configs->api_key
						];

						$this->load->library('EverWebinar', $credentials);

						$credentials = array(
							'webinar_id' => $api_list_id,
							'first_name' => $this->input->post('name') ?:
							($this->input->post('fname') || $this->input->post('lname') ?
								$this->input->post('fname') . ' ' . $this->input->post('lname') : null),
							'last_name' => $this->input->post('lname') ?: null,
							'email' => $this->input->post('email'),
							'schedule' => $this->input->post('schedule') ?: 0,
							'ip_address' => $this->input->post('ip_address') ?: null,
							'phone_country_code' => $this->input->post('phone_country_code') ?: null,
							'phone' => $this->input->post('phone') ?: null,
							'timezone' => $this->input->post('timezone') ?: null,
							'real_dates' =>$this->input->post('real_dates') ?: 1
						);
						$output = $this->everwebinar->registerToWebinar($credentials);
						if ($output)
						{
							$integration_success = $output->status == 'success' ? true : false;
						}
						elseif ( ! isset($credentials['first_name']))
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('first_name_not_exist');
							$integration_success = false;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'getresponse':
						$this->load->library('GetResponse', $api_configs->api_key);
						$account = $this->getresponse->getAccountInfo();
						$name = $this->input->post('name');
						$email = $this->input->post('email');
						if ($account)
						{
							$result = $this->getresponse->addContact($api_list_id, $name, $email);
							$integration_success = $result->queued ? true : false;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'activecampaign':
						$ac = new ActiveCampaign($api_configs->api_url, $api_configs->api_key);

						$contact = array(
							"email" => $this->input->post('email'),
							'first_name' => $this->input->post('name') ?:
							($this->input->post('fname') || $this->input->post('lname') ?
								$this->input->post('fname') . ' ' . $this->input->post('lname') : null),
							'last_name' => $this->input->post('lname') ?: null,
							"p[{$api_list_id}]" => $api_list_id,
							"status[{$api_list_id}]" => 1,
						);
						$contact_sync = $ac->api("contact/sync", $contact);
						if ((int)$contact_sync->success)
						{
							$integration_success = true;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;

						case 'campaign_monitor':
						$auth = array(
							'access_token' => $api_configs->accessToken,
							'refresh_token' => $api_configs->refreshToken);

						$wrap = new CS_REST_Subscribers($api_list_id, $auth);

						$result = $wrap->add(array(
							'EmailAddress' => $this->input->post('email'),
							'Name' => $this->input->post('name') ?:
							($this->input->post('fname') || $this->input->post('lname') ?
								$this->input->post('fname') . ' ' . $this->input->post('lname') : null),
							'ConsentToTrack' => 'yes',
							'Resubscribe' => true
						));
						if ($result->was_successful())
						{
							$integration_success = true;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}

						break;

						case 'gotowebinar':
						$redirect_uri = base_url('integrations/verifierCode');
						$citrix_api_key = $api_configs->consumer_key;
						$citrix_api_secret = $api_configs->consumer_secret;
						$citrix_access_token = $api_configs->accessToken;
						$citrix_organizer_key = $api_configs->organizerKey;
						$citrix_refersh_token = $api_configs->refreshToken;

						$auth = new OAuth($citrix_api_key, $citrix_api_secret, $redirect_uri);

						$auth->setAccessToken($citrix_access_token);
						$auth->setOrganizerKey($citrix_organizer_key);
						$auth->setRefreshToken($citrix_refersh_token);

						$firstName = $this->input->post('name');
						/** @todo */
						$lastName = 'Last name';
						$email = $this->input->post('email');

						$entity = [
							"firstName"    => $firstName,
							"lastName"     => $lastName,
							"email"        => $email,
						];

						$citrix = new Citrix($auth);
						$result = $citrix->register($api_list_id, $entity);
						if ($result == true)
						{
							$integration_success = $result;
						}
						else
						{
							$temp['header'] = $this->lang->line('failure_integration_header');
							$temp['content'] = $this->lang->line('failure_integration_content');
							$integration_success = false;
						}
						break;
					}
				}
				else
				{
					$temp['header'] = $this->lang->line('failure_integration_header');
					$temp['content'] = $this->lang->line('email_not_exist');
					$integration_success = false;
				}

			}
			else
			{
				/** Send email */
				$sendEmail = $this->email->send();

				if ($sendEmail == true)
				{
					$integration_success = true;
				}
				else
				{
					$integration_success = false;
				}
			}

			// Return failure message if subscription failed
			if ( ! $integration_success)
			{
				/** Fail */
				$temp['alert_type'] = "error";

				if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** Remove attachment if there is any */
			if (isset($removeAttachment))
			{
				unlink($removeAttachment);
			}

			/** Redirect after sending email */
			if (isset($_REQUEST['_after']) && $_REQUEST['_after'] != '')
			{
				if (filter_var($_REQUEST['_after'], FILTER_VALIDATE_URL))
				{
					redirect($_REQUEST['_after'], 'location');
				}
				else
				{
					/** Fail */
					$temp = array();
					$temp['header'] = $this->lang->line('after_error_header');
					$temp['content'] = $this->lang->line('after_error_content') . $_REQUEST['_after'];

					$temp['alert_type'] = "error";

					if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
					{
						$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
					}

					die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
				}
			}
			else
			{
				/** No redirection given, display confirmation message */
				$temp = array();
				$temp['header'] = $this->lang->line('success_header');
				if (isset($_REQUEST['_confirmation']) && $_REQUEST['_confirmation'] != '')
				{
					$temp['content'] = "<small>" . $_REQUEST['_confirmation'] . "</small>";
				}
				else
				{
					/** Confirmation message (commented as we dont implemented database yet) */
					// $temp['error_message'] = "<small>" . $this->config->item('email_confirmation_message') . "</small>";
					if ($integration_status)
					{
						if ($integration_success)
						{
							$temp['content'] = $this->lang->line('success_integration_content');
						}
					}
					else
					{
						$temp['content'] = $this->lang->line('success_content');
					}

				}
				if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "success";
				$this->session->set_flashdata('message', $temp);

				/** Hook point */
				$this->hooks->call_hook('sent_api_post');

				$this->load->helper('general');

				if ( $domain )
        		{
        			redirect("//" . $domain . "/sent/msg?tmp=" . json_encode($temp));
        		}
        		else
        		{
        			redirect(site_url("sent/msg?tmp=" . json_encode($temp)));
        		}

			}
		}
	}

	/**
	 * Show message
	 *
	 * @return void
	 */
	public function msg()
	{
		if (!is_null($this->input->get('tmp'))){
            $this->session->set_flashdata('message', json_decode($this->input->get('tmp'), true));
        }

		/** Hook point */
		$this->hooks->call_hook('sent_msg_pre');

		if ($this->session->flashdata('message') != '')
		{
			echo $this->load->view('shared/alert', array('data'=>$this->session->flashdata('message')), TRUE);
		}
		else
		{
			$temp = array();
			$temp['header'] = $this->lang->line('error_header');
			$temp['content'] = $this->lang->line('error_content');

			$temp['alert_type'] = "error";

			/** Hook point */
			$this->hooks->call_hook('sent_msg_post');

			die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
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
		$this->hooks->call_hook('sent_destruct');
	}

}
