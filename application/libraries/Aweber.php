<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
const OAUTH_URL = 'https://auth.aweber.com/1.0/oauth/';

class Aweber{

    public $stack;
    public $auth_stack;
    public $auth;
    public $client;
    public $consumerKey;
    public $consumerSecret;
    public $token;
    public $tokenSecret;
    public $params = [];

    public function __construct($credentials = array()){
        $this->consumerKey = $credentials['consumerKey'];
        $this->consumerSecret = $credentials['consumerSecret'];
        $this->token = isset($credentials['token']) ? $credentials['token'] : '';		
        $this->tokenSecret = isset($credentials['token_secret']) ? $credentials['token_secret'] : '';

        $this->stack = HandlerStack::create();
        $this->client = new Client([
            'base_uri' => 'https://api.aweber.com/1.0/',
            'handler' => $this->stack,
            'auth' => 'oauth'
        ]);

    }

    public function authorize(){
        try{
            $this->auth_stack = HandlerStack::create();
            $this->auth = new Client([
                'base_uri' => OAUTH_URL,
                'handler' => $this->auth_stack,
                'auth' => 'oauth'
            ]);
            $requestMiddleware = new Oauth1([
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
                'token' => null,
                'token_secret' => null,
            ]);
            $this->auth_stack->push($requestMiddleware);

            $res = $this->auth->post('request_token', ['form_params' => ['oauth_callback' => 'oob']]);
            parse_str($res->getBody(), $this->params);
            $output = [
                'consumerKey' => $this->consumerKey,
                'consumerSecret' => $this->consumerSecret,
                'accessToken' => $this->params['oauth_token'],
                'tokenSecret' => $this->params['oauth_token_secret'],
                'url' => OAUTH_URL . "authorize?oauth_token={$this->params['oauth_token']}"
            ];
            return $output;
        }catch(Exception $exc){
            return false;
        }
    }

    public function getAllAccounts($aweber_credentials = array(), $verifier = null){
        try{
            $stack = HandlerStack::create();
            $client = new Client([
                'base_uri' => OAUTH_URL,
                'handler' => $stack,
                'auth' => 'oauth'
            ]);
            $accessMiddleware = new Oauth1([
                'consumer_key' => $aweber_credentials['consumerKey'],
                'consumer_secret' => $aweber_credentials['consumerSecret'],
                'token' => $aweber_credentials['accessToken'],
                'token_secret' => $aweber_credentials['tokenSecret'],
            ]);
            $stack->push($accessMiddleware);
            $res1 = $client->post('access_token', ['form_params' => ['oauth_verifier' => $verifier]]);
            $cred = [];
            parse_str($res1->getBody(), $cred);
            $output = [
                'email' => $aweber_credentials['email'],
                'consumerKey' => $aweber_credentials['consumerKey'],
                'consumerSecret' => $aweber_credentials['consumerSecret'],
                'accessToken' => $cred['oauth_token'],
                'tokenSecret' => $cred['oauth_token_secret'],
            ];
            return $output;
        }catch(Exception $exc){
            return false;
        }
    }

    public function getCollection($url){
        $collection = array();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'AWeber-PHP-code-sample/1.1'

        ];
        while(isset($url)){
            $body = $this->client->get($url, ['headers' => $headers])->getBody();
            $page = json_decode($body, true);
            $collection = array_merge($page['entries'], $collection);
            $url = isset($page['next_collection_link']) ? $page['next_collection_link'] : null;
        }
        return $collection;
    }

    public function getLists(){

        $requestMiddleware = new Oauth1([
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret,
            'token' => $this->token,
            'token_secret' => $this->tokenSecret,
        ]);

        $this->stack->push($requestMiddleware);

        $accounts = $this->getCollection('accounts');
        $listsUrl = $accounts[0]['lists_collection_link'];
        $lists = $this->getCollection($listsUrl);

        return $lists;
    }

    public function addSubscriber($data = array(), $list_id = '5276738'){
        try{
            // Load credentials into the Oauth client
            $requestMiddleware = new Oauth1([
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
                'token' => $this->token,
                'token_secret' => $this->tokenSecret,
            ]);
            $this->stack->push($requestMiddleware);
            $accounts = $this->getCollection('accounts');
            // get all the list entries for the first account
            $listsUrl = $accounts[0]['lists_collection_link'];
            $listUrl = $listsUrl . '/' . $list_id;
            $list = json_decode($this->client->get($listUrl)->getBody());
            $subsUrl = $list->subscribers_collection_link;
            $body = $this->client->post($subsUrl, ['json' => $data]);
            // get the subscriber entry using the Location header from the post request
            $subscriberUrl = $body->getHeader('Location')[0];
            $subscriberResponse = $this->client->get($subscriberUrl)->getBody();
            $subscriber = json_decode($subscriberResponse);
            return $subscriber;
        }catch(Exception $exc){
            return false;
        }
    }
}