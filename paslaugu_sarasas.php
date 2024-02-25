<?php # Script 9.5 - #5

// This script retrieves all the records from the users table.
// This new version allows the results to be sorted in different ways.

$page_title = 'Paslaugų kategorijos';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

echo '<h1>Teikiamų Paslaugų Kategorijos</h1>';

require_once ('mysqli_connect.php');


$q = "SELECT category_id, category_name FROM repair_category ORDER BY category_id";		
$r = @mysqli_query ($dbc, $q); // Run the query.


echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
        <td align="left"><b>Kategorija</b></td>
	<td align="left"><b>Redaguoti</b></td>
        <td align="left"><b>Ištrinti</b></td>
</tr>';




while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
    $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
    <td align="left">' . $row['category_name'] . '</td>
    <td align="left"><a href="edit_paslaugu_kategorijos.php?id=' . $row['category_id'] . '">Redaguoti</a></td>
    <td align="left"><a href="delete_paslaugu_kategorijos.php?id=' . $row['category_id'] . '">Ištrinti</a></td>
    </br>
    </tr>
';}

echo '</table>';
echo '<a href="nauja_paslauga.php">Pridėti naują kategoriją</a>';
mysqli_close($dbc);
include ('includes/footer.html');
?>
