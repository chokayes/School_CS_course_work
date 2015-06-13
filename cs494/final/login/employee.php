<?php
        session_start(); 
        ini_set('display_errors', 'On');
        
        //allows user to log out
        if (isset($_POST["signout"])){
            //remove all session variables
            session_unset(); 
            //sends user to log in page
            header('Location: login.php');
        }

       //redirects user to login page if not logged in
        if(!isset($_SESSION['loggedin'])) {
            header("location: login.php");  
        }
        //allows me to print out users name at anytime
        $name = $_SESSION['name']; 
        
        include 'printnew4.php';

    

?>   

<!DOCTYPE html>
<html>
	<head>
    	<meta charset="utf-8">
    	<title>Employee homescreen</title>
    	<link rel="stylesheet" type="text/css" href="../nurse.css">
        <script>
//_________________________________________________________________________________________              
            function top4replace()
            {
                // this get the new information to put on index page top jobs
                
                var toptitle = document.getElementById("top4title").value; 
                var topstate= document.getElementById("top4state").value; 
                var topcity= document.getElementById("top4city").value; 
                var toptype= document.getElementById("typeoftop4").value; 
                var topnumber= document.getElementById("swapjobnumber").value; 
                
                //error check
                //document.getElementById('printtop4').innerHTML = toptitle+" "+topstate+" "+topcity+" "+toptype+" "+topnumber;
                
                 //create or XMLHttpRequest object
			     var hr= new XMLHttpRequest(); 
			
			     //create varible that gives adress we need to send out phh file to
			     var url = "add.php"; 
                
                // send these to th php page
                 var vars="add=top&title="+ toptitle+"&state="+topstate+"&topcity="+topcity+"&toptype="+toptype+"&topnumber="+topnumber; 
               
                
                // sending info to php
                hr.open("POST", url, true); 
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
			     hr.onreadystatechange = function() 
			     {
				    if(hr.readyState== 4 && hr.status ==200)
				    {
                        //this holds th etext returned from php file
					   var return_data = hr.responseText; 
                                                             
                                //return that the nurse was submitted 
                                document.getElementById('printtop4').innerHTML = return_data;
                    }
                  }
               
                     hr.send(vars); 
			         document.getElementById('printtop4').innerHTML= "loading ... ";     
            }        
//_________________________________________________________________________________________ 
            
            //this function is used to collect and add to database or inform user we need               more information
            function addrn()
            {
			     //create or XMLHttpRequest object
			     var hr= new XMLHttpRequest(); 
			
			     //create varible that gives adress we need to send out phh file to
			     var url = "add.php"; 
			
                //gets primary special, title, first, last namem cell, home, email, adress,                 city, and state
                var s1 = document.getElementById("dspecialityone").value;    
                 var title= document.getElementById("title").value; 
			     var fname= document.getElementById("fname").value; 
                 var lname= document.getElementById("lname").value; 
                var cell= document.getElementById("cell").value; 
                 var home= document.getElementById("home").value; 
                 var email= document.getElementById("email").value; 
                 var adress= document.getElementById("adress").value; 
                 var city= document.getElementById("city").value; 
                var state= document.getElementById("state").value; 
                
                //this collects the  states individual is licensed in 
                var lstate= document.getElementById("lstate[]").options; 
                var license= "";  
                // I did this to create my own type of array
                for( var i=0; i<lstate.length; i++)
                {
                    if (lstate[i].selected){
                        //speerates states by "|"
                        license+= lstate[i].value + "|";     
                    }
                }
                
                //sames as lstate
                var shifts= document.getElementById("shift[]").options;
                var shift="";  
                for( i=0; i< shifts.length; i++)
                {
                    if (shifts[i].selected){
                        shift += shifts[i].value + "|";
                    }
                }
                
                //same as lstate
                var hours= document.getElementById("hours[]").options;
                var hour="";  
                for( i=0; i< hours.length; i++)
                {
                    if (hours[i].selected){
                        hour += hours[i].value + "|";
                    }
                }
                
                //same as lstate
                var typeofwork= document.getElementById("typeofwork[]").options;
                var typework="";  
                for( i=0; i< typeofwork.length; i++)
                {
                    if (typeofwork[i].selected){
                        typework += typeofwork[i].value + "|";
                    }
                }
                
                //same as lstate
                var s2= document.getElementById("float[]").options;
                var float="";  
                for( i=0; i<s2.length; i++)
                {
                    if (s2[i].selected){
                        float += s2[i].value + "|";
                    }
                }
                
                //same as lstate
                var desiredstates= document.getElementById("desiredstates[]").options;
                var dstate="";  
                for( i=0; i< desiredstates.length; i++)
                {
                    if (desiredstates[i].selected){
                        dstate += desiredstates[i].value + "|";
                    }
                }
                
                //created a string that I can pass as a post from js to php
                 var vars="add=nurse&title="+ title+"&fname="+fname+"&lname="+lname+"&cell="+cell+"&home="+home+"&email="+email+"&adress="+adress+"&city="+city+"&state="+state+"&license="+license+"&shift="+shift+"&hour="+hour+"&typeofwork="+typework+"&s1="+s1+"&s2="+float+"&dstate="+dstate; 
               
                
                // sending info to php
                hr.open("POST", url, true); 
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
			     hr.onreadystatechange = function() 
			     {
				    if(hr.readyState== 4 && hr.status ==200)
				    {
                        //this holds th etext returned from php file
					   var return_data = hr.responseText; 
                        
                        //object holder
                        var parsedData;
                        //error holder 
                        var error=""; 
                        
                        //I am chekcing to see if php sent back a javascript object if it                              does I know that there were errors if it doesnt I know that all required information was recieved with no errors see "add.php"
                        try {
                                //try to creat javascript object
                                parsedData = JSON.parse(return_data);
                                
                              //check to see if key exist if it does error
                                if("fname" in parsedData) 
                                {
                                    //console.log("user did not input first name"); 
                                    error += parsedData["fname"]+"<br>";   
                                }
                                if("fnamelength" in parsedData) 
                                {
                                    //console.log("first name too long"); 
                                    error += parsedData["fnamelength"]+"<br>";
                                }
                                if("lname" in parsedData) 
                                {
                                    //console.log("user did not input last name"); 
                                     error += parsedData["lname"] +"<br>";
                                }
                                if("lnamelength" in parsedData) 
                                {
                                    //console.log("last name too long"); 
                                     error += parsedData["lnamelength"]+"<br>";
                                }
                                if("license" in parsedData) 
                                {
                                    //console.log("user did not input license ");
                                     error += parsedData["license"]+"<br>";
                                }
                                if("rnspecality" in parsedData) 
                                {
                                    //console.log("user did not input rn specality");
                                     error += parsedData["rnspecality"]+"<br>";
                                }
                                if("celllength" in parsedData) 
                                {
                                    //console.log("invalid cell phone number");
                                     error += parsedData["celllength"]+"<br>";
                                }
                                if("cell" in parsedData) 
                                {
                                    //console.log("cell phone number too long");
                                     error += parsedData["cell"]+"<br>";
                                }
                                if("homelength" in parsedData) 
                                {
                                    //console.log("email no @ or .");
                                     error += parsedData["homelength"]+"<br>";
                                }
                            
                                if("home" in parsedData) 
                                { 
                                    //console.log("home # too long"); 
                                     error += parsedData["home"]+"<br>";
                                }
                                if("emaillength" in parsedData) 
                                {
                                    //console.log("email no @ or .");
                                     error += parsedData["emaillength"]+"<br>";
                                }
                                if("email" in parsedData) 
                                {
                                    //console.log("email no @ or .");
                                     error += parsedData["email"]+"<br>";
                                }
                                if("adresslength" in parsedData) 
                                {
                                    //console.log("adress too long");
                                     error += parsedData["adresslength"]+"<br>";
                                }
                                if("citylength" in parsedData) 
                                {
                                    //console.log("city too long");
                                     error += parsedData["citylength"]+"<br>";
                                }
                                if("city" in parsedData) 
                                { 
                                    //console.log("city only contain letters"); 
                                     error += parsedData["city"]+"<br>";
                                }
                                   
                                // this puts all errors on page to be correct
                                document.getElementById('returnit').innerHTML =error;
                            }   
                        catch (e) 
                            {
                                // is not a valid JSON string so no errors
                                
                              var erase =["title", "fname", "lname",  "cell",  "home" , "email", "adress" ,"state","city"] 
                              var erase2 =["lstate[]", "shift[]", "hours[]",  "typeofwork[]",  "desiredstates[]" , "float[]"] 
                            var i;     
                              for (i= 0; i< erase.length; i++) 
                              {
                                    document.getElementById(erase[i]).value = "";
                              }
                               
                                for (i= 0; i< erase2.length; i++) 
                              {
                                    removearrayOptions(document.getElementById(erase2[i]))
                              }
                                
                                
                                //return that the nurse was submitted 
                                document.getElementById('returnit').innerHTML = return_data;
                            }
                    }
                }
               
                hr.send(vars); 
			     document.getElementById("returnit").innerHTML= "loading ... ";     
            }        
            //http://stackoverflow.com/questions/3364493/how-do-i-clear-all-options-in-a-dropdown-box
//_________________________________________________________________________________________            
            function removearrayOptions(selectbox)
            {
                //used to remove when the box can have multiples sselected
                selectbox = selectbox.options; 
                
                var i;
                for(i=selectbox.length-1;i>=0;i--)
                {
                    selectbox[i].selected = false;
                }
            }
            
            
            //created to help me determine if i should collect salary or hourly
            var time =""; 
 //_________________________________________________________________________________________                       
            //this shows hourly if clicked
            function hourly()
            { 
                document.getElementById("jobclock").innerHTML= "<br><br>hourly:                                             <input type='number'step='0.01'  name='jpayhour' id='jpayhour' min='0'>"; 
                
                time="hourly";
            }
 //_________________________________________________________________________________________                       
            //this shows salary is clicked 
            function salary()
            {
                document.getElementById("jobclock").innerHTML="<br><br>Salary:                                              <input type='number'step='0.01'  name='jpaysalary' id='jpaysalary' min='0'>"; 
                time= "salary"; 
            }
//_________________________________________________________________________________________                        
            // this shows salary if clicked
            function getweeksend()
            {
                document.getElementById("jtypeofemployeement").innerHTML="You selected Travel"; 
                
                 document.getElementById("weeksandend").innerHTML="ending day: <br> <input type='date'                            name='enddate' id='enddate'><br> number of weeks: <br>                                                    <input type='number'  name='weeks' id='weeks' min='0'><br>"; 
            }
  //_________________________________________________________________________________________                      
            //show per diem was selected
             function selectedperdiem()
            {
                document.getElementById("jtypeofemployeement").innerHTML="You selected Per diem"; 
                 document.getElementById("weeksandend").innerHTML=""; 
            } //_________________________________________________________________________________________                       
            //shows perm was selected
             function selectedperm()
            {
                document.getElementById("jtypeofemployeement").innerHTML="You selected Perm"; 
                 document.getElementById("weeksandend").innerHTML=""; 
            }
//_________________________________________________________________________________________                        
            //allow you to enter contact name , contact number and cont email for department
            function dcontact()
            {
                document.getElementById("inputdcontact").innerHTML="<br>contact name: <br> <input type='text' name='dcname' id='dcname'><br>contact number:<br> <input type='tel' name='dcnumber' id='dcnumber'><br>contact email:<br> <input type='email' name='dcemail' id='dcemail'><br>";
            }
 //_________________________________________________________________________________________                       
             //allow you to enter contact name , contact number and cont email for department
             function fcontact()
            {
                document.getElementById("inputfcontact").innerHTML="<br>contact name: <br> <input type='text' name='fcname' id='fcname'><br>contact number:<br> <input type='tel' name='fcnumber' id='fcnumber'><br>contact email:<br> <input type='email' name='dcemail' id='fcemail'><br>";
            }
 //_________________________________________________________________________________________                       
             //allow you to enteruser name and password for associaion
            function alogin()
            {
                document.getElementById("inputalogin").innerHTML="<br>Username: <br> <input type='text' name='ausername' id='ausername'><br>password:<br> <input type='text' name='auserpass' id='auserpass'><br>"; 
            }
 
//_________________________________________________________________________________________                        
             //allow you to enter contact name , contact number and cont email for association
            function acontact()
            {
                document.getElementById("inputacontact").innerHTML="<br>contact name: <br> <input type='text' name='acname' id='acname'><br>contact number:<br> <input type='tel' name='acnumber' id='acnumber'><br>contact email:<br> <input type='email' name='acemail' id='acemail'><br>"; 
            }
  //_________________________________________________________________________________________                      
             //allow you to enter association information
            function association()
            {
                document.getElementById("associationinput").innerHTML="<br>Association name:<br> <input type='text' name='aname' id='aname'><br>Association website:<br> <input type='url' name='awebsite' id='awebsite'> <br><p>click if website requires login</p><input type='submit'id='alogin' value='add log in info' onClick='alogin()'> <br><span id='inputalogin'> </span> <p>Click to input association rep contact information</p> <input type='submit'id='arep' value='add association contact' onClick='acontact()'><br><span id='inputacontact'> </span><br>"; 
            }
            
//_________________________________________________________________________________________            
            //shows all of rns user has input
            function showrn(x)
            {            
                if (x ==0)
                {
// http://stackoverflow.com/questions/21468507/how-to-use-javascript-to-remove-html-button
                    myBtn = document.getElementById("showrns"),
                    mySpan = document.createElement("span");
                    mySpan.innerHTML = myBtn.innerHTML ;
                    myBtn.parentNode.replaceChild(mySpan, myBtn);
                }        
                
                document.getElementById("update").innerHTML="<br><input type='submit' name='updaterns' id='updatens' value='refresh list' onClick='showrn(1)'<br><input type='submit' name='editlist' id='editlist' value='edit list' onClick='showrn(3)'>"; 
                
        
                //create or XMLHttpRequest object
			     var hr= new XMLHttpRequest(); 
			
			     //create varible that gives adress we need to send out phh file to
			     var url = "showedit.php"; 
                
                if (x==0 || x == 1)
                {
                    var vars="show=listrn"; 
                    
                    if (x==1)
                    {
                        document.getElementById("rnedit").innerHTML="";  
                    }
                }
                else if(x== 3) 
                {
                    var vars="show=editrn"; 
                }
                    
                 hr.open("POST", url, true); 
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
			     hr.onreadystatechange = function() 
			     {
				    if(hr.readyState== 4 && hr.status ==200)
				    {
					   var return_data = hr.responseText; 
                        
                        document.getElementById("rnlist").innerHTML=return_data; 
                    }         
                 }
            
                hr.send(vars); 
			    document.getElementById("rnlist").innerHTML="...loading";  
    
            
            }
//_________________________________________________________________________________________
            
            function editrnlist(x)
            {
                // information gathered to edit the rn
                
                 //error check to see if things were passing correctly
                    console.log(x);
                    var fname= document.getElementById(x+"fname").value; 
                     //console.log("first name");
                    var lname= document.getElementById(x+"lname").value;
                     //console.log("last  name");
                    var cell= document.getElementById(x+"cell").value;
                     //console.log("cell");
                    var home= document.getElementById(x+"home").value;
                     //console.log("home");
                    var specality= document.getElementById(x+"specality").value;
                     //console.log(specality);
                    var email= document.getElementById(x+"email").value;
                     //console.log("email");
                    var state= document.getElementById(x+"state").value;
                     //console.log("state");
                     var city= document.getElementById(x+"city").value;
                     //console.log("city");
                     var adress= document.getElementById(x+"adress").value;
                     //console.log("adress");
                    var title= document.getElementById(x+"title").value; 
                
                //pop up box to make sure user want to edit the database
                var check = confirm("Are you sure you want to update the database");  
                
                if (check == false)
                {
                     document.getElementById("rnedit").innerHTML= fname+" "+lname+" was not  not edited in the database?"
                }
                else
                {
                      var vars="changeit=rn&fname="+ fname+"&lname="+lname+"&cell="+cell+"&home="+home+"&email="+email+"&adress="+adress+"&city="+city+"&state="+state+"&specality="+specality+"&adress="+adress+"&hcpid="+x+"&title="+title; 

                      //errork check display values
                   //console.log(vars); 

                     //create or XMLHttpRequest object
                     var hr= new XMLHttpRequest(); 

                     //create varible that gives adress we need to send out phh file to
                     var url = "changedit.php"; 


                     hr.open("POST", url, true); 
                    hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 

                     hr.onreadystatechange = function() 
                     {
                        if(hr.readyState== 4 && hr.status ==200)
                        {
                           var return_data = hr.responseText; 

                            document.getElementById("rnedit").innerHTML=return_data; 
                        }         
                     }

                    hr.send(vars); 
                    document.getElementById("rnedit").innerHTML="...loading";  
    
                }
            }    
//_________________________________________________________________________________________
            
            
            function deletelist(x)
            {
                //deletes someoen out of the database
                 var fname= document.getElementById(x+"fname").value; 
                 //console.log("first name");
                var lname= document.getElementById(x+"lname").value;
                
                
                var check = confirm("Are you sure you want to delete "+fname+" "+lname+" from the database?");  
                
                if (check == false)
                {
                     document.getElementById("rnedit").innerHTML= fname+" "+lname+" was not deleted from the database?"
                }
                else
                {
                    
                    var vars="changeit=deletern&hcpid="+x+"&fname="+fname+"&lname="+lname; 
                
                    //create or XMLHttpRequest object
			         var hr= new XMLHttpRequest(); 
			
			         //create varible that gives adress we need to send out phh file to
			         var url = "changedit.php"; 
            
                    hr.open("POST", url, true); 
                    hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
                    hr.onreadystatechange = function() 
			         {
				        if(hr.readyState== 4 && hr.status ==200)
				        {
					       var return_data = hr.responseText; 
                        
                            document.getElementById("rnedit").innerHTML=return_data; 
                        }         
                      } 
            
                      hr.send(vars); 
			          document.getElementById("rnedit").innerHTML="...loading";      
                    }
            }
            
//_________________________________________________________________________________________
            
            
            //allows you to add a job
            function addjob()
            {
                //create or XMLHttpRequest object
			     var hr= new XMLHttpRequest(); 
			     //create varible that we need to send out phh file]
			     var url = "add.php";
                
			     //information on open job
                var position= document.getElementById("jposition").value; 
                
                
                var hourly= ""; 
                var salary= ""; 
                //add pay
              
                     if( time == "hourly") 
                     {
                       hourly= document.getElementById("jpayhour").value;
                     }
                     else if( time == "salary")
                        {
                          salary= document.getElementById("jpaysalary").value;
                        }
                    time= ""; 
                 
                var  typeofemployement="";
               
                //gets employeement type form job
                if (document.getElementById("jtypeofemployeement").innerHTML == "You selected Travel")
                {
                   typeofemployement="travel"; 
                }
                else if(document.getElementById("jtypeofemployeement").innerHTML == "You selected Per diem")
                {
                    typeofemployement="Perdiem"; 
                }
                else if(document.getElementById("jtypeofemployeement").innerHTML == "You selected Perm")
                {
                    typeofemployement="Perm";
                }
                else if(document.getElementById("jtypeofemployeement").innerHTML == "")
                {
                    //will have to work on
                    typeofemployement="ERROR";
                }
                
                //gets the shift of job
                var shift= document.getElementById("shiftofemployeement").value; 
                
                //gets the start date of job
                var startdate= document.getElementById("startdate").value;  
                
               var enddate=""; 
                var numbofweeks=""; 
                if (typeofemployement=="travel")
                {
                    enddate= document.getElementById("enddate").value;  
                    numbofweeks= document.getElementById("weeks").value;  
                }
                
                var hoursperweek= document.getElementById("hoursaweek").value; 
                var openings= document.getElementById("quanity").value; 
                
          //      //information on department
                var dname= document.getElementById("dname").value;
                
                
                var dcname=""; 
                var dcnumber=""; 
                var dcemail=""; 
                
                if( document.getElementById("inputdcontact").innerHTML.length>1)
                {
                     dcname= document.getElementById("dcname").value;
                     dcnumber= document.getElementById("dcnumber").value;
                     dcemail= document.getElementById("dcemail").value;
                }
                
                //information on facility
                var fname= document.getElementById("fname").value;
                var fstate= document.getElementById("fstate").value;
                var fcity= document.getElementById("fcity").value;
                var fwebsite= document.getElementById("fwebsite").value;
                var fnumber= document.getElementById("fnumber").value;
                
                var fcname=""; 
                var fcnumber=""; 
                var fcemail=""; 
                
                 if( document.getElementById("inputfcontact").innerHTML.length>1)
                 {
                      fcname= document.getElementById("fcname").value;
                      fcnumber= document.getElementById("fcnumber").value;
                      fcemail= document.getElementById("fcemail").value;
                 }
                
                //these are not always assignned so I had to create them here
                var aname=""; 
                var awebsite=""; 
                var ausername="";
                var apass=""; 
                var acname=""; 
                var acnumber="";
                var acemail=""; 
                
                // information on association
                if( document.getElementById("associationinput").innerHTML.length>1)
                 {
                      aname= document.getElementById("aname").value;
                      awebsite= document.getElementById("awebsite").value;
                     
                    if( document.getElementById('inputalogin').innerHTML.length>1)
                    {
                         ausername=  document.getElementById("ausername").value;
                         apass= document.getElementById("auserpass").value;
                    }
                     
                    if( document.getElementById('inputacontact').innerHTML.length>1)
                    {
                         acname= document.getElementById("acname").value;
                         acnumber= document.getElementById("acnumber").value;
                         acemail= document.getElementById("acemail").value;
                    }    
                 }
                    
                 var vars="add=job&hourly="+hourly+"&salary="+salary+"&position="+ position+"&typeofemployeement="+typeofemployement+"&shift="+shift+"&startdate="+startdate+"&enddate="+enddate+"&numberofweeks="+numbofweeks+"&hoursperweek="+hoursperweek+"&openings="+openings+"&dname="+dname+"&dcname="+dcname+"&dcnumber="+dcnumber+"&dcemail="+dcemail+"&fname="+fname+"&fstate="+fstate+"&fcity="+fcity+"&fwebsite="+fwebsite+"&fnumber="+fnumber+"&fcname="+fcname+"&fcnumber="+fcnumber+"&fcemail="+fcemail+"&aname="+aname+"&awebsite="+awebsite+"&ausername="+ausername+"&apass="+apass+"&acname="+acname+"&acnumber="+acnumber+"&acemail="+acemail;  
                
                hr.open("POST", url, true); 
                hr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
		
			     hr.onreadystatechange = function() 
			     {
				    if(hr.readyState== 4 && hr.status ==200)
				    {
					   var return_data = hr.responseText; 
                       // document.getElementById('jreturnit').innerHTML = return_data;
                        
                          //object holder
                        var parsedData;
                        //error holder 
                        var error=""; 
                        
                        //I am chekcing to see if php sent back a javascript object if it                                          does I know that there were errors if it doesnt I know that all required                                 information was recieved with no errors see "add.php"
                        try {
                                //try to creat javascript object
                                parsedData = JSON.parse(return_data);
                                
                                //error check send error to console 
                                //console.log(return_data);
                                
                         //   -fname, -fstate, -fcity, -dname, -position, 
                            
                                //check to see if key exist if it does error
                                if("fname" in parsedData) 
                                {
                                    //console.log("user did not input first name"); 
                                    error += parsedData["fname"]+"<br>";   
                                }
                                if("fstate" in parsedData) 
                                {
                                    //console.log("first name too long"); 
                                    error += parsedData["fstate"]+"<br>";
                                }
                                if("fcity" in parsedData) 
                                {
                                    //console.log("user did not input last name"); 
                                     error += parsedData["fcity"] +"<br>";
                                }
                                if("dname" in parsedData) 
                                {
                                    //console.log("last name too long"); 
                                     error += parsedData["dname"]+"<br>";
                                }
                                if("position" in parsedData) 
                                {
                                    //console.log("user did not input license ");
                                     error += parsedData["position"]+"<br>";
                                }
                                if("fcitylength" in parsedData) 
                                {
                                    //console.log("user did not input rn specality");
                                     error += parsedData["fcitylength"]+"<br>";
                                }
                                if("fnamelength" in parsedData) 
                                {
                                    //console.log("invalid cell phone number");
                                     error += parsedData["fnamelength"]+"<br>";
                                }
                                if("positionlength" in parsedData) 
                                {
                                    //console.log("cell phone number too long");
                                     error += parsedData["positionlength"]+"<br>";
                                }
                                if("dnamelength" in parsedData) 
                                { 
                                    //console.log("home # too long"); 
                                     error += parsedData["dnamelength"]+"<br>";
                                }
                               
                                // this puts all error son page to be correct
                                 document.getElementById("jreturnit").innerHTML=error;
                            }   
                        catch (e) 
                            {
                                // is not a valid JSON string so no errors
                                        
                                //return that the nurse was submitted 
                                document.getElementById('jreturnit').innerHTML = " not working<br>";
                            }
                        
                         
                    }
                }
                
                hr.send(vars); 
			    document.getElementById("jreturnit").innerHTML="...loading";    
            }
        </script>
	</head>
    <div class ="jello"> 
    <body> 
        
        <table style="margin-left:auto; margin-right:auto" >
            <tr>
                <td>
                    <h1 style="text-align:center; color:blue"> Welcome to                          the Employee page <?php echo $name; ?> &nbsp;</h1>
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
        
       
        <!---used to show login info-->
        <?php //var_dump($_SESSION); ?>
        
        
        <!---used to show links to other page-->
        <nav style="background-color: white; border-color:blue; color:green; " >
   	        <ul>
	  	        <li><a href="employee.php">Home |</a></li>
	  	        <li><a href="rn.php">RN |</a></li>
	  	        <li><a href="jobs.php">Jobs</a></li>      
            </ul>    
        </nav>
        <br>
        
        <div id="top4jobs">
            
            <h1 style="color:white"> Change Top 4 Jobs</h1>
            
            
            <table id="changejobs">
            	<tr>
            		<td>Title:</td>
                    <td>State:</td>
                    <td>City</td>
                    <td>Type:</td>   
               </tr>
               <tr>
               		<td> 
                        <input type="text" name="top4title" id	="top4title">    
                   </td>
                    <td>  
                        <select name="top4state" id="top4state">
                            <option value="">-Select State-</option>
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
            </select>
                        
                    </td> 
                   <td><input type="text" name="top4title" id="top4city"> </td> 
                    <td>
                       <select name="typeoftop4" id="typeoftop4" >
                		  <option value="travel"> Travel assignment </option>
                		  <option value="perm"> Perm placement </option>
                		  <option value="pd"> Per Diem </option>
             			</select> 
                    </td>
                   <td>
                        <select name="swapjobnumber" id="swapjobnumber" >
                            <option value="1"> 1 </option>
                            <option value="2"> 2</option>
                            <option value="3"> 3</option> 
                            <option value="4"> 4</option> 
                        </select> 
                    </td>
                    <td><input type='submit' name='addto4' id='addto4' 
                    value="Replace Top 4 Job" onClick='top4replace()'>  
                    </td>
            	</tr>
            </table> 
            <br> 
            
                  <?php printtop4(); ?>
            <span id="printtop4"> </span> 
            
        </div>
        
         <div id="userrn" style="float:right">
                <h1>Print out all of <?php echo $name ?>'s RN's</h1>

                <br>
                <span id="rnlist"> </span>
                <span id="rnedit"></span>

                <input type='submit' name='showrns' id='showrns' 
                        value='show and edit users rns' onClick='showrn(0)'>

               <span id="update"> </span>
            </div>
     
            <div id="inputrn" style="display:inline-block">
                <!-- allows recuriter to input new rn inforation--> 
                <h1 style="color: white; text-align:center;">Insert a New RN</h1>

                <span id="returnit"> </span>



                <table  style="margin-left:auto; margin-right:auto">
                    <tr>
                        <td>
                            <select name="title" id="title">
                                <option value="">-Select title-</option>
                                <option value="Mr">Mr.</option>
                                <option value="Mrs">Mrs.</option>
                                <option value="Miss">Miss.</option>
                                <option value="Ms">Ms.</option>
                            </select>
                            &nbsp; &nbsp;  &nbsp; 
                        </td>
                        <td>
                             <select name="dspecialityone" id="dspecialityone">
                                <option value="">-RN Speciality-</option>
                                <option value="Acute Rehab">
                                    Acute Rehab</option>
                                <option value="Admissions Coordinator">
                                    Admissions Coordinator</option>
                                <option value="Ambulatory Care">
                                    Ambulatory Care</option>
                                <option value="Burn Unit">Burn Unit</option>
                                <option value="Cardiac">Cardiac</option>
                                <option value="Cardiac Tele">Cardiac Tele</option>
                                <option value=
                                    "Cardiovascular Pulmonary"
                                        >Cardiovascular Pulmonary</option>
                                <option value="Case Manager">Case Manager</option>
                                <option value="Cath Lab">Cath Lab</option>
                                <option value="Clinic">Clinic</option>
                                <option value="Clinical Analyst">
                                    Clinical Analyst</option>
                                <option value="Clinical Director">
                                    Clinical Director</option>
                                <option value="Clinical Instructor">
                                    Clinical Instructor</option>
                                <option value="Coronary Care">
                                    Coronary Care</option>
                                <option value="Corrections">Corrections</option>
                                <option value="CTICU">CTICU</option>
                                <option value="CVICU">CVICU</option>
                                <option value="CVOR">CVOR</option>
                                <option value="Definitive Observation Unit">Definitive Observation Unit</option>
                                <option value="Dermatology">Dermatology</option>
                                <option value="Dialysis">Dialysis</option>
                                <option value="DON">DON</option>
                                <option value="Electrophysiology">Electrophysiology</option>
                                <option value="EMR Conversion">EMR Conversion</option>
                                <option value="Endoscopy">Endoscopy</option>
                                <option value="ER">ER</option>
                                <option value="ER I">ER I</option>
                                <option value="ER II">ER II</option>
                                <option value="ER III">ER III</option>
                                <option value="ER IV">ER IV</option>
                                <option value="Flight Nurse">Flight Nurse</option>
                                <option value="Float">Float</option>
                                <option value="Geriatrics">Geriatrics</option>
                                <option value="GI Lab">GI Lab</option>
                                <option value="Home Health">Home Health</option>
                                <option value="Hospice">Hospice</option>
                                <option value="House Supervisor">House Supervisor</option>
                                <option value="ICU">ICU</option>
                                <option value="Labor and Delivery">Labor and Delivery</option>
                                <option value="LTC">LTC</option>
                                <option value="Maternal/Infant">Maternal/Infant</option>
                                <option value="MDS">MDS</option>
                                <option value="Med Surg">Med Surg</option>
                                <option value="Midwife">Midwife</option>
                                <option value="Mother/Baby">Mother/Baby</option>
                                <option value="Neonatal">Neonatal</option>
                                <option value="Neuro">Neuro</option>
                                <option value="NICU">NICU</option>
                                <option value="Nurse Manager">Nurse Manager</option>
                                <option value="Nursery">Nursery</option>
                                <option value="OB">OB</option>
                                <option value="OB/GYN">OB/GYN</option>
                                <option value="Observation Unit">Observation Unit</option>
                                <option value="Occupational Health">Occupational Health</option>
                                <option value="Occupational Medicine">Occupational Medicine</option>
                                <option value="On-Call">On-Call</option>
                                <option value="Oncology">Oncology</option>
                                <option value="OR">OR</option>
                                <option value="OR TECH">OR TECH</option>
                                <option value="Ortho">Ortho</option>
                                <option value="PACU">PACU</option>
                                <option value="Pain Management">Pain Management</option>
                                <option value="PCU">PCU</option>
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Perinatal">Perinatal</option>
                                <option value="PICU">PICU</option>
                                <option value="Postpartum">Postpartum</option>
                                <option value="Pre-op">Pre-op</option>
                                <option value="Psych">Psych</option>
                                <option value="Radiology">Radiology</option>
                                <option value="Rehab">Rehab</option>
                                <option value="RNFA">RNFA</option>
                                <option value="Same Day Surgery">Same Day Surgery</option>
                                <option value="School">School</option>
                                <option value="Scrub">Scrub</option>
                                <option value="SNF">SNF</option>
                                <option value="Step-Down">Step-Down</option>
                                <option value="Stepdown">Stepdown</option>
                                <option value="Surgery">Surgery</option>
                                <option value="Surgical ICU">Surgical ICU</option>
                                <option value="Tele">Tele</option>
                                <option value="Trauma">Trauma</option>
                                <option value="Urgent Care">Urgent Care</option>
                                <option value="Utilization Review">Utilization Review</option>
                                <option value="Womens Health">Womens Health</option>
                                <option value="Wound Care">Wound Care</option>
                            </select><span id="positionrequired"> <font color="red">*</font></span>      
                        </td>
                    </tr>
                </table>
                <br>
                <table style="margin-left:5px;">
                    <tr>
                        <td align="right">First name:</td>
                        <td align="right"><input type="text" name="fname" id="fname"> 
                            <span id="firstnamerequired"> <font color="red">*</font></span> </td>
                    </tr>
                    <tr>
                        <td align="right">Last name: </td>
                        <td><input type="text" name="lname" id="lname">
                            <span id="lastnamerequired"> <font color="red">*</font></span> </td>
                    </tr>
                    <tr>
                        <td align="right"> E-mail:</td>
                        <td> <input type="email" name="email" id="email">
                            <span id="emailrequired"> <font color="red">*</font></span></td>
                    </tr>
                    <tr>
                        <td align="right">Cell Phone:</td>
                        <td><input type="tel" name="cell" id="cell">
                            <span id="cellequired"> <font color="red">*</font></span> </td>
                    </tr>
                    <tr>
                        <td align="right">Home Phone:</td>
                        <td align="left"><input type="tel" name="home" id="home"> </td>
                    </tr>

                    <tr>
                        <td align="right">Home Adress: </td>
                        <td align="left"><input type="text" name="adress" id="adress"> </td>
                    </tr>
                    <tr>
                        <td align="right">City: </td>
                        <td align="left"><input type="text" name="city" id="city"> </td>
                    </tr>
                    <tr>
                        <td align="right">State: </td>
                        <td> 
                            <select name="state" id="state">
                                <option value="">-Select State-</option>
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
                            </select>
                        </td>
                    </tr>
                </table>


                <table> 
                    <tr>
                        <td> Licensed State's<font color="red">*</font></span></td>
                        <td>&nbsp; </td>
                        <td>Desire Shifts:</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="lstates[]" id="lstate[]" multiple="multiple">
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
                            </select>
                        </td>
                       <td>&nbsp; </td>
                        <td>
                            <select name="shift[]" id="shift[]" multiple>
                                <option value="">-Prefered shifts-</option>
                                <option value="day">day </option>
                                <option value="evening">evening</option>
                                <option value="night"> night </option>
                            </select>
                        </td>
                    </tr>
                    <br> 
                    <tr>
                        <td> Desired shift hours</td>
                        <td>&nbsp;</td>
                        <td>Desired Assignment</td>
                    </tr>
                    <tr>
                        <td> <select name="hours[]" id="hours[]" multiple>
                                <option value="">-Prefered shift hours-</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="12">12</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <select name="typeofwork[]" id="typeofwork[]" multiple>
                                <option value="">-Type of work desired-</option>
                                <option value="travel"> Travel assignment </option>
                                <option value="perm"> Perm placement </option>
                                <option value="pd"> Per Diem </option>
                             </select>
                        </td>   
                    </tr>
                    <tr>
                        <td>Desired States Of Employeement</td>
                        <td></td>
                        <td>Floats and Experience:</td>
                    </tr>
                    <tr>
                        <td>   
                            <select name="desiredstates[]" id="desiredstates[]" multiple>
                                <option value="">-Select State-</option>
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
                            </select>
                        </td>
                        <td> </td>
                        <td>
                            <select name="float[]" id="float[]" multiple="multiple">
                                <option value="">-Select All that apply-</option>
                                <option value="Acute Rehab">Acute Rehab</option>
                                <option value="Admissions Coordinator">Admissions Coordinator</option>
                                <option value="Ambulatory Care">Ambulatory Care</option>
                                <option value="Burn Unit">Burn Unit</option>
                                <option value="Cardiac">Cardiac</option>
                                <option value="Cardiac Tele">Cardiac Tele</option>
                                <option value="Cardiovascular Pulmonary">Cardiovascular Pulmonary</option>
                                <option value="Case Manager">Case Manager</option>
                                <option value="Cath Lab">Cath Lab</option>
                                <option value="Clinic">Clinic</option>
                                <option value="Clinical Analyst">Clinical Analyst</option>
                                <option value="Clinical Director">Clinical Director</option>
                                <option value="Clinical Instructor">Clinical Instructor</option>
                                <option value="Coronary Care">Coronary Care</option>
                                <option value="Corrections">Corrections</option>
                                <option value="CTICU">CTICU</option>
                                <option value="CVICU">CVICU</option>
                                <option value="CVOR">CVOR</option>
                                <option value="Definitive Observation Unit">Definitive Observation Unit</option>
                                <option value="Dermatology">Dermatology</option>
                                <option value="Dialysis">Dialysis</option>
                                <option value="DON">DON</option>
                                <option value="Electrophysiology">Electrophysiology</option>
                                <option value="EMR Conversion">EMR Conversion</option>
                                <option value="Endoscopy">Endoscopy</option>
                                <option value="ER">ER</option>
                                <option value="ER I">ER I</option>
                                <option value="ER II">ER II</option>
                                <option value="ER III">ER III</option>
                                <option value="ER IV">ER IV</option>
                                <option value="Flight Nurse">Flight Nurse</option>
                                <option value="Float">Float</option>
                                <option value="Geriatrics">Geriatrics</option>
                                <option value="GI Lab">GI Lab</option>
                                <option value="Home Health">Home Health</option>
                                <option value="Hospice">Hospice</option>
                                <option value="House Supervisor">House Supervisor</option>
                                <option value="ICU">ICU</option>
                                <option value="Labor and Delivery">Labor and Delivery</option>
                                <option value="LTC">LTC</option>
                                <option value="Maternal/Infant">Maternal/Infant</option>
                                <option value="MDS">MDS</option>
                                <option value="Med Surg">Med Surg</option>
                                <option value="Midwife">Midwife</option>
                                <option value="Mother/Baby">Mother/Baby</option>
                                <option value="Neonatal">Neonatal</option>
                                <option value="Neuro">Neuro</option>
                                <option value="NICU">NICU</option>
                                <option value="Nurse Manager">Nurse Manager</option>
                                <option value="Nursery">Nursery</option>
                                <option value="OB">OB</option>
                                <option value="OB/GYN">OB/GYN</option>
                                <option value="Observation Unit">Observation Unit</option>
                                <option value="Occupational Health">Occupational Health</option>
                                <option value="Occupational Medicine">Occupational Medicine</option>
                                <option value="On-Call">On-Call</option>
                                <option value="Oncology">Oncology</option>
                                <option value="OR">OR</option>
                                <option value="OR TECH">OR TECH</option>
                                <option value="Ortho">Ortho</option>
                                <option value="PACU">PACU</option>
                                <option value="Pain Management">Pain Management</option>
                                <option value="PCU">PCU</option>
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Perinatal">Perinatal</option>
                                <option value="PICU">PICU</option>
                                <option value="Postpartum">Postpartum</option>
                                <option value="Pre-op">Pre-op</option>
                                <option value="Psych">Psych</option>
                                <option value="Radiology">Radiology</option>
                                <option value="Rehab">Rehab</option>
                                <option value="RNFA">RNFA</option>
                                <option value="Same Day Surgery">Same Day Surgery</option>
                                <option value="School">School</option>
                                <option value="Scrub">Scrub</option>
                                <option value="SNF">SNF</option>
                                <option value="Step-Down">Step-Down</option>
                                <option value="Stepdown">Stepdown</option>
                                <option value="Surgery">Surgery</option>
                                <option value="Surgical ICU">Surgical ICU</option>
                                <option value="Tele">Tele</option>
                                <option value="Trauma">Trauma</option>
                                <option value="Urgent Care">Urgent Care</option>
                                <option value="Utilization Review">Utilization Review</option>
                                <option value="Womens Health">Womens Health</option>
                                <option value="Wound Care">Wound Care</option>
                             </select>
                        </td>
                    </tr>
                </table>        
                <br>   

                <div style= "text-align: center"> 
                    <input type='submit' name='insertrn' id='insertrn' 
                        value='Insert New RN' onClick='addrn()'>
                </div>    <br> 
            </div>    
    
    
    
    <p>_________________________________________________<em>Below this Point is Still Under construction</em>____________________________________</p>
    
        <div id="inputjobs">
              <h1>Insert a New Job</h1>
            
              <span id="jreturnit"> </span>
            <br>
            <!--make dynamic--> 
            Rn Position:<br>
                 <select name="jposition" id="jposition">
                	<option value="">-Select Position-</option>
					<option value="Acute Rehab">Acute Rehab</option>
					<option value="Admissions Coordinator">Admissions Coordinator</option>
                    <option value="Ambulatory Care">Ambulatory Care</option>
                    <option value="Burn Unit">Burn Unit</option>
                    <option value="Cardiac">Cardiac</option>
                    <option value="Cardiac Tele">Cardiac Tele</option>
                    <option value="Cardiovascular Pulmonary">Cardiovascular Pulmonary</option>
                    <option value="Case Manager">Case Manager</option>
                    <option value="Cath Lab">Cath Lab</option>
                    <option value="Clinic">Clinic</option>
                    <option value="Clinical Analyst">Clinical Analyst</option>
                    <option value="Clinical Director">Clinical Director</option>
                    <option value="Clinical Instructor">Clinical Instructor</option>
                    <option value="Coronary Care">Coronary Care</option>
                    <option value="Corrections">Corrections</option>
                    <option value="CTICU">CTICU</option>
                    <option value="CVICU">CVICU</option>
                    <option value="CVOR">CVOR</option>
                    <option value="Definitive Observation Unit">Definitive Observation Unit</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Dialysis">Dialysis</option>
                    <option value="DON">DON</option>
                    <option value="Electrophysiology">Electrophysiology</option>
                    <option value="EMR Conversion">EMR Conversion</option>
                    <option value="Endoscopy">Endoscopy</option>
                    <option value="ER">ER</option>
                    <option value="ER I">ER I</option>
                    <option value="ER II">ER II</option>
                    <option value="ER III">ER III</option>
                    <option value="ER IV">ER IV</option>
                    <option value="Flight Nurse">Flight Nurse</option>
                    <option value="Float">Float</option>
                    <option value="Geriatrics">Geriatrics</option>
                    <option value="GI Lab">GI Lab</option>
                    <option value="Home Health">Home Health</option>
                    <option value="Hospice">Hospice</option>
                    <option value="House Supervisor">House Supervisor</option>
                    <option value="ICU">ICU</option>
                    <option value="Labor and Delivery">Labor and Delivery</option>
                    <option value="LTC">LTC</option>
                    <option value="Maternal/Infant">Maternal/Infant</option>
                    <option value="MDS">MDS</option>
                    <option value="Med Surg">Med Surg</option>
                    <option value="Midwife">Midwife</option>
                    <option value="Mother/Baby">Mother/Baby</option>
                    <option value="Neonatal">Neonatal</option>
                    <option value="Neuro">Neuro</option>
                    <option value="NICU">NICU</option>
                    <option value="Nurse Manager">Nurse Manager</option>
                    <option value="Nursery">Nursery</option>
                    <option value="OB">OB</option>
                    <option value="OB/GYN">OB/GYN</option>
                    <option value="Observation Unit">Observation Unit</option>
                    <option value="Occupational Health">Occupational Health</option>
                    <option value="Occupational Medicine">Occupational Medicine</option>
                    <option value="On-Call">On-Call</option>
                    <option value="Oncology">Oncology</option>
                    <option value="OR">OR</option>
                    <option value="OR TECH">OR TECH</option>
                    <option value="Ortho">Ortho</option>
                    <option value="PACU">PACU</option>
                    <option value="Pain Management">Pain Management</option>
                    <option value="PCU">PCU</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Perinatal">Perinatal</option>
                    <option value="PICU">PICU</option>
                    <option value="Postpartum">Postpartum</option>
                    <option value="Pre-op">Pre-op</option>
                    <option value="Psych">Psych</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Rehab">Rehab</option>
                    <option value="RNFA">RNFA</option>
                    <option value="Same Day Surgery">Same Day Surgery</option>
                    <option value="School">School</option>
                    <option value="Scrub">Scrub</option>
                    <option value="SNF">SNF</option>
                    <option value="Step-Down">Step-Down</option>
                  	<option value="Stepdown">Stepdown</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Surgical ICU">Surgical ICU</option>
                    <option value="Tele">Tele</option>
                    <option value="Trauma">Trauma</option>
                    <option value="Urgent Care">Urgent Care</option>
                    <option value="Utilization Review">Utilization Review</option>
                    <option value="Womens Health">Womens Health</option>
                    <option value="Wound Care">Wound Care</option>
                 </select>
            <font color="red">*</font></span>
            
            <p>select hourly or salary</p>
            <input type='submit' name='hourly' id='hourly' value='Insert hourly'
                     	onClick='hourly()'>
            <input type='submit' name='Salary' id='salary' value='Insert salary'
                     	onClick='salary()'>
            
            <!--accepts pay as salary or hourly-->
            <span id="jobclock"> </span> 
            
            <p>Type of Employeemnet:</p> 
                <input type='submit'id='travelemployeement' value='Travel' onClick='getweeksend()'>
                <input type='submit' id='perdiememployeement' value='perdiem' onClick='selectedperdiem()'>
                <input type='submit' id='perdiememployeement' value='perm' onClick='selectedperm()'> <font color="red">*</font></span>
            <span id="jtypeofemployeement"></span>
            
            <br><br>
             Shift:<br>
            <select name="shiftofemployment" id="shiftofemployeement">
                <option value="">-Select shift</option>
                <option value="night"> night</option>
                <option value="day"> day </option>
                <option value="evening">evening </option>
             </select>
             <br>
            Start date:<br> <input type="date" name="startdate" id="startdate"> 
            <br>
            <!-- if travel this is ask for #of weeks and end date-->
            <span id="weeksandend"> </span>
            hours per week:<br> <input type="number" name="hoursaweek" id="hoursaweek" min="1" max="99">
           <br> 
            number of openings:<br> <input type="number" name="quanity" id="quanity" min="1" max="99"> 
            <h1> Information on facility</h1>
            
            <input type='submit' name='association' id='association' value='Click if facility has association' onClick='association()'><br><br>
            <span id="associationinput"></span>
                        
            facility Name:<br> <input type="text" name="dname" id="dname"> 
            <font color="red">*</font></span>
            <br>
             Department of position:<br> <input type="text" name="dname" id="dname"> 
            <br>
            state<br>
            <select name="fstate" id="fstate">
                <option value="">-Select State-</option>
                <option value="compact">Compact</option>
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
            </select>
            <font color="red">*</font>
            <br>
             City:<br> <input type="text" name="fcity" id="fcity">
            <font color="red">*</font><br>
            website:<br> <input type="url" name="fwebsite" id="fwebsite">
            <br>
            Facility phone#:<br> <input type="tel" name="fnumber" id="fnumber">
            <br><br> 
            
             <input type='submit'id='facilitycontact' value='Click to input Facility rep contact information' onClick='fcontact()'>
            <br>
            <span id="inputfcontact"> </span>
            
          <br>
           
             <input type='submit'id='departmentcontact' value='Click to input Department rep contact information' onClick='dcontact()'>
            <br>
            <span id="inputdcontact"> </span>
            
            <br><br> 
       
             <input type='submit' name='insertjob' id='insertjob' 
                    value='Insert New JOB' onClick='addjob()'>
            
        </div>
        
        <div id="myjobs">
              <p>  my jobs</p>
            <p> Ajax used to print out all of users inputs</p>
            
        </div>
        
        
    </body>
</div>
</html>    