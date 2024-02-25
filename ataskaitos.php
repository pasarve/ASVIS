<?php # Script 9.5 - #5

$page_title = 'Atlikti darbai';
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

echo '<h1>Atlikti darbai</h1>';

require_once ('mysqli_connect.php');

// Make the query:
$q = "SELECT atask_id, first_name, last_name, vin, marke, modelis, atlikimo_data FROM ataskaitos_duomenys ORDER BY atlikimo_data";		
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b>Pilna informacija</b></td>
        <td align="left"><b>Užsakovo vardas</b></td>
	<td align="left"><b>Pavardė</b></td>
	<td align="left"><b>Automobilio VIN</b></td>
	<td align="left"><b>Markė</b></td>
        <td align="left"><b>Modelis</b></td>
	<td align="left"><b>Atlikimo data</b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="ataskaitos_info.php?id=' . $row['atask_id'] . '">Informacija</a></td>
		<td align="left">' . $row['first_name'] . '</td>
                <td align="left">' . $row['last_name'] . '</td>
		<td align="left">' . $row['vin'] . '</td>
		<td align="left">' . $row['marke'] . '</td>
                <td align="left">' . $row['modelis'] . '</td>    
                <td align="left">' . $row['atlikimo_data'] . '</td>
	</tr>
	';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
mysqli_close($dbc);



	
include ('includes/footer.html');
?>
