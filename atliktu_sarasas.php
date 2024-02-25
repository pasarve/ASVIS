<?php # Script 9.5 - #5

$page_title = 'Atliktų darbų sąrašas';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

echo '<h1>Užsakymo metu atlikti darbai</h1>';

require_once ('mysqli_connect.php');

if  (isset($_GET['id']) ) { // From view_users.php
	$id = $_GET['id'];
}else{
        echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();   
}

$q = "SELECT darbas, price FROM ataskaita2 WHERE ataskaitos_id = '$id'";		
$r = @mysqli_query ($dbc, $q); // Run the query.
$viso = 0;
echo '<ul>';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    echo '<li><b>Darbo pavadinimas:&nbsp&nbsp&nbsp&nbsp</b> ' . $row['darbas'] . '&nbsp&nbsp&nbsp&nbsp <b> Kaina: </b> ' . $row['price'] . ' (Lt)</li></br>';
    $viso =($viso + $row['price']);
}
echo '</ul>';
echo '<p><h2>Kaina iš viso: ' . $viso . ' (Lt)</h2></p>';

include ('includes/footer.html');
?>
