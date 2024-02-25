<?php # Script 16.5 - index.php
// This is the main page for the site.

// Include the configuration file:
require_once ('includes/config.inc.php'); 

// Set the page title and include the HTML header:
$page_title = 'Sveiki atvykę!';
include ('includes/header.html');

// Welcome the user (by name if they are logged in):
echo '<h1>Sveiki Atvykę!';
if (isset($_SESSION['first_name'])) {
	echo " Jūs prisijungėte kaip: {$_SESSION['first_name']}.";
}
echo '</h1>';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="lt" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<p>Pradinis puslapis!
</p>

<?php // Include the HTML footer file:
include ('includes/footer.html');
?>
</body>
</html>