<?php # Script 9.3 - edit_user.php

// This page is for editing a user record.
// This page is accessed through view_users.php.

$page_title = 'Užsakymo išpildymas';
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

echo '<h1>Užsakymo išipildymas</h1>';

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

if ((isset($_POST['submitted'])) && (!empty($_POST['checkbox']))) {
    $checkbox = array();
            foreach($_POST['checkbox'] as $checkbox){
                //echo $checkbox . ' ';   
                    
}   
}
if (!empty($checkbox)){
            require_once ('mysqli_connect.php');
            $uzpilde = $_SESSION["user_id"];
            $unique_key = substr(md5(rand(0, 1000000)), 0, 10);
            
            $q1 = "SELECT user_id, auto_id, reg_data, info_id FROM registruoti_auto WHERE info_id = '$id'";
            $r1 = @mysqli_query($dbc, $q1);
            $eile1 = mysqli_fetch_array ($r1, MYSQLI_NUM);   // auto info, user info
            $kliento_id = $eile1[0];
            $a_id = $eile1[1];          
            
            
            $q2 = "SELECT first_name, last_name, email FROM users WHERE user_id= '$kliento_id' LIMIT 1";
            $r2 = @mysqli_query($dbc, $q2);
            $eile2 = mysqli_fetch_array ($r2, MYSQLI_NUM);
            
            $q3 = "SELECT vin, marke, modelis, pag_metai FROM auto_kortele WHERE auto_id = '$a_id' LIMIT 1";
            $r3 = @mysqli_query($dbc, $q3);
            $eile3 = mysqli_fetch_array ($r3, MYSQLI_NUM);
            
            $q10 = "SELECT first_name, last_name FROM users WHERE user_id= '$uzpilde' LIMIT 1";
            $r10 = @mysqli_query($dbc, $q10);
            $eile10 = mysqli_fetch_array ($r10, MYSQLI_NUM);
   
            
            $q4 = "INSERT INTO ataskaitos_duomenys (atask_id, first_name, last_name, email, vin, marke, modelis, pag_metai, atlikimo_data, user_id, auto_id, mvardas, mpavarde) VALUES ('$unique_key', '$eile2[0]', '$eile2[1]', '$eile2[2]', '$eile3[0]', '$eile3[1]', '$eile3[2]', '$eile3[3]', NOW(), '$kliento_id', '$a_id', '$eile10[0]', '$eile10[1]')";
            $r4 = @mysqli_query($dbc, $q4);           
                                     
            
            foreach ($_POST['checkbox'] as $sub_id){
                            
            $q5 = "SELECT sub_category_id, sub_category_name, price FROM repair_sub_category WHERE sub_category_id=$sub_id";
            $paleisti = @mysqli_query ($dbc, $q5);
            
            if (mysqli_num_rows($paleisti) == 1){      
                
                
            $rezultatai = mysqli_fetch_array($paleisti, MYSQLI_NUM);
            
            $sub_name = $rezultatai[1];
            $sub_price = $rezultatai[2];
            
            $query2 = "INSERT INTO ataskaita2(ataskaitos_id, darbas, price) VALUES ('$unique_key', '$sub_name', '$sub_price')";
            $paleisti2 = mysqli_query ($dbc, $query2) or trigger_error("Query: $query2\n<br />MySQL Error: " . mysqli_error($dbc));                 

            }
            
            
            else{
                echo '<p class="error">Įvyko klaida</p>';
            }
 
                
            }
            
            echo '<h2>Informacija buvo išsaugota. Užsakymas ištrinamas iš sąrašo..</h2></br>';
            $q7 = "DELETE FROM registruoti_auto WHERE info_id='$id' LIMIT 1";		
            $r7 = @mysqli_query ($dbc, $q7);
            if (mysqli_affected_rows($dbc) == 1){
                echo'</br><p>Užsakymas sėkmingai ištrintas iš sąrašo</p>';
            }else{
                echo'<p class="error">Įvyko klaida ištrinant užsakymą iš sąrašo</p>';
                echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q7 . '</p>';
            }
            
            $q8 = "DELETE FROM reg_info WHERE random_id='$id'";
            $r8 = @mysqli_query($dbc, $q8);
                
            if (mysqli_affected_rows($dbc) != 0){
                echo'</br><p>Išpildytus užsakymus galite peržiūrėti išpildytų užsakymų skiltyje.</p>';
            }else{
                echo'<p class="error">Įvyko klaida ištrinant užsakymą iš sąrašo</p>';
                echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q8 . '</p>';
            }
            
             
        }elseif((isset($_POST['submitted']))&& (empty($_POST['checkbox']))){
            echo '<p class="error">Neišpildėte nei vieno užsakymo</p>';
            
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
                      <p><b>Automobilio spalva: </b>' . $row2[5] .'</p></br>';
        }
                    echo '<h2>Pažymėkite paslaugas, kurios buvo atiktos šiam automobiliui:</h2>';
                    $darbai = "SELECT reg_info_id, sub_id, sub_name, sub_price FROM reg_info WHERE random_id= '$id' ";
                    $row3 = @mysqli_query($dbc, $darbai);
                    
                    echo '<form action="ispildyti.php?id=' . $id . '" method="post">
                     <fieldset>';
                    
                    while ($row4 = mysqli_fetch_array($row3, MYSQLI_ASSOC)) {
                    echo '<p><input type="checkbox" name="checkbox[' . $row4['sub_name'] . ']" value="' . $row4['sub_id'] . '">' . $row4['sub_name'] . ' ..............' . $row4['sub_price'] . ' (Lt) ';
                    echo '</br>';                        
                    
                    }               
                   echo'</fieldset>
                   <div align="center"><input type="submit" name="submit" value="Išsaugoti" /></div>
                   <input type="hidden" name="submitted" value="' . $id . '" />
                   </form>';            
    }
            
        }else{




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
                      <p><b>Automobilio spalva: </b>' . $row2[5] .'</p></br>';
        }
                    echo '<h2>Pažymėkite paslaugas, kurios buvo atiktos šiam automobiliui:</h2>';
                    $darbai = "SELECT reg_info_id, sub_id, sub_name, sub_price FROM reg_info WHERE random_id= '$id' ";
                    $row3 = @mysqli_query($dbc, $darbai);
                    
                    echo '<form action="ispildyti.php?id=' . $id . '" method="post">
                     <fieldset>';
                    
                    while ($row4 = mysqli_fetch_array($row3, MYSQLI_ASSOC)) {
                    echo '<p><input type="checkbox" name="checkbox[' . $row4['sub_name'] . ']" value="' . $row4['sub_id'] . '">' . $row4['sub_name'] . ' ..............' . $row4['sub_price'] . ' (Lt) ';
                    echo '</br>';                        
                    
                    }               
                   echo'</fieldset>
                   <div align="center"><input type="submit" name="submit" value="Išsaugoti" /></div>
                   <input type="hidden" name="submitted" value="' . $id . '" />
                   </form>';            
    }
        }
 mysqli_close($dbc);


               include ('includes/footer.html');
        ob_end_flush();
?>