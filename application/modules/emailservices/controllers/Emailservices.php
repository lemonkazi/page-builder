<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emailservices extends MY_Controller {

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
            'emailservices/Emailservices_model' => 'Emailservices'
        ];
        $this->load->model($model_list);

        /** Hook point */
        $this->hooks->call_hook('emailservice_construct');
    }
    /**
     *
     */
    public function sendMailList(){
        $this->hooks->call_hook('send_mail_list');
        $data = $this->input->post();
        $configs = $this->Emailservices->get_configs_by_user_id();
        if($configs){
            foreach($configs as $config){
                if($config->name == "mailchimp"){
//                    $value = json_decode($config->value);
//                    $options = [
//                        'username' => $value->email,
//                        'api_key' =>  $value->api_key,
//                    ];
//                    $list_id = $value->list_id;
//                    $this->load->library('MailChimp', $options);
//                    if($list_id){
//                        $result = $this->mailchimp->post("lists/$list_id/members", [
//                            'email_address' => 'art.hayrapetyan88@gmail.com',
//                            'merge_fields' => ['FNAME' => 'Ppppppp', 'LNAME' => 'DDDDDDDD'],
//                            'status' => 'subscribed',
//                        ]);
//                    }
                }
                if($config->name == "gotowebinar"){
                    //this gives you all upcoming webinars
//                    $citrix = new \CitrixOAuth2\Citrix(unserialize($config->token));
//                    $webinars = $citrix->getUpcoming();
//                    $entity = [
//                        "firstName"    => 'dsfdsfdf',
//                        "lastName"     => 'sdfdsf',
//                        "email"        => 'art.hayrapetyan88@gmail.com',
//                        "address"      => 'adsasdasd',
//                        "city"         => 'Yerevan',
//                        "state"        => 'sdasdas',
//                        "zipCode"      => '0312',
//                        "country"      => 'Armenia',
//                        "phone"        => '4545155454',
//                        'timeZone'     => 'America/Sao_Paulo',
//                        'organization' => 'Test Organisation',
//                    ];
//                    $citrix->register('6001853942279568385', $entity);

//                    foreach ($webinars as $webinar) {
//                        $webinarKey = $webinar->getWebinarKey();
//                        $web['registrationUrl'] = $webinar->getRegistrationUrl();
//                        $web['subject'] = $webinar->getSubject();
//                        $web['startTime'] = $webinar->getStartTime()->format('d.m.Y');
//                    }
//                    echo "<pre>";
//                    print_r($web);die();
                }
//                if($config->name == "getresponse"){
//                    $value = json_decode($config->value);
//                    $options = [
//                        'email' => $value->email,
//                        'api_key' =>  $value->api_key,
//                    ];
//                    $list_id = $value->list_id;
//                    $this->load->library('GetResponse', $value->api_key);
//                    if($list_id)
//                     $result = $this->getresponse->addContact($list_id, 'name full_name', 'adminadmin@gmail.com', 'insert', 0, ['name' => 'name']);
//                }
//                if($config->name == "aweber"){
//                    $credentials = json_decode($config->value, TRUE);
//                    $data = array(
//                        'email' => 'art.hayrapetyan88@gmail.com',
//                        'name' => 'Art Hayrapetyan',
//                        'custom_fields' => array('awesomeness' => 'somewhat'),
//                        'tags' => array('prospect')
//                    );
//                    $this->load->library('Aweber', $credentials);
//                    $result = $this->aweber->addSubscriber($credentials, $data);
//                }
//                if($config->name == "infusionsoft"){
//                    ini_set('display_errors', 1);
//                    ini_set('display_startup_errors', 1);
//                    error_reporting(E_ALL);
//                    $credentials = json_decode($config->value, TRUE);
//                    $infusionsoft = new \Infusionsoft\Infusionsoft(array(
//                        'clientId'     => $credentials['clientId'],
//                        'clientSecret' => $credentials['clientSecret'],
//                        'redirectUri'  => base_url('/integrations/verifierCode')
//                    ));
//                    $infusionsoft->setToken(unserialize($config->token));
//                    $infusionsoft->contacts('xml')->add(array('FirstName' => 'Migel', 'LastName' => 'Anxel Felix Galiardo'));
//                }
//                if($config->name == "activecampaign"){
//                    $value = json_decode($config->value);
//
//                    $ac = new ActiveCampaign($value->api_url, $value->api_key);
//                    /*
//                     * TEST API CREDENTIALS.
//                     */
//                    if (!(int)$ac->credentials_test()) {
//                        echo "<p>Access denied: Invalid credentials (URL and/or API key).</p>";
//                        exit();
//                    }
//
//                    echo "<p>Credentials valid! Proceeding...</p>";
//
//                    /*
//                     * VIEW ACCOUNT DETAILS.
//                     */
//                    $account = $ac->api("account/view");
//                    echo "<pre>";
//                    print_r($account);
//                    echo "</pre>";
//                    /*
//                     * ADD NEW LIST.
//                     */
//                    $list = array(
//                        "name"           => "List 3",
//                        "sender_name"    => "My Company",
//                        "sender_addr1"   => "123 S. Street",
//                        "sender_city"    => "Chicago",
//                        "sender_zip"     => "60601",
//                        "sender_country" => "USA",
//                    );
//                    $list_add = $ac->api("list/add", $list);
//                    if (!(int)$list_add->success) {
//                        // request failed
//                        echo "<p>Adding list failed. Error returned: " . $list_add->error . "</p>";
//                        exit();
//                    }
//
//                    // successful request
//                    $list_id = (int)$list_add->id;
//                    echo "<p>List added successfully (ID {$list_id})!</p>";
//                    /*
//                     * ADD OR EDIT CONTACT (TO THE NEW LIST CREATED ABOVE).
//                     */
//                    $contact = array(
//                        "email"              => "test@example.com",
//                        "first_name"         => "Test",
//                        "last_name"          => "Test",
//                        "p[{$list_id}]"      => $list_id,
//                        "status[{$list_id}]" => 1, // "Active" status
//                    );
//                    $contact_sync = $ac->api("contact/sync", $contact);
//                    if (!(int)$contact_sync->success) {
//                        // request failed
//                        echo "<p>Syncing contact failed. Error returned: " . $contact_sync->error . "</p>";
//                        exit();
//                    }
//
//                    // successful request
//                    $contact_id = (int)$contact_sync->subscriber_id;
//                    echo "<p>Contact synced successfully (ID {$contact_id})!</p>";
//
//                }
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
        $this->hooks->call_hook('emailservice_destruct');
    }

}