<?php

namespace Citrixoauth\Auth;

use Citrixoauth\Auth;

/**
 * Citrix Authentication
 *
 * Use this class to authenticate into Citrix APIs.
 */
class Direct extends Auth  {

    /**
     * Username for direct authentication.
     *
     * @var string
     */
    private $username;

    /**
     * Password for direct authentication.
     *
     * @var string
     */
    private $password;

    /**
     * Direct authentication URL
     *
     * @var string
     */
    private $authUrl = 'https://api.getgo.com/oauth/access_token';

    /**
     * Iniziatialize the auth adapter
     *
     * @param string $apiKey
     */
    public function __construct($apiKey) {
        $this->apiKey   = $apiKey;
    }

    /**
     * Direct authentication.
     *
     * Using the authentication username and password, the 'access_token' and
     * 'organizer_key' are dinamically setted for the transaction.
     * Those are the same username and password that you use to login to www.gotowebinar.com
     */
    public function applyCredentials() {
        if ($this->accessToken != null && $this->organizerKey != null) {
            return $this;
        }

        if ($this->username == null || $this->password == null) {
            throw new \Exception('Invalid accessToken or organizerKey for obtaining Citrix credentials');
        }

        $params = [
            'grant_type' => 'password',
            'user_id'    => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->apiKey
        ];

        $output = Citrix::send($this->authUrl, 'GET', $params, ['Accept' => Citrix::MIME_JSON]);
        $this->process($output);
        return $this;
    }

    /**
     * @param type $username
     * @return $this
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @param type $password
     * @return $this
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

}
