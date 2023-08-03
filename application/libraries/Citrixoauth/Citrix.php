<?php

namespace App\Citrixoauth;

use App\Citrixoauth\Entity\EntityAbstract;

/**
 * Citrix data loader
 *
 * Use Citrix to connect to GoToWebinar Citrix Service for direct
 * interoperability.
 */
class Citrix {

	 const endpoint = 'https://api.getgo.com';

	/**
	 * Mime content types
	 */
	 const MIME_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';
	 const MIME_MULTIPART_FORM_DATA = 'multipart/form-data';
	 const MIME_JSON = 'application/json';

	/**
	 * Authentication Client
	 *
	 * @var Auth
	 */
	private $auth;

	public function __construct(Auth $auth) {
		$this->auth = $auth;
	}

	/**
	 * Map response to an entity or collection of entities.
	 *
	 * @param array                 $response
	 *          The response to process for having a collection or object
	 * @param boolean               $single
	 *          Define if the response is a collection or not
	 * @param Entity\EntityAbstract $entity
	 *          The Entity to be hydrated or cloned
	 *
	 * @return \ArrayObject
	 * @throws \Exception
	 */
	private function process($response, $single, EntityAbstract $entity) {
		switch (TRUE) {
			case isset($response['int_err_code']):
				throw new \RuntimeException($response['msg']);

//			case isset($response['Details']):
//				throw new \Exception($response['Details']);
//
//			case isset($response['description']):
//				throw new \Exception($response['description']);
		}

		if ($single === TRUE) {
			/* @var $entity Entity\EntityAbstract */
			return $entity->hydrate($response);
		}

		$collection = new \ArrayObject([]);
		foreach ($response as $data) {
			/* @var $clone Entity\EntityAbstract */
			$clone = clone $entity;
			$clone->hydrate($data);
			$collection->append($clone);
		}

		return $collection;
	}

	/**
	 * Send API request, but pass the $oauthToken first.
	 * Return array of Citrix API call.
	 *
	 * @param string                      $url
	 * @param string                      $method
	 * @param array|Entity\EntityAbstract $data
	 * @param string                      $headers
	 *
	 * @param bool                        $debug
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function send($url, $method, $data, $headers = null, $debug = FALSE) {
		if (\is_object($data)) {
			$data = $data->feed();
		}

		$ch = curl_init();

		// Content-Type is The MIME type of the body of the request (used with POST and PUT requests).
		if (\in_array($method, ['POST', 'PUT']) && isset($headers['Content-Type'])) {
			switch ($headers['Content-Type']) {
				case self::MIME_X_WWW_FORM_URLENCODED:
					$body = http_build_query($data, '', '&');
					break;

				case self::MIME_JSON:
					$body = json_encode($data);
					break;

				case self::MIME_MULTIPART_FORM_DATA:
					$body = $data;
					break;
			}
		}

		// CURLOPT_HEADER = TRUE:
		//      to include the header in the output.
		//
		// CURLOPT_POST = TRUE;
		//      to do a regular HTTP POST. This POST is the
		//      normal application/x-www-form-urlencoded kind, most commonly used by HTML forms.
		//
		// CURLOPT_POSTFIELDS = MIXED;
		//      This parameter can either be passed as a urlencoded string
		//      like 'para1=val1&para2=val2&...' or as an array with the field
		//      name as key and field data as value or as a string json_encoded.
		//
		// CURLOPT_FOLLOWLOCATION = TRUE;
		//      to follow any "Location: " header that the server sends as
		//      part of the HTTP header (note this is recursive,
		//      PHP will follow as many "Location: " headers that it is sent,
		//      unless CURLOPT_MAXREDIRS is set).
		//
		// CURLOPT_MAXREDIRS = INT;
		//      The maximum amount of HTTP redirections to follow.
		//
		// CURLOPT_CUSTOMREQUEST = 'PUT', 'DELETE', ecc
		//      A custom request method to use instead of "GET" or "HEAD" when
		//      doing a HTTP request.
		switch (TRUE) {
			// insert
			case $method === 'POST':
				curl_setopt($ch, CURLOPT_POST, TRUE); // tell curl you want to post something
				curl_setopt($ch, CURLOPT_POSTFIELDS, $body); // define what you want to post
				break;

			// update
			case $method === 'PUT':
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
				break;

			// auth
			case $method === 'OAUTH':
				curl_setopt($ch, CURLOPT_HEADER, TRUE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
				$query = http_build_query($data, '', '&');
				$url   = $url . '?' . $query;
				break;

			// get
			case $method === 'GET':
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
				$query = http_build_query($data, '', '&');
				$url   = $url . '?' . $query;
				break;

			// delete
			case $method === 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
				break;

			default:
				throw new \RuntimeException("Invalid method {$method}!");
		}

		if ($headers !== null) {
			$httpHeaders = [];
			foreach ($headers as $header => $value) {
				$httpHeaders[] = "{$header}: {$value}";
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // return the output in string format
		if ($debug) {
			curl_setopt($ch, CURLINFO_HEADER_OUT, FALSE);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			$verbose = fopen('php://temp', 'wb+');
			curl_setopt($ch, CURLOPT_STDERR, $verbose);
		}

		$output = curl_exec($ch);
		curl_close($ch); // close curl handle

		if ($debug) {
			rewind($verbose);
			$verboseLog = stream_get_contents($verbose);
			echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
			die();
		}
		switch ($headers['Accept']) {
			case self::MIME_JSON:
				$result = (array)json_decode($output, TRUE, 512);
				break;

			default:
				$result = $output;
		}

		return $result;
	}

	/**
	 * Create the correct headers needed for OAuth,
	 *
	 * @param string $oauthToken
	 *
	 * @return array
	 */
	private function authTokenHeaders($oauthToken) {
		$headers = [
			'Content-Type'  => OAuthCitrix::MIME_JSON,
			'Accept'        => OAuthCitrix::MIME_JSON,
//			'Authorization' => 'OAuth oauth_token=' . $oauthToken,
			'Authorization' => 'Bearer ' . $oauthToken,
		];

		return $headers;
	}

	public function getAuth() {
		return $this->auth;
	}

	/**
	 * Return the authentication token from Auth.
	 *
	 * @return string
	 */
	private function getAccessToken() {
		return $this->auth->getAccessToken();
	}

	/**
	 * Get upcoming webinars.
	 *
	 * @return \ArrayObject
	 */
	public function getUpcoming() {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/upcomingWebinars';
		try {
//			$response = self::send($url, 'GET', [], $this->authTokenHeaders($this->getAccessToken()), FALSE);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Authorization: Bearer " . $this->getAccessToken(),
                    "Cache-Control: no-cache",
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);

		} catch (\Exception $e) {
			return FALSE;
		}

		if ($response && count($response)) {
		    return $response;
        }

        return FALSE;

//		try {
//			$arrayObject = $this->process($response, FALSE, new Entity\Webinar\Get());
//		} catch (\Exception $e) {
//			return FALSE;
//		}

		return $arrayObject;
	}

	/**
	 * Get all webinars.
	 *
	 * @return \ArrayObject
	 */
	public function getWebinars() {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars';
		try {
			$response = self::send($url, 'GET', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		try {
			$arrayObject = $this->process($response, FALSE, new Entity\Webinar\Get());
		} catch (\Exception $e) {
			return FALSE;
		}

		return $arrayObject;
	}

	/**
	 * Get all past webinars in date range.
	 *
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 *
	 * @return \ArrayObject
	 */
	public function getPast($startDate, $endDate = null) {
		$this->auth->applyCredentials();
		$utcTimeZone = new \DateTimeZone('UTC');
		if ($endDate === null) {
			$endDate = new \DateTime('now', $utcTimeZone);
		}
		$url  = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/historicalWebinars';
		$data = [
			'fromTime' => $startDate->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z'),
			'toTime'   => $endDate->format('Y-m-d\TH:i:s\Z'),
		];
		try {
			$response = self::send($url, 'GET', $data, $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		try {
			$arrayObject = $this->process($response, FALSE, new Entity\Webinar\Get());
		} catch (\Exception $e) {
			return FALSE;
		}

		return $arrayObject;
	}

	/**
	 * Get info for a single webinar by passing the webinar id or
	 * in Citrix's terms webinarKey.
	 *
	 * @param int $webinarKey
	 *
	 * @return \ArrayObject
	 * @throws \Exception
	 */
	public function getWebinar($webinarKey) {
		$this->auth->applyCredentials();
		$url      = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey;
		try {
			$response = self::send($url, 'GET', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return false;
		}

		try {
			$arrayObject = $this->process($response, TRUE, new Entity\Webinar\Get());
		} catch (\Exception $e) {
			return false;
		}

		return $arrayObject;
	}

	/**
	 * Create a new webinar.
	 * Return the same entity with the property WebinarKey hydrated.
	 *
	 * @param Webinar\Post $entity
	 *
	 * @return \ArrayObject|bool
	 */
	public function createWebinar($entity) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars';
		try {
			$response = self::send($url, 'POST', $entity, $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		try {
			$arrayObject = $this->process($response, TRUE, $entity);
		} catch (\Exception $e) {
			return FALSE;
		}

		return $arrayObject;
	}

	/**
	 * Update an existing webinar.
	 *
	 * @param Webinar\Put $entity
	 *
	 * @return array
	 */
	public function updateWebinar($entity) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $entity->getWebinarKey();
		try {
			$response = self::send($url, 'PUT', $entity, $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		return $response;
	}

	/**
	 * Delete a webinar.
	 *
	 * @param string $webinarKey
	 *
	 * @return array|bool
	 */
	public function deleteWebinar($webinarKey) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey;
		try {
			$response = self::send($url, 'DELETE', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		return $response;
	}

	/**
	 * Get all registrants for a given webinar.
	 *
	 * @param int $webinarKey
	 *
	 * @return \ArrayObject|bool
	 */
	public function getRegistrants($webinarKey) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants';
		try {
			$response = self::send($url, 'GET', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		try {
			$arrayObject = $this->process($response, FALSE, new Entity\Registrant\Get());
		} catch (\Exception $e) {
			return FALSE;
		}

		return $arrayObject;
	}

	/**
	 * Get a single registrant for a given webinar.
	 *
	 * @param int $webinarKey
	 * @param int $registrantKey
	 *
	 * @return \ArrayObject|bool
	 */
	public function getRegistrant($webinarKey, $registrantKey) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants/' . $registrantKey;
		try {
			$response = self::send($url, 'GET', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		try {
			$arrayObject = $this->process($response, TRUE, new Entity\Registrant\Get());
		} catch (\Exception $e) {
			return FALSE;
		}

		return $arrayObject;
	}

	/**
	 * Get a single registrant for a given webinar by email.
	 *
	 * @param int $webinarKey
	 * @param     $email
	 *
	 * @return Entity\Registrant\Get
	 */
	public function getRegistrantByEmail($webinarKey, $email) {
		$registrants = $this->getRegistrants($webinarKey);
		/* @var $registrant Entity\Registrant\Get */
		foreach ($registrants as $registrant) {
			if ($registrant->getEmail() == $email) {
				return $registrant;
			}
		}

		return null;
	}

	/**
	 * Get all attendees for a given webinar.
	 *
	 * @param int $webinarKey
	 *
	 * @return \ArrayObject
	 */
	public function getAttendees($webinarKey) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey . '/attendees';
		try {
			$response = self::send($url, 'GET', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		try {
			$arrayObject = $this->process($response, FALSE, new Entity\Registrant\Get());
		} catch (\Exception $e) {
			return FALSE;
		}

		return $arrayObject;
	}

	/**
	 * Register user for a webinar
	 *
	 * @param int                    $webinarKey
	 * @param Entity\Registrant\Post $entity
	 *
	 * @return \ArrayObject|bool
	 */
	public function register($webinarKey, $entity) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants';
		try {
//			$response = self::send($url, 'POST', $entity, $this->authTokenHeaders($this->getAccessToken()));

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($entity),
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/vnd.citrix.g2wapi-v1.1+json",
                    "Authorization: Bearer " . $this->getAccessToken(),
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
        } catch (\Exception $e) {
			return FALSE;
		}

		if (isset(json_decode($response)->int_err_code)) {
            $response = false;
        } else {
            $response = true;
        }

        return $response;

//		try {
//			$arrayObject = $this->process($response, TRUE, $entity);
//		} catch (\Exception $e) {
//			return FALSE;
//		}

//		return $arrayObject;
	}

	/**
	 * Register user for a webinar
	 *
	 * @param int $webinarKey
	 * @param int $registrantKey
	 *
	 * @return array|bool
	 */
	public function unregister($webinarKey, $registrantKey) {
		$this->auth->applyCredentials();
		$url = self::endpoint . '/G2W/rest/organizers/' . $this->auth->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants/' . $registrantKey;
		try {
			$response = self::send($url, 'DELETE', [], $this->authTokenHeaders($this->getAccessToken()));
		} catch (\Exception $e) {
			return FALSE;
		}

		return $response;
	}

}
