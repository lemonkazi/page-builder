<?php

	function sendResponse($response) 
	{
		if (is_callable('fastcgi_finish_request')) {
			echo $response;
			session_write_close();
			fastcgi_finish_request();
		}        

		ignore_user_abort(true);
		set_time_limit(0);
		header("Connection: close\r\n");
		header("Content-Encoding: none\r\n");
		ob_start();
		echo $response;
		header('Content-Length: ' . ob_get_length(),TRUE);
		header('Connection: close');
		ob_end_flush();
		ob_flush();
		flush();
	}
	
?>