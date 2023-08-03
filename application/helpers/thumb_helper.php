<?php
/**
 * Thumb()
 * A TimThumb-style function to generate image thumbnails on the fly.
 *
 * @access public
 * @param string $fullname
 * @param int $width
 * @param int $height
 * @param string $image_thumb
 * @return String
 *
 */
    function thumb($fullname, $width, $height, $force = false)
    {
        $CI = &get_instance();

        // Path to image thumbnail in your root
        $dir_thumbs = './tmp/thumbs/';
        $url = base_url() . 'tmp/thumbs/';
        // Get the CodeIgniter super object

        // get src file's extension and file name
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);
        $filename = pathinfo($fullname, PATHINFO_FILENAME);
        $image_thumb = $dir_thumbs . $filename . "-" . $height . '_' . $width . "." . $extension;
        $image_returned = $url . $filename . "-" . $height . '_' . $width . "." . $extension;

        if (!file_exists($image_thumb) || $force) {
            // LOAD LIBRARY
            $CI->load->library('image_lib');
            // CONFIGURE IMAGE LIBRARY
            $config['source_image'] = $fullname;
            $config['new_image'] = $image_thumb;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $width;
            $config['height'] = $height;
            //die(print_r($config));
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
        }
        return $image_returned;
    }