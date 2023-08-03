<?php

namespace App\Citrixoauth;

/**
 * Description of Auth
 *
 * @author DalPraS
 */
abstract class Auth {

	/**
	 * API key or Secret Key in Citrix's Developer Portal
	 *
	 * @var string
	 */
	protected $apiKey;

	/**
	 * Consumer Secret in Citrix's Developer Portal
	 *
	 * @var string
	 */
	protected $apiSecret;

	/**
	 * Access Token
	 *
	 * @var string
	 */
	protected $accessToken;

	/**
	 * Organizer Key
	 *
	 * @var int
	 */
	protected $organizerKey;

	/**
	 * @var int
	 */
	protected $expiresIn;

	/**
	 * @var string
	 */
	protected $refreshToken;

	protected $tokenDate;

	/**
	 * @return mixed
	 */
	public function getTokenDate() {
		return $this->tokenDate;
	}

	/**
	 * @param mixed $tokenDate
	 */
	public function setTokenDate($tokenDate){

		$this->tokenDate = $tokenDate;
	}

	/**
	 * Error list
	 *
	 * @var array
	 */
	private $errors = [];

	abstract public function applyCredentials();

	/**
	 * Process the citrix Authentication output.
	 *
	 * @param mixed $output
	 *
	 * @throws \Exception
	 */
	public function process($output) {
		switch (TRUE) {
			case empty($output):
				$this->addError('Empty response from CITRIX');
				break;
			case isset($output['msg']):
				$this->addError($output['msg']);
				break;
			case isset($output['int_error_code']):
				$this->addError($output['int_error_code']);
				break;
			case isset($output['access_token'], $output['organizer_key'], $output['expires_in'], $output['refresh_token']):
				$this->setAccessToken($output['access_token']);
				$this->setOrganizerKey($output['organizer_key']);
				$this->setRefreshToken($output['refresh_token']);
				$this->setExpiresIn($output['expires_in']);
				$this->setTokenDate(time());
				break;
			case \is_array($output):
				$this->setErrors($output);
				break;
			default:
				throw new \RuntimeException("Invalid Output: {$output}");
		}
	}

	/**
	 * @return string $accessToken
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param string $accessToken
	 *
	 * @return \citrixOAuth2\Auth
	 */
	public function setAccessToken($accessToken) {
		$this->accessToken = $accessToken;

		return $this;
	}

	/**
	 * @return int $organizerKey
	 */
	public function getOrganizerKey() {
		return $this->organizerKey;
	}

	/**
	 * @param int $organizerKey
	 *
	 * @return \citrixOAuth2\Auth
	 */
	public function setOrganizerKey($organizerKey) {
		$this->organizerKey = $organizerKey;

		return $this;
	}

	public function getErrorsAsString() {
		return implode('n', $this->errors);
	}

	public function hasErrors() {
		return count($this->errors) > 0;
	}

	public function addError($error) {
		$this->errors[] = $error;

		return $this;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function setErrors($errors) {
		$this->errors = $errors;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getApiSecret() {
		return $this->apiSecret;
	}

	/**
	 * @param mixed $apiSecret
	 */
	public function setApiSecret($apiSecret) {
		$this->apiSecret = $apiSecret;
	}

	/**
	 * @return mixed
	 */
	public function getExpiresIn() {
		return $this->expiresIn;
	}

	/**
	 * @param mixed $expiresIn
	 */
	public function setExpiresIn($expiresIn) {
		$this->expiresIn = $expiresIn;
	}

	/**
	 * @return mixed
	 */
	public function getRefreshToken() {
		return $this->refreshToken;
	}

	/**
	 * @param mixed $refreshToken
	 */
	public function setRefreshToken($refreshToken) {
		$this->refreshToken = $refreshToken;
	}
}
