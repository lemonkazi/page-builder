<?php
/**
 * Thumb()
 * A TimThumb-style function to generate image thumbnails on the fly.
 *
 * @access public
 * @param string $url
 * @return Boolean
 *
 */
    function doesUrlLoad($url)
    {

        if (!$fp = curl_init($url)) return false;
        return true;

    }