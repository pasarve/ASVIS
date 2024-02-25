<?php # Script 16.11 - change_password.php
// This page allows a logged-in user to change their password.

require_once ('includes/config.inc.php'); 
$page_title = 'Pakeisti Slaptazodi';
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

if (isset($_POST['submitted'])) {
	require_once (MYSQL);
			
	// Check for a new password and match against the confirmed password:
	$p = FALSE;
	if (preg_match ('/^(\w){4,20}$/', $_POST['password1']) ) {
		if ($_POST['password1'] == $_POST['password2']) {
			$p = mysqli_real_escape_string ($dbc, $_POST['password1']);
		} else {
			echo '<p class="error">Jusu ivesti slaptazodziai nesutampa!</p>';
		}
	} else {
		echo '<p class="error">Iveskite tinkama slaptazodi!</p>';
	}
	
	if ($p) { // If everything's OK.

		// Make the query.
		$q = "UPDATE users SET pass=SHA1('$p') WHERE user_id={$_SESSION['user_id']} LIMIT 1";	
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
			// Send an email, if desired.
			echo '<h3>Jusu slaptazodis buvo sekmingai pakeistas.</h3>';
			mysqli_close($dbc); // Close the database connection.
			include ('includes/footer.html'); // Include the HTML footer.
			exit();
			
		} else { // If it did not run OK.
		
			echo '<p class="error">Jusu slaptazodis nebuvo pakeistas. Isitikinkite, kad jis nesutampa su jusu senu slaptazodziu!</p>'; 

		}

	} else { // Failed the validation test.
		echo '<p class="error">Bandykite dar karta.</p>';		
	}
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.

?>

<h1>Pasikeiskite Slaptazodi!</h1>
<form action="change_password.php" method="post">
	<fieldset>
	<p><b>Naujas Slaptazodis:</b> <input type="password" name="password1" size="20" maxlength="20" /> <small>Naudokite tik raides, skaicius ir apatini bruksneli. Slatpazodis turi buti tarp 4 ir 20 simboliu ilgio.</small></p>
	<p><b>Patvirtinti Nauja Slaptazodi:</b> <input type="password" name="password2" size="20" maxlength="20" /></p>
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="Pakeisti slaptazodi" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('includes/footer.html');
?>
