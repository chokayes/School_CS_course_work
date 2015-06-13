<?php
        session_start(); 
        ini_set('display_errors', 'On');
                
       //redirects user to login page if not logged in
        if(!isset($_SESSION['loggedin'])) 
        {
            header("location: login.php");  
        }
        $name = $_SESSION['name'];  
?>    

<!DOCTYPE html>
	<html>
	<head>
    	<meta charset="utf-8">
    	<title>Work-A-Nurse</title>
    	<link rel="stylesheet" type="text/css" href="nurse.css">
	</head> 
	<!-- border for all of website -->
    <body> 
        <body> 
       <h1> Welcome <?php echo $name; ?></h1>
        
        <?php var_dump($_SESSION); ?>
        <br>
        <form action= "employee.php" method="post"> 
        
            <input type="submit" id="signout" 
                    	name="signout" value="sign out">
        </form>
    
    </body>
</html>    