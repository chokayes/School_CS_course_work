<?php
        session_start(); 
        ini_set('display_errors', 'On');
                
       //redirects user to login page if not logged in
        if(!isset($_SESSION['loggedin'])) 
        {
            header("location: login.php");  
        }
        $name = $_SESSION['name'];  

           include 'printnew4.php';

?>    
<!DOCTYPE html>
<html>
	<head>
    	<meta charset="utf-8">
    	<title>Work-A-Nurse Search</title>
    	<link rel="stylesheet" type="text/css" href="../nurse.css">
        <script>
        function lookat()
            {
                var hr= new XMLHttpRequest(); 
			     //create varible that we need to send out phh file]
			     var url = "thesearch.php";
                
                var lname= document.getElementById("lnamesearch").value;
                var fname= document.getElementById("fnamesearch").value;
                var email= document.getElementById("emailsearch").value;
                var number= document.getElementById("phonesearch").value;
                var specality= document.getElementById("specalitysearch").value;
                var state=document.getElementById("statesearch").value;
                var type=document.getElementById("rntypesearch").value;
                
               var vars="search=rn&lname="+lname+"&fname="+fname+"&email="
                + email+"&number="+number+"&specailty="+specality+"&state="+state+"&rntypesearch="+type; 
                
                
                hr.open("POST", url, true); 
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
                
			     hr.onreadystatechange = function() 
			     {
				    if(hr.readyState== 4 && hr.status ==200)
				    {
					   var return_data = hr.responseText; 
                       
                        
                        document.getElementById("thesearch").innerHTML="";
                        document.getElementById("searchrn").innerHTML=return_data;
                        
                        
                
                    }
                        
                         
                }
                
                hr.send(vars);
                
			    document.getElementById("thesearch").innerHTML="...loading";    
            }
        </script>
	</head> 
	
    
     <div class="jello" style="position:relative";> 
    <body> 
       
       <?php //var_dump($_SESSION); ?>
        <table style="margin-left:auto; margin-right:auto" >
            <tr>
                <td>
                    <h1 style="text-align:center; color:blue"><?php echo $name; ?> what nurse are you looking for? </h1>
                </td>
                <td>
                       <!---used to sign out-->
                    <form action= "employee.php" method="post" > 
                        <input type="submit" id="signout" 
                    	   name="signout" value="sign out">
                    </form>
                </td>
            </tr>
        </table>
        
         <!---used to show links to other page-->
        <nav style="background-color: white; border-color:blue; color:green; " >
   	        <ul>
	  	        <li><a href="employee.php">Home |</a></li>
	  	        <li><a href="rn.php">RN |</a></li>
	  	        <li><a href="jobs.php">Jobs</a></li>      
            </ul>    
        </nav>
        <br>
                <table style ="margin-left:auto; margin-right:auto; ">
                    <tr> 
                     
                        <td> Last name :<br> <input type="text" name="top4title" id="lnamesearch"> </td>
                     
                        <td> First name:<br> <input type="text" name="top4title" id="fnamesearch"> </td>
                     
                         <td>Email:<br> <input type="text" name="top4title" id="emailsearch"> </td>
                 
                        <td>Phone #'s <br> <input type="text" name="top4title" id="phonesearch"> </td>
                 
                        <td> <?php allspecalities()?> </td>
                    
                         <td>Licensed:<br> <select name="states" id="statesearch">
                                <option value="">-Select State-</option>
                                <option value="CC">Compact</option>
                                <option value="AK">AK</option>
                                <option value="AL">AL</option>
                                <option value="AR">AR</option>
                                <option value="AZ">AZ</option>
                                <option value="CA">CA</option>
                                <option value="CO">CO</option>
                                <option value="CT">CT</option>
                                <option value="DC">DC</option>
                                <option value="DE">DE</option>
                                <option value="FL">FL</option>
                                <option value="GA">GA</option>
                                <option value="HI">HI</option>
                                <option value="IA">IA</option>
                                <option value="ID">ID</option>
                                <option value="IL">IL</option>
                                <option value="IN">IN</option>
                                <option value="KS">KS</option>
                                <option value="KY">KY</option>
                                <option value="LA">LA</option>
                                <option value="MA">MA</option>
                                <option value="MD">MD</option>
                                <option value="ME">ME</option>
                                <option value="MI">MI</option>
                                <option value="MN">MN</option>
                                <option value="MO">MO</option>
                                <option value="MS">MS</option>
                                <option value="MT">MT</option>
                                <option value="NC">NC</option>
                                <option value="ND">ND</option>
                                <option value="NE">NE</option>
                                <option value="NH">NH</option>
                                <option value="NJ">NJ</option>
                                <option value="NM">NM</option>
                                <option value="NV">NV</option>
                                <option value="NY">NY</option>
                                <option value="OH">OH</option>
                                <option value="OK">OK</option>
                                <option value="OR">OR</option>
                                <option value="PA">PA</option>
                                <option value="PR">PR</option>
                                <option value="RI">RI</option>
                                <option value="SC">SC</option>
                                <option value="SD">SD</option>
                                <option value="TN">TN</option>
                                <option value="TX">TX</option>
                                <option value="UT">UT</option>
                                <option value="VA">VA</option>
                                <option value="VT">VT</option>
                                <option value="WA">WA</option>
                                <option value="WI">WI</option>
                                <option value="WV">WV</option>
                                <option value="WY">WY</option>
                            </select></td>
                     
                        <td>Type <br><select name="rntypesearch" id="rntypesearch" >
                            
                            <option value=" "> -select type- </option> 
                		  <option value="tl"> Travel assignment </option>
                		  <option value="pm"> Perm placement </option>
                		  <option value="pd"> Per Diem </option>
             			</select> 
                        <td><br> <input type="submit" name="lookatrns" id="lookatrns" onClick="lookat()"></td>
                    
                    </tr>
                </table>
        
        <br>
        
            <span id="thesearch"> </span>
            <div style="border-bottom: solid;"> </div>
            
        <span id="searchrn"> </span>
        
        
    </body>
         </div>
</html>    