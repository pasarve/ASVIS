<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Redaguoti paslaugą';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}



echo '<h1>Redaguoti Paslaugą</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();
}

require_once ('mysqli_connect.php');

if (isset($_POST['submitted'])) {
$pavadinimas = $_POST['pavadinimas'];
$kaina = $_POST['kaina'];
$apibudinimas = $_POST['apibudinimas'];

    if (($pavadinimas == NULL) || ($kaina == "0.00")){
        echo '<p class="error">Laukeliai "Paslaugos pavadinimas" ir "Paslaugos kaina" turi būti užpildyti</p>';
    }else{
        $q = "UPDATE repair_sub_category SET sub_category_name='$pavadinimas', sub_category_description='$apibudinimas', price='$kaina' WHERE sub_category_id='$id' LIMIT 1";
        $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
    if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		echo '<p>Paslaugoss informacija buvo sėkmingai redaguota.</p>';	
							
		}else{
                      echo '<p class="error">Įsitikinkite, kad pakeitėte bent vieną lauką.</p>'; // Public message.
                      //echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
                      }
        
        
        
    }





}

$q = "SELECT sub_category_name, sub_category_description, price FROM repair_sub_category WHERE sub_category_id=$id";		
$r = @mysqli_query ($dbc, $q);

 if (mysqli_num_rows($r) == 0) {
     echo '<p class="error">Paslauga buvo nerasta!</p>';
 }else{
     $row = mysqli_fetch_array ($r, MYSQLI_NUM);
     echo '<form action="edit_paslaugas.php" method="post">
    <p>Paslaugos pavadinimas: <input type="text" name="pavadinimas" size="30" maxlength="30" value="' . $row[0] . '" /></p>
    <p>Paslaugos kaina: <input type="text" name="kaina" size="30" maxlength="150" value="' . $row[2] . '" input id="inp1" />(Lt)</p>
    <p>Paslaugos apibūdinimas: <input type="text" name="apibudinimas" size="30" maxlength="150" value="' . $row[1] . '" /></p>
    <p><input type="submit" name="submit" value="Išsaugoti pakeitimus" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
    <input type="hidden" name="id" value="' . $id . '" />';
 }
 
 
mysqli_close($dbc);
		
include ('includes/footer.html');

?>
