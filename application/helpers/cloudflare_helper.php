<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function callAPI ($method, $endpoint, $auth_email, $auth_key, $data = false) 
{

	$ch = curl_init();

	if ( $method === 'POST' )
	{
		 curl_setopt($ch, CURLOPT_POST, 1);

		 if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	}

	if ( $method === 'DEL' )
	{
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	}

	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/' . $endpoint);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    	'X-Auth-Email: ' . $auth_email,
    	'X-Auth-Key: ' . $auth_key,
    	'Content-Type: application/json'
	));

	$res = curl_exec($ch);
	curl_close($ch);

	return $res;

}