<?php # Script 9.5 - #5

$page_title = 'Automobiliui atlikti darbai';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

echo '<h1>Automobiliui atlikti darbai</h1>';

require_once ('mysqli_connect.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
}else{
        echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();   
}

$q = "SELECT marke, modelis, vin, pag_metai, keb_tipas, spalva FROM auto_kortele WHERE auto_id = $id LIMIT 1";		
$r = @mysqli_query ($dbc, $q); // Run the query.
$row = mysqli_fetch_array($r, MYSQLI_NUM);

echo '</br><h2>Pasirinktas automobilis:</h2>
            <p><b>Marke:  </b>' . $row[0] .'</p>
            <p><b>Modelis:  </b>' . $row[1] .'</p>
            <p><b>Kėbulo Nr.:  </b>' . $row[2] .'</p>
            <p><b>Pagaminimo metai:  </b>' . $row[3] .'</p>
            <p><b>Kėbulo tipas:  </b>' . $row[4] .'</p>     
            <p><b>Spalva:  </b>' . $row[5] .'</p>

';


echo '</br><h2>Pasirinktam automobiliui atlikti darbai:</h2>';
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b>Meistro Vardas</b></td>
	<td align="left"><b>Pavardė</b></td>
        <td align="left"><b>Atlikimo data</b></td>
        <td align="left"><b>Atlikti darbai</b></td>
        
</tr>
';
$bg = '#eeeeee';

$q2 = "SELECT atask_id, mvardas, mpavarde, atlikimo_data FROM ataskaitos_duomenys WHERE auto_id = '$id'";
$r2 = @mysqli_query($dbc, $q2);

while ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">		
		<td align="left">' . $row2['mvardas'] . '</td>
		<td align="left">' . $row2['mpavarde'] . '</td>
		<td align="left">' . $row2['atlikimo_data'] . '</td>
                <td align="left"><a href="atliktu_sarasas.php?id=' . $row2['atask_id'] . '">Atlikti darbai</a></td>
                </tr>
	';
} // End of WHILE loop.






include ('includes/footer.html'); 
?>