<?php
# Script 16.6 - register.php
// This is the registration page for the site.

require_once ('includes/config.inc.php');
$page_title = 'Registracija';
include ('includes/header.html');

if (isset($_POST['submitted'])) { // Handle the form.

	require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$fn = $ln = $e = $p = FALSE;
	
	// Check for a first name:
	if (preg_match ('/^[A-ZĄąČčĘęĖėĮįŠšŲųŪūŽž \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string ($dbc, $trimmed['first_name']);
	} else {
		echo '<p class="error">Įveskite savo vardą!</p>';
	}
	
	// Check for a last name:
	if (preg_match ('/^[A-ZąĄčČęĘėĖįĮšŠųŲūŪžŽ \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	} else {
		echo '<p class="error">Įveskite savo pavardę!</p>';
	}
	
	// Check for an email address:
	if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $trimmed['email'])) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	} else {
		echo '<p class="error">Įveskite savo elektroninio pašto adresą!</p>';
	}
	

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		} else {
			echo '<p class="error">Jūsų slaptažodžiai nesutampa!</p>';
		}
	} else {
		echo '<p class="error">Iveskite tinkamą slaptažodį!</p>';
	}
	
	if ($fn && $ln && $e && $p) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT user_id FROM users WHERE email='$e'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available.
		
			// Create the activation code:
			$a = md5(uniqid(rand(), true));
		
			// Add the user to the database:
			$q = "INSERT INTO users (email, pass, first_name, last_name, active, registration_date) VALUES ('$e', SHA1('$p'), '$fn', '$ln', '$a', NOW() )";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Send the email:
				$body = "Ačiū, kad užsiregistravote <http://arvydelis.eut>. Norint aktyvuoti savo prisijungimo duomenis spauskite sekančią nuorodą:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
				mail($trimmed['email'], 'Registracijos patvirtinimas', $body, 'From: pasarve@gmail.com.com');
				
				// Finish the page:
				echo '<h3>Ačiū, kad užsiregistravote. Norint akytuoti savo prisijungimo duomenis, paspauskite ant nuorodos kurią gavote į registracijoje nurodytą email adresą!</h3>';
				include ('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">Nepavyko jūsų priregistruoti dėl sisteminės klaidos. Atsiprašome už nesklandumus.</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">Šis elektroninio pašto adresas jau yra užregistruotas. Jeigu pamiršote savo slaptažodį, pasinaudokite nuoroda esančia dešinėje pusėje gauti naujam slaptažodžiuj.</p>';
		}
		
	} else { // If one of the data tests failed.
		echo '<p class="error">Įveskite slaptažodžius iš naujo ir bandykite dar kartą.</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.
?>
	
<h1 align="center">Registruotis</h1>


<form action="register.php" method="post">
	<fieldset style="text-align:left;">
	
	<p><b>Vardas:</b> <input type="text" name="first_name" size="20" maxlength="20" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" /></p>
	
	<p><b>Pavarde:</b> <input type="text" name="last_name" size="20" maxlength="40" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" /></p>
	
	<p><b>Email Adresas:</b> 
    <input type="text" name="email" size="30" maxlength="80" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /></p>
    
		
    <p><b>Slaptažodis:</b> 
      <input type="password" name="password1" size="20" maxlength="20" /> 
	 <small>naudokite tik raides, skaičius ir apatinį bruksneli. Turi būti nuo 4 iki 20 simbolių ilgio.</small></p>
	<p><b>Pakartoti slaptažodį:</b> <input type="password" name="password2" size="20" maxlength="20" /></p>
	</fieldset>
	
	<div align="left"><input type="submit" name="submit" value="Registruotis" /></div>
	<input type="hidden" name="submitted" value="TRUE" />

</form>



<?php // Include the HTML footer.
include ('includes/footer.html'); ?>

