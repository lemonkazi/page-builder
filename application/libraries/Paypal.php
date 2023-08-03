<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paypal {

	public $api_username;
	public $api_password;
	public $api_signature;
	public $paypal_sandbox;
	public $paypal_api;
	public $paypal_url;
	public $version;
	public $subscriber_id;

	/**
     * Class constructor
     *
     * @return  void
     */
	public function __construct($options = array())
	{

		$this->api_username = $options['username'];
		$this->api_password = $options['password'];
		$this->api_signature = $options['signature'];
		$this->paypal_sandbox = $options['sandbox'];
		$this->version = urlencode('98');

		if ($this->paypal_sandbox)
		{
			$this->paypal_api = 'https://api-3t.sandbox.paypal.com/nvp';
			$this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
			$this->paypal_ipn_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
		}
		else
		{
			$this->paypal_api = 'https://api-3t.paypal.com/nvp';
			$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr?';
			$this->paypal_ipn_url = 'https://ipnpb.paypal.com/cgi-bin/webscr';
		}

	}

	/**
	 * To check the request
	 *
     * @param 	string 	$method
     * @param 	string 	$data
     * @return 	array 	$httpParsedResponseAr
     */
	public function request($method, $data, $string = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->paypal_api);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		$nvpreq = "METHOD=" . $method . "&VERSION=" . $this->version . "&PWD=" . $this->api_password . "&USER=" . $this->api_username . "&SIGNATURE=" . $this->api_signature . "$data";
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		$httpResponse = curl_exec($ch);

		// Making log to database:
		$this->ci = & get_instance();
		$this->ci->db->insert('payment_log',
			array(
				'request' => $nvpreq,
				'response' => $httpResponse,
				'date' => date("Y-m-d H:i:s")
			)
		);

		if ( ! $httpResponse)
		{
			exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
		}

		if ($string)
		{
			return $httpResponse;
		}

		$httpResponseAr = explode("&", $httpResponse);
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value)
		{
			$tmpAr = explode("=", $value);
			if (sizeof($tmpAr) > 1)
			{
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
		return $httpParsedResponseAr;
	}

	/**
	 * Function for express checkout
	 *
	 * @param 	numeric 	$token
	*/
	public function do_express_checkout($token)
	{
		header("Location: " . $this->paypal_url . "cmd=_express-checkout&token=$token");
		exit;
	}

	/**
	 * Function to generate token
	 *
	 * @param 	array 	$data
	 * @return 	array 	$response
	*/
	public function get_token($data)
	{
		$returnURL = isset($data['returnurl']) ? $data['returnurl'] : false;
		$cancelURL = isset($data['cancelurl']) ? $data['cancelurl'] : false;
		$name = isset($data['name']) ? $data['name'] : false;
		$description = isset($data['description']) ? $data['description'] : false;
		$amount = isset($data['amount']) ? $data['amount'] : false;
		$qty = isset($data['qty']) ?$data['qty'] : 1;
		$currency = isset($data['currency']) ? $data['currency'] : false;
		$subscription_info = isset($data['subscription_info']) ? $data['subscription_info'] : false;

		$data = '&RETURNURL='.urlencode($returnURL);
		$data .= '&CANCELURL='.urlencode($cancelURL);
		$data .= '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
		$data .= '&L_PAYMENTREQUEST_0_NAME0='.urlencode($name);
		$data .= '&L_PAYMENTREQUEST_0_DESC0='.urlencode($description);
		$data .= '&L_PAYMENTREQUEST_0_AMT0='.urlencode($amount);
		$data .= '&L_PAYMENTREQUEST_0_QTY0='. $qty;
		$data .= '&L_BILLINGTYPE0=RecurringPayments';
		$data .= '&L_BILLINGAGREEMENTDESCRIPTION0='. urlencode($subscription_info);
		$data .= '&NOSHIPPING=1';
		$data .= '&PAYMENTREQUEST_0_AMT='.urlencode($amount);
		$data .= '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($currency);

		$method = "SetExpressCheckout";
		$response = $this->request($method, $data);
		if (isset($response['TOKEN']))
		{
			return $token = $response['TOKEN'];
		}
		else
		{
			return $response;
		}
	}

	/**
	 * Function To create a recurring profile
	 *
	 * @param 	array 	$data
	 * @param 	array 	$token
	 * @return 	array 	$response
	*/
	public function create_recurring_profile($token, $data)
	{
		$GetExpressCheckoutDetailsdata = "&TOKEN=$token";
		$method = "GetExpressCheckoutDetails";
		$GetExpressCheckoutDetails = $this->request($method, $GetExpressCheckoutDetailsdata, true);

		$DoExpressCheckoutPaymentdata = "&" . $GetExpressCheckoutDetails;
		$method = "DoExpressCheckoutPayment";
		$DoExpressCheckoutPaymentresponse = $this->request($method, $DoExpressCheckoutPaymentdata);

		// PAYMENTINFO_0_TRANSACTIONID
		$GetExpressCheckoutDetails = explode("&", $GetExpressCheckoutDetails);
		$GetExpressCheckoutDetailsresponse = array();
		foreach ($GetExpressCheckoutDetails as $i => $value)
		{
			$tmpAr = explode("=", $value);
			if (sizeof($tmpAr) > 1)
			{
				$GetExpressCheckoutDetailsresponse[$tmpAr[0]] = $tmpAr[1];
			}
		}

		$startdate = isset($data['start_date'])?$data['start_date']:"";
		$methodName = "CreateRecurringPaymentsProfile";
		$dt = new DateTime($startdate);
		$dt->setTimeZone(new DateTimeZone('UTC'));
		$datetime = $dt->format('Y-m-d\TH:i:s\Z');
		$email = $GetExpressCheckoutDetailsresponse['EMAIL'];
		$currency = $GetExpressCheckoutDetailsresponse['CURRENCYCODE'];
		$transactionid = $DoExpressCheckoutPaymentresponse['PAYMENTINFO_0_TRANSACTIONID'];
		$payerid = $GetExpressCheckoutDetailsresponse['PAYERID'];
		$previous_payment_date = $DoExpressCheckoutPaymentresponse['TIMESTAMP'];
		$billing_period = isset($data['billing_period'])?$data['billing_period']:"Month";
		$billing_frequency = isset($data['billing_frequency'])?$data['billing_frequency']:12;
		$subscription_info = isset($data['subscription_info'])?$data['subscription_info']:"";
		$max_fail_payment = isset($data['max_fail_payment'])?$data['max_fail_payment']:0;
		$name = isset($data['name'])?$data['name']:"";
		$amount = isset($data['amount'])?$data['amount']:"";
		$qty = isset($data['qty'])?$data['qty']:1;

		$data = "&TOKEN=".urlencode($token);
		$data .= '&PROFILESTARTDATE='.$datetime;
		$data .= "&EMAIL=".urlencode($email);
		$data .= "&CURRENCYCODE=".urlencode($currency);
		$data .= "&DESC=".urlencode($subscription_info);
		$data .= '&BILLINGPERIOD='.$billing_period;
		$data .= '&BILLINGFREQUENCY='.$billing_frequency;
		$data .= "&AMT=".urlencode($amount);
		$data .= "&MAXFAILEDPAYMENTS=".urlencode($max_fail_payment);
		$data .= '&L_PAYMENTREQUEST_0_NAME0='.urlencode($name);
		$data .= '&L_PAYMENTREQUEST_0_AMT0='.urlencode($amount);
		$data .= '&L_PAYMENTREQUEST_0_QTY0='. urlencode($qty);
		$data .= '&L_PAYMENTREQUEST_0_ITEMCATEGORY0=Digital';

		$method = "CreateRecurringPaymentsProfile";
		$response = $this->request($method, $data);
		$response['transactionid'] = $transactionid;
		$response['payerid'] = $payerid;
		$response['previous_payment_date'] = $previous_payment_date;
		return $response;
	}

	/**
	 * Function to get recurring profile
	 *
	 * @param 	integer 	$profileid
	 * @return 	array 		$response
	*/
	public function get_recurring_profile($profileid)
	{
		$data = "&PROFILEID=$profileid";
		$method = "GetRecurringPaymentsProfileDetails";
		$response = $this->request($method, $data);
		return $response;
	}

	/**
	 * Function to update the recurring profile
	 *
	 * @param 	integer 	$profileid
	 * @param 	array 		$data
	 * @return 	array 		$response
	*/
	public function update_recurring_profile($profileid, $data)
	{
		$amount = isset($data['amount']) ? $data['amount'] : $data['amount'];
		$currency = isset($data['currency']) ? $data['currency'] : $data['currency'];

		$data = "&PROFILEID=$profileid";
		$data .= "&AMT=$amount";
		$data .= "&CURRENCYCODE=$currency";

		$method = "UpdateRecurringPaymentsProfile";

		$response = $this->request($method, $data);
		return $response;
	}

	/**
	 * Following function is for changing profile status
	 * (allowed status: Cancel, Suspend, Reactivate )
	 *
	 * @param 	integer 	$profile_id
	 * @param 	string 		$status
	 * @return 	array 		$response
	*/
	public function manage_recurring_profile_status($profile_id, $status, $reason = "Package changed"){

		$data = "&PROFILEID=$profile_id";
		$data .= "&ACTION=$status";
		$data .= "&NOTE=" . urlencode($reason);
		$response = $this->request("ManageRecurringPaymentsProfileStatus", $data);
		return $response;
	}

	/**
	* Following function is used for refund process.
	* In Bloxby we will use it while cancelling any current paid subscription
	*
	* @param 	integer 	$data
	* @return 	array 		$response
	*/
	public function process_refund($data)
	{
		$amount = isset($data['amount']) ? urlencode($data['amount']) : null;
		$transactionid = isset($data['transactionid']) ? urlencode($data['transactionid']) : "";
		$note = isset($data['note']) ? urlencode($data['note']) : "";

		$data = "&TRANSACTIONID=$transactionid";

		if ($amount != null)
		{
			$data .= "&AMT=$amount";
			$refundtype = "Partial";
		}
		else
		{
			$refundtype = "Full";
		}
		$data .= "&REFUNDTYPE=$refundtype";
		$data .= "&NOTE=$note";

		$method = "RefundTransaction";

		$response = $this->request($method, $data);
		return $response;
	}

    /**
    * Following function is used for IPN and process the transaction activities
    *
    * @param 	array 		$data
	* @param 	boolean 	true/false
    */
	public function verify_ipn($data)
	{
		$post_data = array();
		foreach($data as $val)
		{
			$value = explode("=", $val);
			if (count($value) == 2)
			{
				if ($value[0] == 'payment_date')
				{
					if (substr_count($value[1], "+") === 1)
					{
						$value[1] = str_replace("+", "%2B", $value[1]);
					}
				}
				$post_data[$value[0]] = urldecode($value[1]);
			}
		}

		$req = 'cmd=_notify-validate';

		foreach ($post_data as $key => $values)
		{
			$values = urlencode($values);
			$req .= "&$key=$values";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->paypal_ipn_url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		$response = curl_exec($ch);
		curl_close($ch);

		if ($response == "VERIFIED")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}