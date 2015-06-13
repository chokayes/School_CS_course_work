<?php
ini_set('display_errors', 'On');

session_start(); 
 if(!isset($_SESSION['loggedin'])) {
     //if they havent send them to loggin 
     header("location: login.php");  
    }

include 'storedInfo.php';//database password = $myPass

//shows all of the post varibles
print_r($_POST);
echo"<br>"; 



if($_POST['search']=='rn') 
{           
            //need to find out how to hidn my password 
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "payneal-db", $myPassword , 
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
    
    
            
            $lname = $_POST["lname"];  
            $fname = $_POST["fname"];
            $email = $_POST["email"];
            $email = $_POST["email"];
            $phone = $_POST["number"];
            $specality = $_POST["specailty"];
            $lstate = $_POST["state"];
            $type = $_POST["rntypesearch"];       
            
            // these results will always be contained
            $search = array( "SELECT hcp.lname, hcp.fname, hcp.email, hcp.cellphone, hcp.homephone "); 
            $where = array(); 
            $from = array("From RN_HCP hcp"); 
    
            // is the last name blank
            if ($lname == " " || $lname =="") 
            {  
            }
            else
            {
                //not so we add this to query
               
                $where[]="hcp.lname Like '%$lname%'"; 
            }
            //is the first name blank  
            if ($fname == " " || $fname =="") 
            {
                
            }
            else
            {
                //not so we add this to query 
             
                $where[]= "hcp.fname like '%$fname%'"; 
            }
            // is the email empty
            if ($email== " " || $email=="") 
            {
                
            }
            else
            {
                
                //did like for email because user will ofer spell them                              incorectly
                $where[]= "hcp.email like '%$email%'"; 
                
            }
            
            //is there a phone number input
            if ($phone == " " || $phone =="") 
            {
                
            }
            else
            {
    
                $where[]= "hcp.cellphone = '$phone' OR hcp.homephone = '$phone'"; 
            }
            //was a specality input
            if ($specality == " " || $specality=="") 
            {
                
            }
            else
            {
                //ad this to query 
                
                //must get the specality number because experience hold ids of specalities and we need to check 
                //that as well 
                
                $theid=0;  
                    if (!($stmt = $mysqli->prepare("SELECT id FROM RN_specalities WHERE speciality= '$specality' "))) 
                {
                    echo "Prepare failed (SELECT):
                        (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) 
                {
                    echo "Execute failed:
                        (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->bind_result($id)) 
                {
	               echo "select failed (bind): 
                        (" . $stmt->errno . ") " .$stmt->error;
                }
                 while($stmt->fetch()) 
                { 
                    $theid= $id;  
                }

echo "this is the id $theid";  
                //add  to query 
                $search[]="rnspec.speciality";
                $where[]="rne.id ='$theid'"; 
                $from []= "INNER JOIN RN_experience rne ON rne.rnpid = hcp.id INNER JOIN RN_specalities rnspec ON rnspec.id = rne.experience"; 
                
                //errock check the string
             //   $stmt->close(); 
            }
            //is license blank 
             if ($lstate == " " || $lstate=="") 
            {
                
            }
            else
            {
                //if not add that to query
                $search[]= "rns.license"; 
                $where[]= "rns.license = '$lstate'"; 
                $from []="INNER JOIN RNs rns ON rns.pid = hcp.id"; 
            }
            //is the city blank
             if ($type == " " || $type=="") 
            {
                    
            }
            else
            {
                $search[] = " rnd.travler, rnd.perdiem, rnd.perm"; 
                $from= "INNER JOIN RN_desire rnd ON rnd.rnpid = rns.pid"; 
                //if not add that to query
                if ($type == 'pd')
                {
                    $where[]= "rnd.perdiem  = '1'"; 
                }
                else if ($type =='pm')
                {
                    $where[]= "rnd.perm  = '1'"; 
                }
                else if ($type =='tl') 
                {
                    $where[]= "rnd.traveler  = '1'"; 
                }
            }
    
    
            //put out commons in
            $select= implode(', ', $search);  
            $where= "where ".implode (' and ', $where);  
            $from = implode(' ', $from); 

    /*
    
    this contains everything I want searchable so far
    
    SELECT DISTINCT hcp.lname, hcp.fname, hcp.email, hcp.cellphone, hcp.homephone, rnspec.speciality, rns.license, rnd.travler, rnd.perdiem, rnd.perm
FROM RN_HCP hcp
INNER JOIN RN_experience rne ON rne.rnpid = hcp.id
INNER JOIN RN_specalities rnspec ON rnspec.id = rne.experience
INNER JOIN RNs rns ON rns.pid = hcp.id
INNER JOIN RN_desire rnd ON rnd.rnpid = rns.pid
    
    
    // stuct here I need to find some way to bind all of the selects since they not yet determined 
    will work on after finals
   
        
     if (!($stmt = $mysqli->prepare("$select $from $where"))) 
                {
                    echo "Prepare failed (SELECT):
                        (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) 
                {
                    echo "Execute failed:
                        (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->get_result() 
                {
	               echo "select failed (bind): 
                        (" . $stmt->errno . ") " .$stmt->error;
                }
    
    */
    
    echo "$select <br>"; 
    echo "$from <br>"; 
     echo "$where <br>"; 
    
}

?>