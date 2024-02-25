        <?php # Script 9.5 - #5

        $page_title = 'Registruoti Apžiūrai';
        require_once ('includes/config.inc.php'); 
        include ('includes/header.html');

        // If no first_name session variable exists, redirect the user:
        if (!isset($_SESSION['first_name'])) {

                $url = BASE_URL . 'index.php'; // Define the URL.
                ob_end_clean(); // Delete the buffer.
                header("Location: $url");
                exit(); // Quit the script.	
        }

        echo '<h1>Registruoti automobilį apžiūrai</h1>';

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


        if ((isset($_POST['submitted'])) && (!empty($_POST['checkbox']))) {
            $checkbox = array();
            foreach($_POST['checkbox'] as $checkbox){
            //echo $checkbox . ' ';   
                    
}
            if (!empty($checkbox)){
            require_once ('mysqli_connect.php');
            $user_id = $_SESSION["user_id"];
            $unique_key = substr(md5(rand(0, 1000000)), 0, 10);
            foreach ($_POST['checkbox'] as $sub_id){            
            
            $query = "SELECT sub_category_id, sub_category_name, price FROM repair_sub_category WHERE sub_category_id=$sub_id";
            $paleisti = @mysqli_query ($dbc, $query);
            
            if (mysqli_num_rows($paleisti) == 1){                
                
                
            $rezultatai = mysqli_fetch_array($paleisti, MYSQLI_NUM);
            
            $sub_name = $rezultatai[1];
            $sub_price = $rezultatai[2];
            
            $query2 = "INSERT INTO reg_info(random_id, sub_id, sub_name, sub_price) VALUES ('$unique_key', '$sub_id', '$sub_name', '$sub_price')";
            $paleisti2 = mysqli_query ($dbc, $query2) or trigger_error("Query: $query2\n<br />MySQL Error: " . mysqli_error($dbc));                 

            }
            
            
            else{
                echo '<p class="error">Įvyko klaida</p>';
            }
 
                
            }
            $query3 = "INSERT INTO registruoti_auto(user_id, auto_id, reg_data, info_id) VALUES('$user_id', '$id', NOW(), '$unique_key')";
            $paleisti3 = mysqli_query ($dbc, $query3) or trigger_error("Query: $query3\n<br />MySQL Error: " . mysqli_error($dbc));              
        }

            echo '<h2>Jūsų pasirinktos paslaugos buvo sėkmingai užregistruotos!</h2>';
        } elseif ((isset($_POST['submitted']))&& (empty($_POST['checkbox']))){
            echo '<p class="error">Nepasirinkote nei vienos paslaugos!</p>';
            
        $q = "SELECT vin, marke, modelis, pag_metai, keb_tipas, spalva FROM auto_kortele WHERE auto_id=$id";		
        $r = @mysqli_query ($dbc, $q);

        if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

                // Get the user's information:
                $row = mysqli_fetch_array ($r, MYSQLI_NUM);

        
                // Create the form:
                echo 
                      '<fieldset>
                      <h2> Apžiūrai registruojamo automobilio informacija:<p></p></h2>
                      <p><b>Automobilio markė:  </b>' . $row[1] .'</p>
                      <p><b>Automobilio modelis:  </b>' . $row[2] .'</p>
                      <p><b>Automobilio pagaminimo metai:  </b>' . $row[3] .'</p>
                      <p><b>Automobilio kėbulo numeris:  </b>' . $row[0] .'</p>     
                      </fieldset>
                      </br>';
$que = "SELECT category_id, category_name FROM repair_category ORDER BY category_id";		
$run = @mysqli_query ($dbc, $que);                                     

                    echo
                         '<form action="registruoti_apziurai.php?id=' . $id . '" method="post">
                          <fieldset>
                          <h2>Pasirinkite norimas paslaugas:</h2>';                
                    while ($eile = mysqli_fetch_array($run, MYSQLI_ASSOC)) {
                          echo '<b>' . $eile['category_name'] . '</b>';
                            echo '</br></br>';
                            $que2 = "SELECT sub_category_id, parent_id, sub_category_name, price FROM repair_sub_category ORDER BY parent_id";
                            $run2 = @mysqli_query($dbc, $que2);  
                            while ($eile2 = mysqli_fetch_array($run2, MYSQLI_ASSOC)){
                                if (($eile2['parent_id']) == ($eile['category_id'])){
                                echo '<p><input type="checkbox" name="checkbox[' . $eile2['sub_category_name'] . ']" value="' . $eile2['sub_category_id'] . '">' . $eile2['sub_category_name'] . ' ..............' . $eile2['price'] . ' (Lt) ';
                                echo '</br>';            
                                }                                                 
                }
               echo '<p></p></br>';

    }              
                   echo'  </fieldset>
                   <div align="center"><input type="submit" name="submit" value="Išsaugoti" /></div>
                   <input type="hidden" name="submitted" value="' . $id . '" />
                   </form>';                   
                   
        }   
        }else{
                  $q = "SELECT vin, marke, modelis, pag_metai, keb_tipas, spalva FROM auto_kortele WHERE auto_id=$id";		
        $r = @mysqli_query ($dbc, $q);

        if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

                // Get the user's information:
                $row = mysqli_fetch_array ($r, MYSQLI_NUM);

        
                // Create the form:
                echo 
                      '<fieldset>
                      <h2> Apžiūrai registruojamo automobilio informacija:<p></p></h2>
                      <p><b>Automobilio markė:  </b>' . $row[1] .'</p>
                      <p><b>Automobilio modelis:  </b>' . $row[2] .'</p>
                      <p><b>Automobilio pagaminimo metai:  </b>' . $row[3] .'</p>
                      <p><b>Automobilio kėbulo numeris:  </b>' . $row[0] .'</p>     
                      </fieldset>
                      </br>';
$que = "SELECT category_id, category_name FROM repair_category ORDER BY category_id";		
$run = @mysqli_query ($dbc, $que);                                     

                    echo
                         '<form action="registruoti_apziurai.php?id=' . $id . '" method="post">
                          <fieldset>
                          <h2>Pasirinkite norimas paslaugas:</h2>';                
                    while ($eile = mysqli_fetch_array($run, MYSQLI_ASSOC)) {
                          echo '<b>' . $eile['category_name'] . '</b>';
                            echo '</br></br>';
                            $que2 = "SELECT sub_category_id, parent_id, sub_category_name, price FROM repair_sub_category ORDER BY parent_id";
                            $run2 = @mysqli_query($dbc, $que2);  
                            while ($eile2 = mysqli_fetch_array($run2, MYSQLI_ASSOC)){
                                if (($eile2['parent_id']) == ($eile['category_id'])){
                                echo '<p><input type="checkbox" name="checkbox[' . $eile2['sub_category_name'] . ']" value="' . $eile2['sub_category_id'] . '">' . $eile2['sub_category_name'] . ' ..............' . $eile2['price'] . ' (Lt) ';
                                echo '</br>';            
                                }                                                 
                }
               echo '<p></p></br>';

    }              
                   echo'  </fieldset>
                   <div align="center"><input type="submit" name="submit" value="Išsaugoti" /></div>
                   <input type="hidden" name="submitted" value="' . $id . '" />
                   </form>';                   
                   
        }   
            
            
        }  
            
            
        

            
            /*foreach($_POST['checkbox'] as $checkbox);{
             if (!isset($checkbox)){
                 echo '<p>Nieko nepasirinkote</p>';
             }
                 echo $checkbox . ' ';
             
         }*/
                   

   



        mysqli_close($dbc);


               include ('includes/footer.html');
        ob_end_flush();
        ?>