<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sentapi_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Looks for spam words and returns FALSE if found, TRUE if not found
     * @param  array    $data
     * @return boolian  TRUE/FALSE
     */
    public function spam_check($data)
    {
    	foreach ($data as $key=>$value)
        {
    		if (substr($key, 0, 1) != "_" && $key != "ci_session" && strpos($key,'wp-') === FALSE && $key != 'cc' && $key != 'bcc')
            {
    			foreach ($this->config->item("spam_words") as $spamWord)
                {
    				if (strpos($value, $spamWord) !== FALSE)
                    {
    				    return FALSE;
    				}
    			}
    		}
    	}

    	return TRUE;
    }

    /**
     * Returns FALSE if all fields are empty
     * @param  array    $data
     * @return mixed    $all_empty
     */
    public function all_empty($data)
    {
    	$all_empty = TRUE;
    	foreach ($data as $key=>$value)
        {
    		if (substr($key, 0, 1) != "_" && $key != "ci_session" && strpos($key,'wp-') === FALSE && $key != 'cc' && $key != 'bcc')
            {
    			if ($value != '')
                {
    				$all_empty = FALSE;
    			}
    		}
    	}

    	return $all_empty;
    }

}