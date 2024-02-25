<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Redaguoti vartotoją';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}



echo '<h1>Redaguoti vartotoją</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">Čia neturėjote patekti.</p>';
	include ('includes/footer.html'); 
	exit();
}

require_once ('mysqli_connect.php'); 

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array();
	
	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'Neįvedėte vardo.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'Neįvedėte pavardės.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'Neįvedėte elektroninio pašto adreso.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	
	$user_level = $_POST['user_level'];
	
	
	
	if (empty($errors)) { // If everything's OK.
	
		//  Test for unique email address:
		$q = "SELECT user_id FROM users WHERE email='$e' AND user_id != $id";
		$r = @mysqli_query($dbc, $q);
		if (mysqli_num_rows($r) == 0) {

			// Make the query:
			$q = "UPDATE users SET first_name='$fn', last_name='$ln', email='$e', user_level='$user_level' WHERE user_id=$id LIMIT 1";
			$r = @mysqli_query ($dbc, $q);
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Print a message:
				echo '<p>Vartotojo duomenys buvo sėkmingai redaguoti.</p>';	
							
			} else { // If it did not run OK.
				echo '<p class="error">Vartotojo nepavyko redaguoti nes įvyko klaida!.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
			}
				
		} else { // Already registered.
			echo '<p class="error">Šitas elektroninio pašto adresas jau yra užregistruotas!.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<p class="error">Įvyko šios klaidos:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Bandykite dar kartą.</p>';
		
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT first_name, last_name, email, user_level FROM users WHERE user_id=$id";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:
	echo '<form action="edit_user.php" method="post">
<p>Vardas: <input type="text" name="first_name" size="15" maxlength="15" value="' . $row[0] . '" /></p>
<p>Pavardė: <input type="text" name="last_name" size="15" maxlength="30" value="' . $row[1] . '" /></p>
<p>Email adresas: <input type="text" name="email" size="20" maxlength="40" value="' . $row[2] . '"  /> </p>

<label><input type="radio" name="user_level" value="1" id="admin_0"'; 

 if($row['3'] == 1) {
 echo 'checked'; 
 }
 
echo '/>Vadovas</label>

<br />

<label><input type="radio" name="user_level" value="2" id="admin_2"';
if($row['3'] == 2) {
 echo 'checked'; 
 }
 
echo '/>Darbuotojas</label>

<br />



<label><input type="radio" name="user_level" value="0" id="admin_1"'; 

 if($row['3'] == 0) {
 echo 'checked'; 
 }

echo '/>Eilinis lankytojas</label>
<br /><br />
<p><input type="submit" name="submit" value="Išsaugoti" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
</form>';

} else { // Not a valid user ID.
	echo '<p class="error">Čia patekote per klaida.</p>';
}

mysqli_close($dbc);
		
include ('includes/footer.html');
?>
