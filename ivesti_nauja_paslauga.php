<?php # Script 9.5 - #5

// This script retrieves all the records from the users table.
// This new version allows the results to be sorted in different ways.

$page_title = 'Nauja paslauga';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

echo '<h1>Įvesti Naują Paslaugą</h1>';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$parent_id = $_GET['id'];
}elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$parent_id = $_POST['id'];
}else{
	echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();    
}


require_once ('mysqli_connect.php');

if (isset($_POST['submitted'])) { // Handle the form.
$paslauga = $_POST['paslauga'];
$kaina = $_POST['kaina'];
$apibudinimas = $_POST['apibudinimas'];
	require_once (MYSQL);
if (($paslauga == NULL)|| ($kaina == "0.00") || ($kaina == NULL)){
            echo '<p class="error">Laukai "Paslaugos pavadinimas" ir "Paslaugos kaina" yra būtini!</p>';
        }else{
         $q = "INSERT INTO repair_sub_category (sub_category_name, price, sub_category_description, parent_id) VALUES ('$paslauga', '$kaina', '$apibudinimas', '$parent_id')";
         $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));   
         if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Finish the page:
				echo '<h3>Nauja paslauga sėkmingai išsaugota.</h3>
                                <a href="paslaugu_sarasas.php">Grįžti į kategorijų sąrašą</a>';
				include ('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">Paslaugos nepavyko išsaugoti dėl sisteminės klaidos.</p>';
			}   
        }
}
echo '<form action="ivesti_nauja_paslauga.php?id=' . $parent_id . '" method="post">
<p>Paslaugos pavadinimas: <input type="text" name="paslauga" size="30" maxlength="30" /></p>
<p>Paslaugos kaina: <input type="text" name="kaina" size="30" maxlength="30" input id="inp1" />(Lt)</p>
<p>Paslaugos apibūdinimas: <input type="text" name="apibudinimas" size="30" maxlength="150" /></p>
<p><input type="submit" name="submit" value="Išsaugoti" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $parent_id . '" />';  


 // Include the HTML footer.
include ('includes/footer.html');
?>