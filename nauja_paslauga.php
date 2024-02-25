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

echo '<h1>Įvesti Naują Paslaugos Kategoriją</h1>';


require_once ('mysqli_connect.php');

if (isset($_POST['submitted'])) { // Handle the form.
$kategorija = $_POST['kategorija'];
$apibudinimas = $_POST['apibudinimas'];
	require_once (MYSQL);
        if ($kategorija == NULL){
            echo '<p class="error">Kategorijos pavadinimą įvesti yra būtina!</p>';           
        }else{           
            $q = "INSERT INTO repair_category (category_name, category_description) VALUES ('$kategorija', '$apibudinimas')";
            $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
            
           if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Finish the page:
				echo '<h3>Nauja kategorija sėkmingai išsaugota.</h3>
                                   <a href="paslaugu_sarasas.php">Grįžti į kategorijų sąrašą</a>';
				include ('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">Kategorijos nepavyko išsaugoti dėl sisteminės klaidos.</p>';
			}
            
        
        }}
?>
<form action="nauja_paslauga.php" method="post">
	<fieldset>
	
	<p><b>Kategorijos pavadinimas:</b> <input type="text" name="kategorija" size="20" maxlength="30" /></p>
	
        
        <p><b>Apibūdinimas:</b> <input type="text" name="apibudinimas" size="30" maxlength="150" /></p>
    
	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Išsaugoti" /></div>
	<input type="hidden" name="submitted" value="TRUE" />

</form>

<?php // Include the HTML footer.
include ('includes/footer.html');
?>