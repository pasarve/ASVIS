<?php
# Script 16.6 - register.php
// This is the registration page for the site.

require_once ('includes/config.inc.php');
$page_title = 'Siūlomos paslaugos';
include ('includes/header.html');

echo '<h2>Autoservise siūlomos paslaugos ir jų kainos</h2>';

require_once (MYSQL);

$q = "SELECT category_id, category_name FROM repair_category ORDER BY category_id";		
$r = @mysqli_query ($dbc, $q);

$que = "SELECT sub_category_id, parent_id, sub_category_name FROM repair_sub_category ORDER BY parent_id";
$run = @mysqli_query($dbc, $que);


while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    echo '<b>' . $row['category_name'] . '</b>';
    echo '</br></br>';
    $que = "SELECT sub_category_id, parent_id, sub_category_name, price FROM repair_sub_category ORDER BY parent_id";
    $run = @mysqli_query($dbc, $que);    
    while ($row2 = mysqli_fetch_array($run, MYSQLI_ASSOC)){
        if (($row2['parent_id']) == ($row['category_id'])){
            echo '<p>' . $row2['sub_category_name'] . '..............' . $row2['price'] . ' (Lt) ';
            //echo '</br>';
            
        }
        
        
    }  
 echo '<p></p></br>';       
}


include ('includes/footer.html')
?>
