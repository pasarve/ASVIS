<?php # Script 9.5 - #5

$page_title = 'Mano automobiliai';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

echo '<h1>Mano automobiliai</h1>';

require_once ('mysqli_connect.php');

$display = 10;

if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
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

$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'knr';

// Determine the sorting order:
switch ($sort) {
	case 'mar':
		$order_by = 'marke ASC';
		break;
	case 'mod':
		$order_by = 'modelis ASC';
		break;
	case 'knr':
		$order_by = 'vin ASC';
		break;
	default:
		$order_by = 'vin ASC';
		$sort = 'knr';
		break;
}
$user_id = $_SESSION['user_id'];

// Make the query:
$q = "SELECT marke, modelis, vin, auto_id FROM auto_kortele WHERE user_id = $user_id ORDER BY $order_by LIMIT $start, $display";		
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
	<td align="left"><b>Redaguoti</b></td>
	<td align="left"><b>Trinti</b></td>
	<td align="left"><b><a href="mano_automobiliai.php?sort=mar">Markė</a></b></td>
	<td align="left"><b><a href="mano_automobiliai.php?sort=mod">Modelis</a></b></td>
	<td align="left"><b><a href="mano_automobiliai.php?sort=knr">Kėbulo Nr.</a></b></td>
        <td align="left"><b>Registruoti Apžiūrai</b></td>
        <td align="left"><b>Atlikti Darbai</b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_mano_automobiliai.php?id=' . $row['auto_id'] . '">Redaguoti</a></td>
		<td align="left"><a href="delete_mano_automobiliai.php?id=' . $row['auto_id'] . '">Trinti</a></td>
		<td align="left">' . $row['marke'] . '</td>
		<td align="left">' . $row['modelis'] . '</td>
		<td align="left">' . $row['vin'] . '</td>
                <td align="left"><a href="registruoti_apziurai.php?id=' . $row['auto_id'] . '">Registruoti Apžiurai</a></td>
                <td align="left"><a href="mano_automobiliai_darbai.php?id=' . $row['auto_id'] . '">Atlikti Darbai</a></td>
	</tr>
	';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
mysqli_close($dbc);

// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	echo '<br /><p>';
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="mano_automobiliai.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="mano_automobiliai.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="mano_automobiliai.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</p>'; // Close the paragraph.
	
} // End of links section.

echo '<a href="naujas_automobilis.php" title="Ivesti nauja automobili">Įvesti naują automobilį</a><br />';


	
include ('includes/footer.html');
?>
