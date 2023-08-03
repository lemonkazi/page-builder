<?php

class Screenshot_library
{
    function __construct()
    {
        $this->_CI = & get_instance();
    }

    /**
     * @param $url
     * @param $fileName
     * @return $fileName
     */
    public function make_screenshot($url, $fileName = '', $size = '400x300', $destination, $selectorId = false, $crop = false)
    {

        $config = array();
        $config['dimension'] = '347x293';
        $config['delay'] = 0;

        if ( $selectorId ) $config['selectorId'] = $selectorId;
        if ( $crop ) $config['crop'] = $crop;

        $screen = $this->screenshotapi($url, $config);

        $ch = curl_init ($screen);

        if ( $ch )
        {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            $image = curl_exec($ch);

            if ( $image ) {
                curl_close ($ch);

                $fp = fopen("./". $destination . $fileName, 'w');

                if ( $fp )
                {
                    fwrite($fp, $image);
                    fclose($fp);

                    return $fileName;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }

    /**
     * @param $url
     * @param $args
     * @return string
     */
    private function screenshotapi($url, $args)
    {
        //access key
        $access_key = $this->_CI->config->item('screenshot_api_key');

        //secret keyword
        $secret_keyword = $this->_CI->config->item('screenshot_secret');
        $secret_key = md5($url . $secret_keyword);

        //encode URL
        $params['url'] = urlencode($url);

        $params += $args;

        //create the query string based on the options
        foreach($params as $key => $value) { $parts[] = "$key=$value"; }

        //compile query string
        $query = implode("&", $parts);

        //call API and return the image
        return "https://api.bloxby.com/v1/screenshot/?api_key=$access_key&hash=$secret_key&$query";

    }

}
