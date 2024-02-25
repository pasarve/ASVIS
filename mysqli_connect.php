<?php # Script 16.4 - mysqli_connect.php

// This file contains the database access information. 
// This file also establishes a connection to MySQL 
// and selects the database.

// Set the database access information as constants:
DEFINE ('DB_USER', 'arvydeli_admin');
DEFINE ('DB_PASSWORD', 'acme5896');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'arvydeli_servisas');

// Make the connection:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$dbc->set_charset("utf8");
if (!$dbc) {
	trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
}

?>
