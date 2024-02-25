<?php # Script 9.5 - #5

$page_title = 'Užsakymai';
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

echo '<h1>Užsakymai</h1>';

require_once ('mysqli_connect.php');

$display = 10;

/*if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(user_id) FROM auto_kortele";
	$r = @mysqli_query ($dbc, $q);
	$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}
*/


$q = "SELECT reg_data, user_id, auto_id, info_id FROM registruoti_auto";		
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b>Daugiau Informacijos</b></td>
        <td align="left"><b>Užsakovo vardas</b></td>
        <td align="left"><b>Pavardė</b></td>
        <td align="left"><b>Automobilio markė</b></td>
        <td align="left"><b>Modelis</b></td>
        <td align="left"><b>Pag. Metai</b></td>
	<td align="left"><b>Užsakymo data</b></td>
</tr>
';

$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    $u_id = $row['user_id'];
    $a_id = $row['auto_id'];
    $que = "SELECT first_name, last_name FROM users WHERE user_id = $u_id LIMIT 1";
    $run = @mysqli_query ($dbc, $que);
    $row2 = mysqli_fetch_array ($run, MYSQLI_NUM);
    
    $que2 = "SELECT marke, modelis, pag_metai FROM auto_kortele WHERE auto_id = $a_id LIMIT 1";
    $run2 = @mysqli_query ($dbc, $que2);
    $row3 = mysqli_fetch_array ($run2, MYSQLI_NUM);
    
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="uzsakymo_info.php?id=' . $row['info_id'] . '">Informacija</a></td>
   		<td align="left">' . $row2[0] . '</td>
                <td align="left">' . $row2[1] . '</td>
                <td align="left">' . $row3[0] . '</td>
                <td align="left">' . $row3[1] . '</td>
                <td align="left">' . $row3[2] . '</td>
                <td align="left">' . $row['reg_data'] . '</td>
	</tr>
	';
} // End of WHILE loop.

echo '</table>';



include ('includes/footer.html');
?>
