<?php # Script 16.8 - login.php
// This is the login page for the site.

require_once ('includes/config.inc.php'); 
$page_title = 'Prisijungimas';
include ('includes/header.html');

if (isset($_POST['submitted'])) {
	require_once (MYSQL);
	
	// Validate the email address:
	if (!empty($_POST['email'])) {
		$e = mysqli_real_escape_string ($dbc, $_POST['email']);
	} else {
		$e = FALSE;
		echo '<p class="error">Pamiršote įvesti email adresą!</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['pass'])) {
		$p = mysqli_real_escape_string ($dbc, $_POST['pass']);
	} else {
		$p = FALSE;
		echo '<p class="error">Pamiršote įvesti slaptažodį!</p>';
	}
	
	if ($e && $p) { // If everything's OK.
	
		// Query the database:
		$q = "SELECT user_id, first_name, user_level FROM users WHERE (email='$e' AND pass=SHA1('$p')) AND active IS NULL";		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows($r) == 1) { // A match was made.

			// Register the values & redirect:
			$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
			mysqli_free_result($r);
			mysqli_close($dbc);
							
			$url = BASE_URL . '/index.php'; // Define the URL:
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
				
		} else { // No match was made.
			echo '<p class="error">Blogai įvedėte email adresą arba slaptažodį, arba dar neaktyvavote savo prisijungimo duomenu.</p>';
		}
		
	} else { // If everything wasn't OK.
		echo '<p class="error">Bandykite dar kartą.</p>';
	}
	
	mysqli_close($dbc);

} // End of SUBMIT conditional.
?>

<h1>Prisijungti</h1>
<p>Jusu naršyklė turi palaikyti sausainiukus.</p>
<form action="login.php" method="post">
	<fieldset>
	<p><b>Email Adresas:</b> <input type="text" name="email" size="20" maxlength="40" /></p>
	<p><b>Slaptažodis:</b> <input type="password" name="pass" size="20" maxlength="20" /></p>
	<div align="center"><input type="submit" name="submit" value="Prisijungti" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
	</fieldset>
</form>

<?php // Include the HTML footer.
include ('includes/footer.html');
?>
