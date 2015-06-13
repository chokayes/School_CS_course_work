<?php
        //allow me to store and use session arary
        session_start(); 
        ini_set('display_errors', 'On');
        
        // used to hold password to database
        include 'storedInfo.php';

        //cheking to see if this person accessing this page has logged in
         if(!isset($_SESSION['loggedin'])) {
            //if they havent send them to loggin 
            header("location: login.php");  
        }
        
        //error check - used to see if userers where still logged in 
           //  print_r($_SESSION);
            // echo "<br> above is session varibles<br>";
       
        //print_r($_POST);
        //echo "<br> above ispost  varibles<br>";

        // of we have decided to show th rns list
        if($_POST['show'] == "listrn")
        {
           // echo"you have decided to show the list<br>"; 
            
            //my passowrd is on hidden page
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "payneal-db", $myPassword, 
                "payneal-db");

            if ($mysqli->connect_errno)
            {
                    echo "Failed to connect tp MySQL: (" . $mysqli->connect_errno . ") "
                        . $mysqli->connect_error;
            }
            else
            {
            // echo "connection worked! <br> ";
            }
            
            //creats the table
            echo"<table border=1>
            <tr>
            <th>Title</th>
            <th>Fisrt name</th>
            <th>last name</th>
            <th>PrimarySpecality</th>
            <th>cell #</th>
            <th>home #</th>
            <th>email</th>
            <th>state</th>
            <th>city</th>
            <th>adress</th>
            </tr>"; 
         
            // combiinding hcp and rns
             if (!($stmt = $mysqli->prepare("select DISTINCT hcp.title, hcp.fname, hcp.lname, hcp.cellphone, hcp.homephone, hcp.email, hcp.state, hcp.city, hcp.adress, rns.speciality from RN_HCP hcp
inner join RNs rns on rns.pid = hcp.id
where hcp.recruiter='".$_SESSION['username']."'"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($t, $fn, $ln, $cell, $home, $email, $state, $city, $adress, $specality)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            { 
                echo "<tr>";
                 echo "<td>" . $t."</td>"; 
                echo "<td>" . $fn."</td>"; 
                 echo "<td>" . $ln."</td>";
                 echo "<td>" . $specality."</td>";
                 echo "<td>" . $cell."</td>";
                 echo "<td>" . $home."</td>";
                 echo "<td>" . $email."</td>";
                 echo "<td>" . $state."</td>"; 
                 echo "<td>" . $city."</td>"; 
                 echo "<td>" . $adress." </td>"; 
                echo "</tr>";  
            } 
            echo "</table>"; 
        $stmt->close();  
            
        }
    else if($_POST['show'] == "editrn") 
        {
             //   echo"you have decided to edit the list<br>";   
           
           
                  //my passowrd is on hidden page
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "payneal-db", $myPassword, 
                "payneal-db");

            if ($mysqli->connect_errno)
            {
                    echo "Failed to connect tp MySQL: (" . $mysqli->connect_errno . ") "
                        . $mysqli->connect_error;
            }
            else
            {
            // echo "connection worked! <br> ";
            }
            
        $spec= array(); 
           
            if (!($stmt = $mysqli->prepare("SELECT speciality FROM RN_specalities"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($s)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            {
                $spec[]= $s; 
            } 
           
           
             $thestate= array(); 
           
            if (!($stmt = $mysqli->prepare("SELECT state FROM RN_licensure"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($a)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            {
                $thestate[]= $a; 
            } 
       
            echo"<table border=1>
            <tr>
            <th>Title</th>
            <th>Fisrt name</th>
            <th>last name</th>
            <th>PrimarySpecality</th>
            <th>cell #</th>
            <th>home #</th>
            <th>email</th>
            <th>home state</th>
            <th>city</th>
            <th>adress</th>
            </tr>"; 
            
             if (!($stmt = $mysqli->prepare("select DISTINCT hcp.title, hcp.id, hcp.fname, hcp.lname, hcp.cellphone, hcp.homephone, hcp.email, hcp.state, hcp.city, hcp.adress, rns.speciality from RN_HCP hcp
inner join RNs rns on rns.pid = hcp.id
where hcp.recruiter='".$_SESSION['username']."'"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($t,$id, $fn, $ln, $cell, $home, $email, $state, $city, $adress, $specality)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            {
                // set equal to ???????? is no infomation
                if($cell == "")
                {
                    $cell="??????????"; 
                }
                
                if($home == "")
                {
                    $home="??????????"; 
                }
                
                //set equal to Unknow id no information
                if($city == "")
                {
                    $city="UNKNOWN"; 
                }
                
                if($adress == "")
                {
                    $adress="UNKNOWN"; 
                }
                if ($t=="")
                {
                    $t="???"; 
                }
                echo "<tr>";
                 echo "<td>" .'<input type =text name=title id="'.$id.'title" value="'.$t.'"</td>';
                echo "<td>" .'<input type=text name=fname id="'.$id.'fname" value="' . $fn. '" </td>'; 
                 echo "<td>" .'<input type=text name=lname id="'.$id.'lname" value="' . $ln.'" </td>';
                 echo "<td>" .'<select name=specality id="'.$id.'specality" value=" '.$specality.'" </td>';
                for($x=0; $x< sizeof($spec); $x++)
                {
                    //if specality matches leave it selected
                    if($spec[$x]== $specality)
                    {
                        echo " <option value='".$spec[$x]."' selected>'".$spec[$x]."'</option>"; 
                    }
                    else 
                    {
                        echo " <option value=".$spec[$x].">".$spec[$x]."</option>"; 
                    }
                }
                
                 echo "<td>" .'<input type=text name=cell id="'.$id.'cell" value=' . $cell. ' </td>';
                 echo "<td>" .'<input type=text name=home id="'.$id.'home" value=' . $home. ' </td>';
                 echo '<td>' .'<input type=text name=email id="'.$id.'email" value= ' . $email. ' </td>';
                echo "<td>" .'<select name=specality id="'.$id.'state" </td>';
                for($x=0; $x< sizeof($thestate); $x++)
                {
                    // if state matches  make it selected
                     if($thestate[$x]== $state)
                    {
                         
                        echo " <option value=".$thestate[$x]." selected>".$thestate[$x]."</option>"; 
                    }
                    else 
                    {
                        echo '<option value='.$thestate[$x].'>'.$thestate[$x].'</option>'; 
                    }
                }
                
                echo '<td>' .'<input type=text name=city id="'.$id.'city" value="' .$city. '" </td>'; 
                echo "<td>" .'<input type=text name=adress id="'.$id.'adress" value="' .$adress. '" </td>';
               
                 echo "<td>" . "<input type='submit' name='editrnlist' id='editrnlist'style='color: white; background-color:green' value='update' onClick='editrnlist(".$id.")' </td>";
                echo "<td>" . "<input type='submit' name='deleternlist' id='deleternlist' style='color: white; background-color:red' value='delete' onClick='deletelist(".$id.")' </td>"; 
                
                echo "</tr>"; 
            }  
         echo "</table>";
        
        //echo $id."adress <br>"; 
        
     
            $stmt->close();  
        }

?>