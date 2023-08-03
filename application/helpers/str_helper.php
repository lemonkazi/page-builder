<?php

    function clean($str) 
    {
   	
   		$str = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.

		return preg_replace('/[^A-Za-z0-9\-]/', '', $str); // Removes special chars.
	
	}