<?php
	ini_set('display_errors', 'On');

	// start the session
	session_start();    
   
    //logged in user is sent to employee page
    if(isset($_SESSION['loggedin'])) 
    {
            header("location: employee.php");  
    }
?>

<!DOCTYPE html>
	<html>
	<head>
    	<meta charset="utf-8">
    	<title>Work-A-Nurse</title>
        <link rel="stylesheet" type="text/css" href="../nurse.css">
        <script>
		//see if im dealing with new or existing user
		var newuser= false; 
		
		function existing_user(){
			// get the title 
			var changetitle=  document.getElementById("title"); 
			
			//get the response  
			var status=  document.getElementById("response"); 
			
			// get the place where enter name would be
			var getname=  document.getElementById("entername");
			
			//the submit button for a existing user
			status.innerHTML ="this is a existing user";
			
			//changes back title, if switched
			changetitle.innerHTML ="Employee Login"; 
			
			//erases name box if it has been created
			getname.innerHTML= ""; 
			newuser= false; 
            
            document.getElementById("status").innerHTML=""; 
		}
		
		function new_user(){
			newuser= true; 
			
            // get the title and change it
			var changetitle=  document.getElementById("title");
            
			changetitle.innerHTML ="New User Registration"; 
            
			//get the response  
			var status=  document.getElementById("response"); 
			 
			 //the submit button for a existing user
			status.innerHTML ="this is a new user";
 			
			//get the response  
			var getname=  document.getElementById("entername"); 
			 
			 //the submit button for a existing user
			getname.innerHTML="Name:<input type='text' name='name' id='name'><br>";
            
            document.getElementById("status").innerHTML=""; 
		}
		
		function signin(){
			//var changetitle=  document.getElementById("title"); 
			//changetitle.innerHTML ="sign in hit"; 
			
			//create or XMLHttpRequest object
			var hr= new XMLHttpRequest(); 
			
			//create varible that we need to send out phh file]
			var url = "database.php"; 
			
			var username= document.getElementById("uname").value; 
			var password= document.getElementById("pword").value; 
			var vars= "username="+ username+"&password="+password; 
			
			if ( newuser == true){	
				var name= document.getElementById("name").value;
				vars +="&name="+name;  			
			}
			
			hr.open("POST", url, true); 
			hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
			hr.onreadystatechange = function() 
			{
				if(hr.readyState== 4 && hr.status ==200)
				{
					var return_data = hr.responseText; 
                    if( return_data != "access")
                    {
					   document.getElementById("status").innerHTML = return_data;
                    }
                    else
                    {
                        //user is logged in so redirect
                        window.location.href = "http://web.engr.oregonstate.edu/~payneal/cs494/final/workanurse/login/employee.php";
                    }
				}
			
			}
			//send the data to php
			hr.send(vars); 
			document.getElementById("status").innerHTML= "loading ... "; 	
		}
		</script>
        
	</head> 
     <div class="jello" style="position:relative";>
    <body> 
<header id="header">     
             <!-- used to lower the nurse image--> 
            
            	<div style="height: 1em;  top:2em; position: relative;
                 	background: white;"></div>
           
				<!-- where I found image --><!--http://genkigoth.deviantart.com/art/Stop-Nurse-at-work-here-342168545 -->
                <!-- nurse  logo below --> 
	    		<a href="../index.php"><img id="nurselogo" 
        			src="../pictures/mynurselogosmall.png"  
        			alt="Work-A-Nurse logo" width="328" height="247" /></a>
        
        		  <!-- 1800 number( decided to do an image -->  
        		<img src="../pictures/phonenumber.png" width="261" height="45"
            		alt="1800wenurse"/>
                
                <!-- click to apply button-->  
        		<a href="../apply.php"><img id="apply" 
                	src="../pictures/applyonline.png" 
            		width="277" height="179" 
                	alt="apply now, by clicking this button"/></a>
                    
                 <!-- social media button--> 
                <a href="login.php"><img src="../pictures/social.png" 
                 	alt="employee login" id="social"/></a>
                    
                                           
                 <!-- emplyee login button--> 
                <a href="login.php"><img src="../pictures/login.png" 
                 	alt="employee login" width="198" height="49"
                    id="login"/></a>
                <nav id="navmain">
   				  <ul>
	  				<li><a href="../search.php">SEARCH JOBS |</a></li>
	  				<li><a href="../apply.php">APPLY |</a></li>
	  				<li><a href="../benefits.php">BENEFITS |</a></li>
	  				<li><a href="../aboutus.php">ABOUT US</a></li>      
				  </ul>    
			  	</nav>
			</header>
        
        <br><br> 
        
        
        
			<div class="loginform" style="width: 340px; padding:15px;  border: 5px solid gray; text-align:center; margin:0px auto; ">           
				<h1 id="title" style="text-align:center">Employee Login</h1>
                
            <!--put php code here to inform them they are already logged in if or not logged in-->
                
                	<!-- pick existing or new user --> 		
                	<input type="submit" id="signin" 
                    	name="signin" value="Existing User"
                        onClick="existing_user()">
                     
                     <!-- create new user-->    
                    <input type="submit" id="newuser" 
                        name="newuser" value="Create New User"
                        onClick= "new_user()">
                     
                     <!-- submit information-->    
                     <div id="response"> </div>  
                     <br>
                     <!-- allows new user to enter name -->
                    <span id="entername"> </span>
					UserName:<input type="text" name="uname" id="uname">
                    <br>
                    Password:<input type="password" name="pword" id="pword">
                    <br>
                     <input type='submit' name='submit' id='submit'
                     	onClick='signin()'>
                     <div id= "status"> </div>
                     
			</div> <!-- end of log in form --> 

			<footer>
	  			<p>
                	Copyright WORK-A-NURSE &copy;  <?php echo date("Y"); ?> - 
        			ALL RIGHTS RESERVED. - Website Terms and Conditions -
                    WWW.WORK-A-NURSE.COM	
                </p>	
            </footer>
       
</body>
</div>
</html>

