<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Redaguokite paslaugų informaciją';
require_once ('includes/config.inc.php'); 
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}



echo '<h1>Redaguoti Kategoriją</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">Čia patekote per klaidą.</p>';
	include ('includes/footer.html'); 
	exit();
}

require_once ('mysqli_connect.php');

if (isset($_POST['submitted'])) {
$kategorija = $_POST['kategorija'];
$apibudinimas = $_POST['apibudinimas'];
	require_once (MYSQL);

        if ($kategorija == NULL){
            echo'<p class="error">Neįvestas kategorijos pavadinimas!</p>';
        }else{
           $q = "UPDATE repair_category SET category_name='$kategorija', category_description='$apibudinimas' WHERE category_id='$id' LIMIT 1";
           $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
        if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		echo '<p>Kategorijos informacija buvo sėkmingai redaguota.</p>';	
							
		}else{
                      echo '<p class="error">Įsitikinkite, kad pakeitėte bent vieną lauką.</p>'; // Public message.
                      //echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
                      }
                                
        }


}



$q = "SELECT category_name, category_description FROM repair_category WHERE category_id=$id";		
$r = @mysqli_query ($dbc, $q);
$que = "SELECT sub_category_id, parent_id, sub_category_name, price FROM repair_sub_category WHERE parent_id=$id";
$run = @mysqli_query ($dbc, $que);


if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	// Create the form:
	echo '<form action="edit_paslaugu_kategorijos.php" method="post">
<p>Kategorijos pavadinimas: <input type="text" name="kategorija" size="30" maxlength="30" value="' . $row[0] . '" /></p>
<p>Kategorijos apibūdinimas: <input type="text" name="apibudinimas" size="30" maxlength="150" value="' . $row[1] . '" /></p>
<p><input type="submit" name="submit" value="Išsaugoti pakeitimus" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />';  
        
        
        
        if (mysqli_num_rows($run) == 0) { //jei neranda įrašų lentelėje
        echo '<h3>Šioje kategorijoje šiuo metu nėra nei vienos paslaugos</h3>   
        <a href="ivesti_nauja_paslauga.php?id=' . $id . '">Įvesti naują paslaugą?</a>   
';
        }else{ 
              echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
        <td align="left"><b>Paslauga</b></td>
        <td align="left"><b>Kaina</b></td>
	<td align="left"><b>Redaguoti</b></td>
        <td align="left"><b>Ištrinti</b></td>
</tr>';  
               echo '<h1>Kategorijoje teikiamos paslaugos:</h1>';              
while ($row2 = mysqli_fetch_array($run, MYSQLI_ASSOC)){
    $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
    <td align="left">' . $row2['sub_category_name'] . '</td>
    <td align="left">' . $row2['price'] . '</td>    
    <td align="left"><a href="edit_paslaugas.php?id=' . $row2['sub_category_id'] . '">Redaguoti</a></td>
    <td align="left"><a href="delete_paslaugas.php?id=' . $row2['sub_category_id'] . '">Ištrinti</a></td>     
    </br>
    </tr>'
;    
}
echo '<a href="ivesti_nauja_paslauga.php?id=' . $id . '" title="Ivesti_nauja_paslauga">Įvesti naują paslaugą?</a><br />';
  
} 

} else { // Not a valid user ID.
    echo '<p class="error">Įvyko klaida, čia neturėjote patekti.</p>';
}
              
mysqli_close($dbc);
		
include ('includes/footer.html');

?>
