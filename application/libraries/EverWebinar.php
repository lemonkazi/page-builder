<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class EverWebinar
{
    public $client;
    public $api_key;
    public $base_url = 'https://webinarjam.genndi.com/api/everwebinar/';

    public function __construct($credentials = array())
    {
        $this->client = new Client();
        $this->api_key = $credentials['api_key'];
    }

    public function getWebinarsList()
    {
        $url = $this->base_url . 'webinars';

        try {

            $response = $this->client->request('POST', $url, [
                'form_params' => array(
                    'api_key' => $this->api_key
                )
            ]);

            return json_decode($response->getBody());

        } catch (Exception $exc) {

            return false;

        }

    }

    public function getWebinar($webinar_id, $timezone = null, $real_dates = null)
    {
        $url = $this->base_url . 'webinar';

        try {

            $response = $this->client->request('POST', $url, [
                'form_params' => array(
                    'api_key' => $this->api_key,
                    'webinar_id' => $webinar_id,
                    'timezone' => $timezone,
                    'real_dates' => $real_dates
                )
            ]);

            return json_decode($response->getBody());

        } catch (Exception $exc) {

            return false;

        }

    }

    public function registerToWebinar($credentials = array())
    {
        $url = $this->base_url . 'register';

        try {

            $response = $this->client->request('POST', $url, [
                'form_params' => array(
                    'api_key' => $this->api_key,
                    'webinar_id' => $credentials['webinar_id'],
                    'first_name' => $credentials['first_name'],
                    'last_name' => isset($credentials['last_name']) ? $credentials['last_name'] : null, 
                    'email' => $credentials['email'],
                    'schedule' => $credentials['schedule'],
                    'ip_address' => isset($credentials['ip_address']) ? $credentials['ip_address'] : null,
                    'phone_country_code' => isset($credentials['phone_country_code']) ? $credentials['phone_country_code'] : null,
                    'phone' => isset($credentials['phone']) ? $credentials['phone'] : null,
                    'timezone' => $credentials['timezone'],
                    'real_dates' => $credentials['real_dates']
                )
            ]);

            return json_decode($response->getBody());

        } catch (Exception $exc) {

            return false;

        }

    }

}