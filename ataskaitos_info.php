<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Pilna užsakymo informacija';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (isset($_SESSION['first_name']) && ($_SESSION["user_level"] == '0')) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
} else if (!isset($_SESSION['first_name'])){
        $url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.   
    
}

echo '<h1>Užsakymo informacija</h1>';

if  (isset($_GET['id']) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();
}

require_once ('mysqli_connect.php'); 

$q = "SELECT first_name, last_name, vin, marke, modelis, pag_metai, atlikimo_data, mvardas, mpavarde FROM ataskaitos_duomenys WHERE atask_id = '$id'";
$r = @mysqli_query($dbc, $q);


    if (mysqli_num_rows($r) == 1) {        
        $row = mysqli_fetch_array ($r, MYSQLI_NUM); 
        
      $q2 = "SELECT darbas, price FROM ataskaita2 WHERE ataskaitos_id = '$id'";
      $r2 = @mysqli_query($dbc, $q2);
      
        echo'
        
                      <h2> Automobilio savininko informacija:<p></p></h2>
                      <p><b>Vardas:  </b>' . $row[0] .'</p>
                      <p><b>Pavarde:  </b>' . $row[1] .'</p></br>
                      <p><h2>Automobilio informacija:</h2></p>
                      <p><b>Automobilio kebulo nr: </b>' . $row[2] .'</p>
                      <p><b>Automobilio marke: </b>' . $row[3] .'</p>
                      <p><b>Automobilio modelis: </b>' . $row[4] .'</p>
                      <p><b>Automobilio pagaminimo metai: </b>' . $row[5] .'</p></br>
                      <p><h2>Darbuotojas atlikęs darbus:</h2></p>
                      <p><b>Darbuotojo vardas: </b>' . $row[7] .'</p>
                      <p><b>Darbuotojo pavardė: </b>' . $row[8] .'</p>    
                      <p><b>Darbai atlikti: </b>' . $row[6] .'</p></br>
                      <h2>Atlikti darbai:<p></p></h2>';
        
        
        echo '<table border="1" cellpadding="5" cellspacing="5" width="50%">
                <tr>
                <th>Atliktas darbas</th>
                <th>Darbo kaina</th>
                </tr>
';
        while ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
            echo '<tr>
                  <td width="70%"> ' . $row2['darbas'] . '</td><td align ="right"> ' . $row2['price'] . ' (Lt)</td>
                  </tr>    
';
        $viso= ($viso + $row2['price']);  
        }
        echo '</table></br>';
        $pvm = round($viso * 0.21, 2);
        $bendra = ($pvm + $viso);
        echo '<table border="1" cellpadding="5" cellspacing="5" width="30%">
            <tr>
            <td width="50%">Iš viso:</td><td>' . $viso . ' (Lt)</td>
            </tr>
            <tr>
            <td width="50%">PVM (21%):</td><td>' . $pvm . ' (Lt)</td>
            </tr>
            <td width="50%"><p></p></td><td></td>
            <tr>
            <tr>
            <td width="50%">Bendra suma: </td><td>' . $bendra . ' (Lt)</td>
            </tr>
            </tr>
            </table>';
        
        echo' <input type="button" value="Atspausdinti!" onclick="window.print()"/>';
        
        

}
include ('includes/footer.html');
?>