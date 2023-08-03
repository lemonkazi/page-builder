<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whitelabel_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function load($page = false)
    {

        $returnArray = [];
	    
        $this->db->from('settings');
        $this->db->where('name', 'colors');
        $this->db->or_where('name', 'logo_image');
        $this->db->or_where('name', 'logo_text');
        $this->db->or_where('name', 'custom_css');

        $q = $this->db->get();

        $res = $q->result();

        foreach( $res as $row )
        {

            if ( $row->name == 'colors' )
            {

                if ( $row->value == '' ) 
                {
                    $returnArray['css'] = false;
                }
                else
                {

                    $css = json_decode($row->value, true);

                    $str = "\n<style>\n";

                    foreach( $css['style'] as $name => $value )
                    {
                        foreach($value as $key => $item) {
                            //echo $key . " => " .$item."<br>";
                            $str .= "$key " . "{" . $item ." !important}\n";
                        }
                    }

                    $str .= '</style>';

                    $returnArray['css'] = $str;

                }

            }
            else
            {

                $returnArray[$row->name] = $row->value;

            }

        }

        return $returnArray;

    }

}