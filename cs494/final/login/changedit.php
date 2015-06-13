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
             //print_r($_SESSION);
             //echo "<br> above is session varibles<br>";
        
       //print_r($_POST);
//echo "<br> above ispost  varibles<br>";


//    echo"so you wish to delete an RN<br>"; 
         //  print_r($_POST);
                
 
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


        if($_POST['changeit'] == "rn")
        {
           // echo"so you wish to change  an rn<br>"; 
            //print_r($_POST);
          
            //plan put all of post in an array    
            $collected= array(); 
            
           // take all of the post varibles and and make them php values    
            $collected[]= $_POST["title"]; 
            $collected[]= $_POST["hcpid"];  
            $collected[]= $_POST["fname"];
            $collected[]= $_POST["lname"]; 
            $collected[]= $_POST["cell"];
            $collected[]= $_POST["home"]; 
            $collected[]= $_POST["email"]; 
            $collected[]= $_POST["adress"]; 
            $collected[]= $_POST["city"]; 
           $collected[]= $_POST["state"];
           $collected[]= $_POST["specality"];
    
            // hold the primary key doiesnt allow user to change it, only is used so i can collect correct data
             $hcp_id= $_POST["hcpid"];
                      
            //run a select statement inner join distinct and get all values need ( rns specality is in seperate table) 
             if (!($stmt = $mysqli->prepare("select hcp.title, hcp.id, hcp.fname, hcp.lname, hcp.cellphone, hcp.homephone, hcp.email, hcp.state, hcp.city, hcp.adress, rns.speciality from RN_HCP hcp
inner join RNs rns on rns.pid = hcp.id
where hcp.id='$hcp_id'"))) 
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
                //no error check for if char is number or letter  will need to be implimented later    
                if($cell == "" )
                {
                    //no cell value - similar for all other cases
                    $cell="??????????"; 
                }
                if($home == "")
                {
                    $home="??????????"; 
                }
                if($city == "")
                {
                    $city="UNKNOWN"; 
                }
                if($adress == "")
                {
                    $adress="UNKNOWN"; 
                }
                if ($t=="" )
                {
                    $t="???"; 
                }        
            }
           //holds all possible errors
            $error= array(); 
            
             for($x=0; $x<sizeof($collected); $x++)
             {
                if (strlen($collected[$x])> 10 && ($x==4 || $x==5) )
                 {
                    //the phone numbers cant be longer than 10 digits  
                     $error[]="#'s cant be longer than 10 digits no special chars<br>";  
                 }
                 if (strlen($collected[$x]> 255) && ($x==2 || $x==3 || $x==6) )
                 {
                    //the fname, lname , email  cant be longer than 255 chars  
                      $error[]="first name , last name, email is too long <br> ";  
                 }   
                 if (strlen($collected[$x])> 20 && $x==0 )
                 {
                    //the title cant be longer than 20 chars  
                      $error[]="title can only be at max 20 chars<br> "; 
                 }
                if (strlen($collected[$x])> 100 && $x==7 )
                 {
                    //the adress cant be longer than 20 chars  
                    
                    echo"$collected[$x] is "; 
                    
                     $error[]="adress too long<br>"; 
                 } 
                if (strlen($collected[$x]> 25) && $x==8 )
                 {
                    //the title cant be longer than 20 chars  
                     $error[]="city too long<br>"; 
                 }    
            }
            //if there are errors just print the array to be returned and colse the connection
            if (sizeof($error)> 0) 
            {
                print_r( $error);
                $stmt->close(); 
            }
            else
                
            {
                // will hold all values
                 $arr = array ();
                //error check
                //echo" this is the specality $specality";  
                    //holds all of the results
                    $arr[]= $t; 
                    $arr[]= $id;
                    $arr[]= $fn;
                    $arr[]= $ln;
                    $arr[]= $cell; 
                    $arr[]= $home;
                    $arr[]= $email;
                    $arr[]= $adress;  
                    $arr[]= $city;
                    $arr[]= $state;
                    $arr[]= $specality;  

                //echo" this is the specality $arr[10]";  
                //created to make updating easier 
                $table[]= "title"; 
                $table[]= "id"; 
                $table[]= "fname";
                $table[]= "lname"; 
                $table[]= "cellphone";
                $table[]= "homephone"; 
                $table[]= "email"; 
                $table[]= "adress"; 
                $table[]= "city";
                $table[]= "state";
                $table[]= "specality";

                
                //error check to see the id
                //echo"$hcp_id is the id "; 
                
                
                for ($x=0; $x< sizeof($arr); $x++)
                {
                    if($collected[$x] != $arr[$x])
                    {
                        //echo "$collected[$x] is not equal to $arr[$x] <br>";
                    
                        if($x< 10)
                        {
                            //updates all cases that are not of an equal value
                            if(!($stmt = $mysqli->prepare("UPDATE RN_HCP SET $table[$x] = '$collected[$x]' WHERE 
                                id = '$hcp_id'")))
                            {
                                echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
                            }
	                       if(!$stmt->execute()) 
                           {
                                echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	                       }  
                        }
                        else if($x == 10)
                        {  // the 10 element in the array is from another table so further digging is required
                        
                            $thecount=0; 
                            // see if that update has an instance in the database licensure 
                            if (!($stmt = $mysqli->prepare("SELECT count(speciality) FROM RNs WHERE pid='hcp_$id'")))                                                           {
                                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
                             }
                            if (!$stmt->execute()) 
                            {
                                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                            }
                           if (!$stmt->bind_result($count)) 
                            {
	                              echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
                             }
                              while($stmt->fetch()) 
                              {
                                  //collects id of desire
                                  $thecount= $count;  
                              }                  
                            
                            echo" this is the count $thecount"; 
                            
                            //if not  already in table for rn experience a new instance of rn licensure  is necessary   
                            if ($thecount==0)
                                {    
                                    // we need to enter a new specality but $collected[$x] is a string so we need to get the number that identifies this string in the rn specalities table so we can create a new specality because they were not a specalist in this area originally
                                echo" looks like we are inputing a new specality  but first must find the specality id so this is the specality $collected[$x] ";
                                
                                    $sid; 
                                    // see if that update has an instance in the database licensure 
                                    if (!($stmt = $mysqli->prepare("SELECT id FROM RN_specalities WHERE speciality= '$collected[$x]'")))                                                           {
                                        echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
                                     }
                                    if (!$stmt->execute()) 
                                    {
                                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                                    }
                                   if (!$stmt->bind_result($spe)) 
                                    {
                                          echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
                                     }
                                      while($stmt->fetch()) 
                                      {
                                          //collects id of desire
                                          $sid= $spe;  
                                      }                  
                                
                                echo" and this is the id $sid"; 
                                
                                
                                    echo" looks like we are inputing a new specality "; 
                                        //inputs to RN_experience the new specility
                                        if(!($stmt = $mysqli->prepare("INSERT INTO RN_experience 
                                                (rnpid, experience) VALUES ('$hcp_id', '$sid')"))) 
                                        {
                                            echo "Prepare failed (INSERT): (" . $mysqli->errno . ") " . $mysqli->error;
                                        } 
                                        if(!$stmt->execute()) 
                                        {
			                               echo "Execute (INSERT INTO) failed:
                                              (" . $mysqli->errno . ") ".$mysqli->error;
                                        }
		                                    echo "you have Inserted a new specality for $_POST[fname] $_POST[lname]";
                                }
                                    
                            // go to RNs and make change of the primary specality 
                            if(!($stmt = $mysqli->prepare("UPDATE RNs SET speciality = '$collected[$x]' WHERE 
                            pid = '$hcp_id'")))
                            {
                                echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	                        }
	                        if(!$stmt->execute())
                            {
                               echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	                        }       
                        }    
                    } 
                    else
                    {
                        //error check
                        //do nothing
                      //  echo "$collected[$x] is equal to $arr[$x] <br>"; 
                    }
                }
                $stmt->close();
                echo "database updated"; 
            }
        }
                    
        if($_POST['changeit'] == "deletern")
        {
        
   /*             
            ex this would show all of the instences of this hcp             
            
            for example I will use HCP 11
            
            SELECT * FROM `RN_HCP` hcp
            inner join RNs rn  on hcp.id =rn.pid
            inner join RN_experience exp on exp.rnpid = rn.pid 
            inner join RN_desire d on d.rnpid =rn.pid
            inner join RN_shift s on s.desireid = d.id
            inner join RN_hour h on h.desireid = d.id
            inner join RN_dlocation dl on dl.desireid =d.id
            where hcp.id ='11'
        */
            
            //if i wanted to deletet ex hcpid 11 I would
        
            //first get desire id with hcip which is also rnpid (they are linked/references)
            //select id from RN_desire where rnpid = '11'
                // this gave me 6 
           
            //passed in  hcpid with post
            $hcp_id=$_POST["hcpid"];
            
            $desireid; 
            // this will get the id that was created with the desire table
           if (!($stmt = $mysqli->prepare("SELECT id FROM
                RN_desire WHERE rnpid='".$hcp_id."'"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($theid)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            {
                //collects id of desire
                $desireid= $theid;  
            } 
            
            //then delete the one case of  shift with desire id
            //delete from RN_shift where desireid = '6'
                
            if(!($stmt = $mysqli->prepare("delete from RN_shift where desireid = '$desireid'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            
            //then delete the one case of hour with desired id
            //delete from RN_hour where desireid = '6'
              
             if(!($stmt = $mysqli->prepare("delete from RN_hour where desireid = '$desireid'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            
            //then delete all the cases of dlocations with desired id 
            //select * from RN_dlocation where desireid = '6'
            
             if(!($stmt = $mysqli->prepare("delete from RN_dlocation where desireid = '$desireid'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            
            // then i would delete the one instance of desire id 
            //delete from RN_desire where id = '6'  could also go by 11 but rnpid
            
             if(!($stmt = $mysqli->prepare("delete from RN_desire where id = '$desireid'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            
            // then i can delete all of the cases of experience that individual using hcpid aka rnpid
            //delete from RN_experience where rnpid = '11'
            
             if(!($stmt = $mysqli->prepare("delete from RN_experience where rnpid = '$hcp_id'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            
            //then i can delete all of the cases of rns which is all states individual is licensesd in using hcpid
            //delete from RNs where pid = '11'
            
            if(!($stmt = $mysqli->prepare("delete from RNs where pid = '$hcp_id'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            
            // then finially i can delete the hcp
            // delete from RN_HCP where id = '11'
            
            if(!($stmt = $mysqli->prepare("delete from RN_HCP where id = '$hcp_id'"))) 
            {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if(!$stmt->execute())
            {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
           
        
            
           //close the database 
           $stmt->close();
          
            
              $f=$_POST["fname"]; 
            $l=$_POST["lname"]; 
            
            echo $f. " ".$l. " has been deleted from the database";  
        }

?>