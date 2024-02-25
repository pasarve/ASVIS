<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Redaguokite automobilio informaciją';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}



echo '<h1>Redaguoti automobilio informaciją</h1>';

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

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array();
	
	if (empty($_POST['vin'])) {
		$errors[] = 'Pamirsote ivesti automobilio kebulo numeri.';
	} else {
		$kn = mysqli_real_escape_string($dbc, trim($_POST['vin']));
	}
	
	// Check for a last name:
	if (empty($_POST['marke'])) {
		$errors[] = 'Pamirsote ivesti automobilio marke.';
	} else {
		$ma = mysqli_real_escape_string($dbc, trim($_POST['marke']));
	}
	
	// Check for an email address:
	if (empty($_POST['modelis'])) {
		$errors[] = 'Pamirsote ivesti automobilio modeli.';
	} else {
		$mo = mysqli_real_escape_string($dbc, trim($_POST['modelis']));
	}
        
        if (empty($_POST['pag_metai'])) {
		$errors[] = 'Pamirsote ivesti automobilio pagaminimo metus.';
	} else {
		$pm = mysqli_real_escape_string($dbc, trim($_POST['pag_metai']));
	}
        
        if (empty($_POST['keb_tipas'])) {
		$errors[] = 'Pamirsote ivesti automobilio kebulo tipa.';
	} else {
		$kt = mysqli_real_escape_string($dbc, trim($_POST['keb_tipas']));
	}
        
        if (empty($_POST['spalva'])) {
		$errors[] = 'Pamirsote ivesti automobilio spalva.';
	} else {
		$sp = mysqli_real_escape_string($dbc, trim($_POST['spalva']));
	}
        
        if (empty($errors)) {            
                $q = "SELECT auto_id FROM auto_kortele WHERE auto_id = $id";
		$r = @mysqli_query($dbc, $q);                
		if (mysqli_num_rows($r) == 1) {
                    
                
                        $q = "UPDATE auto_kortele SET vin='$kn', marke='$ma', modelis='$mo', pag_metai='$pm', keb_tipas='$kt', spalva='$sp' WHERE auto_id=$id LIMIT 1";
			$r = @mysqli_query ($dbc, $q);}
                        
                        if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Print a message:
				echo '<p>Automobilio informacija buvo sėkmingai redaguota.</p>';	
							
			} else { // If it did not run OK.
				echo '<p class="error">Nepavyko redaguoti automobilio informacijos, dėl sisteminės klaidos.</p>'; // Public message.
				//echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
			}
                        
            
        } else { // Report the errors.
	
		echo '<p class="error">Klaida!!<br />';
		foreach ($errors as $msg) { // Print each error.
			echo "$msg<br />\n";
		}
		echo '</p><p>Bandykite dar karta.</p>';
		
	
}
}


$q = "SELECT vin, marke, modelis, pag_metai, keb_tipas, spalva FROM auto_kortele WHERE auto_id=$id";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:
	echo '<form action="edit_mano_automobiliai.php" method="post">
<p>Kėbulo nr: <input id="inp4" type="text" name="vin" size="15" maxlength="15" value="' . $row[0] . '" /></p>
<p>Markė: <input id="inp3" type="text" name="marke" size="15" maxlength="30" value="' . $row[1] . '" /></p>
<p>Modelis: <input id="inp3" type="text" name="modelis" size="20" maxlength="40" value="' . $row[2] . '"  /> </p>
<p>Pagaminimo metai: <input id="inp2" type="text" name="pag_metai" size="15" maxlength="15" value="' . $row[3] . '" /></p>
<p>Kėbulo tipas:
        <select name="keb_tipas">
        <option value="Kita">Kita</option>
        <option value="Komercinis">Komercinis</option>
        <option value="Kabrioletas">Kabrioletas</option>
        <option value="Kupė">Kupė</option>
        <option value="Visureigis">Visureigis</option>
        <option value="Hečbekas">Hečbekas</option>
        <option value="Limuzinas">Limuzinas</option>
        <option value="Vienatūris">Vienatūris</option>
        <option value="Pikapas">Pikapas</option>
        <option value="Sedanas">Sedanas</option>
        <option value="Universalas">Universalas</option>
        </select></p>
<p>Spalva: 
        <select name="spalva">
        <option value="Kita">Kita</option>
        <option value="Smėlio">Smėlio</option>
        <option value="Juoda">Juoda</option>
        <option value="Mėlyna">Mėlyna</option>
        <option value="Bronzinė">Bronzinė</option>
        <option value="Ruda">Ruda</option>
        <option value="Vyšninė">Vyšninė</option>
        <option value="Auksinė">Auksinė</option>
        <option value="Pilka">Pilka</option>
        <option value="Žalia">Žalia</option>
        <option value="Žydra">Žydra</option>
        <option value="Šviesiai žalia">Šviesiai žalia</option>
        <option value="Šviesiai pilka">Šviesiai pilka</option>
        <option value="Oranžinė">Oranžinė</option>
        <option value="Violetinė">Violetinė</option>
        <option value="Raudona">Raudona</option>
        <option value="Sidabrinė">Sidabrinė</option>
        <option value="Balta">Balta</option>
        <option value="Geltona">Geltona</option>
        </select></p>
<p><input type="submit" name="submit" value="Išsaugoti pakeitimus" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />';


} else { // Not a valid user ID.
	echo '<p class="error">Įvyko klaida, čia neturėjote patekti.</p>';
}

mysqli_close($dbc);
		
include ('includes/footer.html');
?>