<?php # Script 9.5 - #5

$page_title = 'Naujas Automobilis';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.	
}
if (isset($_POST['submitted'])) { // Handle the form.
$keb_tipas = $_POST['keb_tipas'];
$sp = $_POST['spalva'];

        require_once (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$ma = $mo = $vi = $pm = FALSE;
	
	// Patikrina marke
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['marke'])) {
		$ma = mysqli_real_escape_string ($dbc, $trimmed['marke']);
	} else {
		echo '<p class="error">Įveskite automobilio markę!</p>';
	}
	
	// Patikrina modeli:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['modelis'])) {
		$mo = mysqli_real_escape_string ($dbc, $trimmed['modelis']);
	} else {
		echo '<p class="error">Įveskite automobilio modelį!</p>';
	}
        
        // Patikrina VIN numeri:
	if (preg_match ('/^[A-Za-z0-9 \'.-]{17,17}$/i', $trimmed['VIN'])) {
		$vi = mysqli_real_escape_string ($dbc, $trimmed['VIN']);
	} else {
		echo '<p class="error">Įveskite automobilio kėbulo numerį!</p>';
	}
        
         // Patikrina pagaminimo metus:
	if (preg_match ('/\b\d\d(?:\d\d)?\b/', $trimmed['pag_metai'])) {
		$pm = mysqli_real_escape_string ($dbc, $trimmed['pag_metai']);
	} else {
		echo '<p class="error">Įveskite automobilio pagaminimo metus!</p>';
	}
        
         // Patikrina kebulo tipa:
	/*if (preg_match ('/^[A-Z a-z \'.-]{2,20}$/i', $trimmed['keb_tipas'])) {
		$kt = mysqli_real_escape_string ($dbc, $trimmed['keb_tipas']);
	} else {
		echo '<p class="error">Įveskite automobilio kėbulo tipą!</p>';
	}*/
        
         // Patikrina spalva:
	/*if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['spalva'])) {
		$sp = mysqli_real_escape_string ($dbc, $trimmed['spalva']);
	} else {
		echo '<p class="error">Įveskite automobilio spalvą!</p>';
	}*/
        
        if ($ma && $mo && $vi && $pm) {
        $user_id = $_SESSION['user_id'];        
            
            $q = "INSERT INTO auto_kortele (vin, marke, modelis, pag_metai, keb_tipas, spalva, user_id) VALUES ('$vi', '$ma', '$mo', '$pm', '$keb_tipas', '$sp', '$user_id' )";
            $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
            
            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Finish the page:
				echo '<h3>Jūsų automobilis sėkmingai išsaugotas.</h3>';
				include ('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">Automobilio nepavyko išsaugoti dėl sisteminės klaidos.</p>';
			}
            
        }
        }

?>


<h1>Naujas Automobilis</h1>
<form action="naujas_automobilis.php" method="post">
	<fieldset>
	
	<p><b>Markė:</b> <input type="text" name="marke" size="20" maxlength="20" value="<?php if (isset($trimmed['marke'])) echo $trimmed['marke']; ?>" /></p>
	
	<p><b>Modelis:</b> <input type="text" name="modelis" size="20" maxlength="40" value="<?php if (isset($trimmed['modelis'])) echo $trimmed['modelis']; ?>" /></p>
	
	<p><b>VIN:</b> <input id="inp4" type="text" name="VIN" size="30" maxlength="80" value="<?php if (isset($trimmed['VIN'])) echo $trimmed['VIN']; ?>" /><small>(17 simbolių.)</small></p>
        
        <p><b>Pagaminimo metai:</b> <input id="inp2" type="text" name="pag_metai" size="30" maxlength="80" value="<?php if (isset($trimmed['pag_metai'])) echo $trimmed['pag_metai']; ?>"/></p>
                      
        <p><b>Kėbulo tipas: </b>
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
        
        <p><b>Spalva: </b>
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
        
    
	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Registruoti" /></div>
	<input type="hidden" name="submitted" value="TRUE" />

</form>


<?php // Include the HTML footer.
include ('includes/footer.html'); ?>