<?php
// database.php is only used for creating a new account or logginin in existing account 

// start the session
	session_start(); 

//http://eecs.oregonstate.edu/ecampus-video/player/player.php?id=74
ini_set('display_errors', 'On');
include 'storedInfo.php';
//contains my password so not submitted to git hub - $myPassword
    
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "payneal-db", $myPassword, "payneal-db");
if ($mysqli->connect_errno)
    {
        echo "Failed to connect tp MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    else
    {
     // echo "connection worked! <br> ";
    }

$count= NULL; 

	if (isset($_POST['name']))
	{	
		$name = $_POST["name"]; 
		$username = $_POST["username"];  
		$pword = $_POST["password"]; 
	
	
		  if(!($stmt = $mysqli->prepare("INSERT INTO RN_login(username, name, pword) 
		  VALUES ('$username', '$name', '$pword')"))) 
        {
			echo "Prepare failed (INSERT): (" . $mysqli->errno . ") " . 
                $mysqli->error;
        }
        if(!$stmt->execute()) 
        {
			echo "Execute (INSERT INTO) failed: (" . $mysqli->errno . ") " . 
                $mysqli->error;
		}
        $stmt->close(); 

		echo "you have created an account now login as an existing user"; 
	}
    else
    {
        //checks to see if username is in the database
        $username = $_POST["username"];  
		$pword = $_POST["password"];
    
        if(!($stmt = $mysqli->prepare("SELECT count(*) from RN_login WHERE username = '$username'")))
        {
		  echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
        }
	    if(!$stmt->execute()) 
        {
		  echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	    }
        if (!$stmt->bind_result($count)) 
        {
	       echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
        }
        while ($stmt->fetch())
        {
           //getting the value of count (checking if that username is in database)  
        }
        
        $stmt->close(); 
        
        if ($count == 0) 
        {
            //if its not this will appear on the page
            echo "that is not a registered username, try again";  
        }
        //if username is vaild we will now check the password
        else 
        {
            if(!($stmt = $mysqli->prepare("SELECT count(*) from RN_login WHERE username = '$username' and pword = '$pword'")))
            {
		      echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
            }
	       if(!$stmt->execute()) 
            {
		      echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	        }
            if (!$stmt->bind_result($count)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while ($stmt->fetch())
            {
                //getting the value of count (checking if that username is in database)  
            } 
            
            $stmt->close(); 
            
            if ($count == 0) 
            {
                //if its not this will appear on the page
                echo "incorrect password, try again";  
            }
            else 
            {
                if(!($stmt = $mysqli->prepare("SELECT name from RN_login WHERE username = '$username' and pword = '$pword'")))
                {
		          echo "Prepare failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
                }
	           if(!$stmt->execute()) 
                {
		          echo "Execute failed (UPDATE): (" . $mysqli->errno . ") " . $mysqli->error;
	            }
                if (!$stmt->bind_result($name)) 
                {
	               echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
                }
                while ($stmt->fetch())
                {
                    //getting the value of count (checking if that username is in database)  
                }
        
                $stmt->close(); 
                // hold fact user logged in and users name
                $_SESSION['loggedin'] = "YES";
                $_SESSION['name'] = $name;
                $_SESSION['username'] = $username; 
                 echo "access";
            }    
        }
     }
?>