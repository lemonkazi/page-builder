<?php

class Database {

	/** Function to the database and tables and fill them with the default data */
	function create_database($data)
	{
		/** Connect to the database */
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], '');

		/** Check for errors */
		if (mysqli_connect_errno())
		{
			return FALSE;
		}

		/** Create the prepared statement */
		$mysqli->query("CREATE DATABASE IF NOT EXISTS " . $data['database']);

		/** Close the connection */
		$mysqli->close();

		return TRUE;
	}

	/** Function to create the tables and fill them with the default data */
	function create_tables($data)
	{
		/** Connect to the database */
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);

		/** Check for errors */
		if (mysqli_connect_errno())
		{
			return FALSE;
		}

		/** Open the default SQL file */
		$query = file_get_contents('assets/install.sql');

		/** Execute a multi query */
		$mysqli->multi_query($query);

		/** Close the connection */
		$mysqli->close();

		return TRUE;
	}

	/** Create admin user account */
	function create_admin($data, $email, $password)
	{
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);

		/** Check for errors */
		if (mysqli_connect_errno())
		{
			return FALSE;
		}

		/** Hash password */
		$hash = substr(sha1($password), 0, 32);

		$mysqli->query("UPDATE `users` SET `username` = '$email', `email` = '$email', `password` = '$hash' WHERE `id` = '1'");

		/** Close the connection */
		$mysqli->close();

		return TRUE;
	}

}