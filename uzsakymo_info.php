<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Užsakymo informacija';
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

if ( (isset($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();
}

require_once ('mysqli_connect.php'); 

$q = "SELECT user_id, auto_id, reg_data, info_id FROM registruoti_auto WHERE info_id = '$id'";
$r = @mysqli_query($dbc, $q);
$row = mysqli_fetch_array ($r, MYSQLI_NUM);
$uid= $row[0];
$aid= $row[1];
$rdata= $row[2];
$info= $row[3];

    if (mysqli_num_rows($r) == 1) {
        $q = "SELECT first_name, last_name, email FROM users WHERE user_id= '$uid'";
        $r = @mysqli_query($dbc, $q);
        $row = mysqli_fetch_array ($r, MYSQLI_NUM); 
        
        $l = "SELECT vin, marke, modelis, pag_metai, keb_tipas, spalva FROM auto_kortele WHERE auto_id= '$aid'";
        $m = @mysqli_query($dbc, $l);
        $row2 = mysqli_fetch_array($m, MYSQLI_NUM);
        

        
        
        if ((mysqli_num_rows($r) == 1) && (mysqli_num_rows($m) == 1)) {       
         
        echo'
        
                      <h2> Užregistruoto automobilio, savininko informacija:<p></p></h2>
                      <p><b>Vardas:  </b>' . $row[0] .'</p>
                      <p><b>Pavarde:  </b>' . $row[1] .'</p>
                      <p><b>Email Adresas:  </b>' . $row[2] .'</p></br>
                      <input type="hidden" name="submitted" value="TRUE" />
                      <input type="hidden" name="id" value="' . $id . '" />
                      <p><h2>Užregistruoto automobilio informacija:</h2></p>
                      <p><b>Automobilio marke: </b>' . $row2[1] .'</p>
                      <p><b>Automobilio modelis: </b>' . $row2[2] .'</p>
                      <p><b>Automobilio kebulo nr: </b>' . $row2[0] .'</p>
                      <p><b>Automobilio pagaminimo metai: </b>' . $row2[3] .'</p>
                      <p><b>Automobilio kebulo tipas: </b>' . $row2[4] .'</p>
                      <p><b>Automobilio spalva: </b>' . $row2[5] .'</p></br>
                      <h2> Užsakyti darbai:</h2>';
                
                       $darbai = "SELECT sub_name, sub_price FROM reg_info WHERE random_id= '$id' ";
                       $row3 = @mysqli_query($dbc, $darbai);       
                      while ($row4 = mysqli_fetch_array($row3, MYSQLI_ASSOC)) {
                           echo '<p><b>Darbo pavadinimas: </b>' . $row4['sub_name'] . '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>        Kaina: </b>' . $row4['sub_price'] . '</p>';
                      }

                      echo '</br><a href="ispildyti.php?id=' . $id . '">Išpildyti užsakymą</a>';

        

    } else {
        echo '<p>Cia patekote per klaida!! Bandykite dar karta!</p';
       
        
    }     




                
}
include ('includes/footer.html');
?>