<?php # Script 16.10 - forgot_password.php
// This page allows a user to reset their password, if forgotten.

require_once ('includes/config.inc.php'); 
$page_title = 'Pamiršote slaptažodį?';
include ('includes/header.html');

if (isset($_POST['submitted'])) {
	require_once (MYSQL);

	// Assume nothing:
	$uid = FALSE;

	// Validate the email address...
	if (!empty($_POST['email'])) {
	
		// Check for the existence of that email address...
		$q = 'SELECT user_id FROM users WHERE email="'.  mysqli_real_escape_string ($dbc, $_POST['email']) . '"';
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 1) { // Retrieve the user ID:
			list($uid) = mysqli_fetch_array ($r, MYSQLI_NUM); 
		} else { // No database match made.
			echo '<p class="error">Toks elektroninio pašto adresas neegzistuoja mūsų sistemoje!</p>';
		}
		
	} else { // No email!
		echo '<p class="error">Pamiršote įvesti email adresą!</p>';
	} // End of empty($_POST['email']) IF.
	
	if ($uid) { // If everything's OK.

		// Create a new, random password:
		$p = substr ( md5(uniqid(rand(), true)), 3, 10);

		// Update the database:
		$q = "UPDATE users SET pass=SHA1('$p') WHERE user_id=$uid LIMIT 1";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
			// Send an email:
			$body = "Jūsų slaptažodis prisijungti prie: http://arvydelis.eu/ buvo laikinai pakeistas į: '$p'. Prisijungkite naudodami šį slaptažodį ir savo email adresą. Tada galėsite pasikeisti savo slaptažodį į kokį norite.";
			mail ($_POST['email'], 'Jūsų laikinas slaptažodis.', $body, 'From: arvydas.berteska@gmail.com');
			
			// Print a message and wrap up:
			echo '<h3>Jūsų slaptažodis buvo pakeistas. Naujas slaptažodis bus atsiūstas į adresą kurį nurodėte registruodamiesi. </h3>';
			mysqli_close($dbc);
			include ('includes/footer.html');
			exit(); // Stop the script.
			
		} else { // If it did not run OK.
			echo '<p class="error">Slaptažodžio nepavyko pakeisti dėl sisteminės klaidos.</p>'; 
		}

	} else { // Failed the validation test.
		echo '<p class="error">Bandykite dar kartą.</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.

?>

<h1>Pakeisti slaptazodi</h1>
<p>Iveskite savo email adresa zemiau ir jusu slaptazodis bus pakeistas.</p> 
<form action="forgot_password.php" method="post">
	<fieldset>
	<p><b>Email Adresas:</b> <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></p>
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="Pakeisti slaptažodį" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('includes/footer.html');
?>


