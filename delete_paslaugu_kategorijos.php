<?php # Script 9.2 - delete_user.php

// This page is for deleting a user record.
// This page is accessed through view_users.php.

$page_title = 'Ištrinti Kategoriją';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}


echo '<h1>Ištrinti Kategoriją</h1>';

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

	if ($_POST['sure'] == 'Yes') { // Delete the record.
            
                $q = "DELETE FROM repair_sub_category WHERE parent_id=$id";
                $r = @mysqli_query ($dbc, $q);
                if (mysqli_affected_rows($dbc) == 0){
                    echo '<p>Šioje kategorijoje nebuvo rasta nei vienos paslaugos. Ištrinama kategorija.</p>';
                }

		// Make the query:
		$q = "DELETE FROM repair_category WHERE category_id=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
			// Print a message:
			echo '<p>Kategorija ištrinta.</p>
                            <a href="paslaugu_sarasas.php">Grįžti į kategorijų sąrašą</a>';	
		
		} else { // If the query did not run OK.
			echo '<p class="error">Kategorijos nepavyko ištrinti dėl sisteminės klaidos.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>Kategorija nebuvo ištrinta.</p>';	
	}

} else { // Show the form.

	// Retrieve the user's information:
	$q = "SELECT CONCAT(category_name) FROM repair_category WHERE category_id=$id";
	$r = @mysqli_query ($dbc, $q);
	
	if (mysqli_num_rows($r) == 1) { 

		// Get the user's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Create the form:
		echo '<form action="delete_paslaugu_kategorijos.php" method="post">
	<h3>Kategorijos pavadinimas: ' . $row[0] . '</h3>
	<p>Ar tikrai norite ištrinti šią kategoriją? <font color="red"><b>Kartu bus ištrinamos šioje kategorijoje įvestos paslaugos!</b></font><br />
	<input type="radio" name="sure" value="Yes" /> Taip
	<input type="radio" name="sure" value="No" checked="checked" /> Ne</p>
	<p><input type="submit" name="submit" value="Istrinti" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	
	} else { // Not a valid user ID.
		echo '<p class="error">Čia patekote per klaidą.</p>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);
		
include ('includes/footer.html');
?>
