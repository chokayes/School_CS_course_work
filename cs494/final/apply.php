<!DOCTYPE html>
	<html>
	<head>
    	<meta charset="utf-8">
    	<title>Work-A-Nurse</title>
    	<link rel="stylesheet" type="text/css" href="nurse.css">
        <script> 
            function homeapply(){
                	var status=  document.getElementById("status"); 
			status.innerHTML ="apply hit"; 
            
                 //create or XMLHttpRequest object
			     var hr= new XMLHttpRequest(); 
			
			     //create varible that gives adress we need to send out phh file to
			     var url = "homeadd.php"; 
			
                var title=  document.getElementById("title").value;
                var specality=  document.getElementById("specality").value;
                var fname=  document.getElementById("fname").value;
                var lname=  document.getElementById("lname").value;
                var email=  document.getElementById("email").value;
                var cell=  document.getElementById("cell").value;
                var home=  document.getElementById("home").value;
                var adress=  document.getElementById("adress").value;
                var city =  document.getElementById("city").value;
                var state=  document.getElementById("state").value;
                
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
                
                 var resume=  document.getElementById("resume").value;
                
                //created a string that I can pass as a post from js to php
                 var vars="title="+ title+"&fname="+fname+"&lname="+lname+"&cell="+cell+"&home="+home+"&email="+email+"&adress="+adress+"&city="+city+"&state="+state+"&license="+license+"&shift="+shift+"&hour="+hour+"&typeofwork="+typework+"&specality="+specality+"&sother="+float+"&dstate="+dstate+"&resume="+resume; 
                    
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
                        
                        //I am chekcing to see if php sent back a javascript object if it does I know that there were errors if it doesnt I know that all required information was recieved with no errors see "add.php"
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
     
                            
                                document.getElementById("status").innerHTML= error;
                            }   
                        catch (e) 
                            {
                                // is not a valid JSON string so no errors
                                
                                    
                              var erase =["title","specality",  "fname", "lname",  "cell",  "home" , "email", "adress" ,"state","city"] 
                              var erase2 =["lstate[]", "shift[]", "hours[]",  "typeofwork[]",  "desiredstates[]" , "float[]"] 
                            var i;     
                              for (i= 0; i< erase.length; i++) 
                              {
                                    document.getElementById(erase[i]).value = "";
                              }
                               
                                document.getElementById("resume").value="";  
                                
                                for (i= 0; i< erase2.length; i++) 
                              {
                                    removearrayOptions(document.getElementById(erase2[i]))
                              }
                                
                        
                                document.getElementById("status").innerHTML= return_data; 
                            }
			
			         }
                 }
			         //send the data to php
			         hr.send(vars); 
			         document.getElementById("status").innerHTML= "loading ... "; 	
                 
            }
            
              function removearrayOptions(selectbox)
            {
                selectbox = selectbox.options; 
                
                var i;
                for(i=selectbox.length-1;i>=0;i--)
                {
                    selectbox[i].selected = false;
                }
            }
            
            
        </script>
        
	</head> 
	<!-- border for all of website -->
    <div class="jello" style="position:relative";>
    <body> 
<header id="header">     
             <!-- used to lower the nurse image--> 
            
            	<div style="height: 1em;  top:2em; position: relative;
                 	background: white;"></div>
            
				<!-- where I found image --><!--http://genkigoth.deviantart.com/art/Stop-Nurse-at-work-here-342168545 -->
                <!-- nurse  logo below --> 
	    		<a href="index.php"><img id="nurselogo" 
        			src="pictures/mynurselogosmall.png"  
        			alt="Work-A-Nurse logo" width="328" height="247" /></a>
        
        		<!-- 1800 number( decided to do an image -->  
        		<img src="pictures/phonenumber.png" width="261" height="45"
            		alt="1800wenurse"/>
                
                <!-- click to apply button-->  
        		<a href="apply.php"><img id="apply" 
                	src="pictures/applyonline.png" 
            		width="277" height="179" 
                	alt="apply now, by clicking this button"/></a>
                    
                 <!-- social media button--> 
                <a href="login/login.php"><img src="pictures/social.png" 
                 	alt="employee login" id="social"/></a>
                    
                                           
                 <!-- emplyee login button--> 
                <a href="login/login.php"><img src="pictures/login.png" 
                 	alt="employee login" width="198" height="49"
                    id="login"/></a>
                
                <nav id="navmain">
   				  <ul>
	  				<li><a href="search.php">SEARCH JOBS |</a></li>
	  				<li><a href="apply.php">APPLY |</a></li>
	  				<li><a href="benefits.php">BENEFITS |</a></li>
	  				<li><a href="aboutus.php">ABOUT US</a></li>      
				  </ul>    
			  	</nav>
    </header>
        
        
      
        
   <p style="padding:5px 50px; color:gray;">Please fill out the following secure online application as completely as possible. When completed hit the submit button at the bottom of the page.</p>
               
    
   <div style="background-color:red"><strong style="color:white">&nbsp;&nbsp;Identifying Information </strong></div>
       
        
        <p style="color:gray;margin-left:220px;"> Required Fields indicated with asterisk <span style="color:red">*</span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; (hold shift to select multiple)</p>


    <div style="margin-left:180px; "> 
        <table>
            <tr>
                <td>
                    <select name="title" id="title">
                        <option value="">-Select title-</option>
                        <option value="Mr">Mr.</option>                                              
                        <option value="Mrs">Mrs.</option>   
                        <option value="Miss">Miss.</option>
                        <option value="Ms">Ms.</option>
                    </select>
                </td>
                <td>
                    <select name="dspecialityone" id="specality">
                        <option value="">-RN Speciality-</option>
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
                        <option value="Clinical Director"> Clinical Director</option>
                        <option value="Clinical Instructor">Clinical Instructor</option>
                        <option value="Coronary Care">Coronary Care</option>
                        <option value="Corrections">Corrections</option>
                        <option value="CTICU">CTICU</option>
                        <option value="CVICU">CVICU</option>
                        <option value="CVOR">CVOR</option>
                        <option value="Definitive Observation Unit">
                            Definitive Observation Unit</option>
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
        </table>
    </div>    
        
        <br>
        
        <div style="position: absolute; right:180px; top: 440px "> 
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
                        <td>Desired State </td>
                        <td> &nbsp; &nbsp; &nbsp;</td>
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
            </div>    
        
        
                <table style="margin-left:200px; ">
                    <tr>
                        <td align="left">First name:</td>
                        <td align="left"><input type="text" name="fname" id="fname"> 
                            <span id="firstnamerequired"> <font color="red">*</font></span> </td>
                    </tr>
                    <tr>
                        <td align="left">Last name: </td>
                        <td><input type="text" name="lname" id="lname">
                            <span id="lastnamerequired"> <font color="red">*</font></span> </td>
                    </tr>
                    <tr>
                        <td align="left"> E-mail:</td>
                        <td> <input type="email" name="email" id="email">
                            <span id="emailrequired"> <font color="red">*</font></span></td>
                    </tr>
                    <tr>
                        <td align="left">Cell Phone:</td>
                        <td><input type="tel" name="cell" id="cell">
                            <span id="cellequired"> <font color="red">*</font></span> </td>
                    </tr>
                    <tr>
                        <td align="left">Home Phone:</td>
                        <td align="left"><input type="tel" name="home" id="home"> </td>
                    </tr>

                    <tr>
                        <td align="left">Home Adress: </td>
                        <td align="left"><input type="text" name="adress" id="adress"> </td>
                    </tr>
                    <tr>
                        <td align="left">City: </td>
                        <td align="left"><input type="text" name="city" id="city"> </td>
                    </tr>
                    <tr>
                        <td align="left">State: </td>
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
          <br><br>
   
     <div style="background-color:red"><strong style="color:white">&nbsp;&nbsp;Resume: </strong></div>
            <br>
            <p>Attach Your CV <input name="cv" type="file" id="resume"></p>      
          
            <h5 style="color:gray; font-family: Arial, Helvetica, sans-serif;" &nbsp;&nbsp;><input name="canemail" type="checkbox" id="canemail" value="no" checked="checked">
             I hereby express my written consent to receive emails from Work-A Nurse. I understand these messages will  contain information pertaining to job opportunities, referral bonuses, as well  as other promotional information.We will never share your email with any third parties </h5>    
    
            <h5 style="color:gray; font-family: Arial, Helvetica, sans-serif;" &nbsp;&nbsp;><input name="cantext" type="checkbox" id="cantext" value="Yes" checked="checked">I hereby express my written consent to receive  text messages from Work-A-Nurse. I understand these messages will  contain information pertaining to job opportunities, referral bonuses, as well  as other promotional information.</h5>
              
            <h5 style="color:gray; font-family: Arial, Helvetica, sans-serif;" &nbsp;&nbsp;><input name="cantext" type="checkbox" id="cantext" value="Yes" checked="checked">
              I attest that all statements in this application are true and accurate to the best of my knowledge. I understand that any falsification could lead to disciplinary action and/or termination of employment. I authorize Work-A Nurse to contact past employers and references in order to verify the information I have provided. I release all such persons from liability for furnishing said information. I authorize Work-A-Nurse to release a copy of this application and any medical information which may be relevant to my employment to their client facilities. I agree to hold confidential any client or job opportunities introduced to me by Work-A-Nurse, and agree not to accept assignment or engage directly with any client introduced by Work-a-nurse. By typing your name and date in the space below, you agree that it constitutes your Electronic Signature and is the equivalent, and has the same force and effect of your handwritten signature.</h5>
        
        
        <table style="padding: 0px 180px">
            <tr> 
                <td> Electronic Signature - type your name in the box</td>
            </tr>
            <tr>
                <td><input name="signature" type="text"                                                     id="signature" Style= "line-height:30px; font-size:20pt; font-family: C	Caflisch Script, Adobe Poetica, Sanvito, Ex Ponto, Snell Roundhand, Zapf-Chancery;">
                </td>
                <td>
                     <p><input name="agreedate" type="text" id="agreedate" 
                 value="<?php echo date("m/d/y") ?>"></p>
                </td> 
                <td> <input type="submit"  onClick="homeapply()"> </td>
            </tr>
        </table>  
           <br>
       <h1 id="status" style="font_weight:bold;  color:red"></h1> 
        
        <footer>
	  	    <p>
                Copyright WORK-A-NURSE &copy;  <?php echo date("Y"); ?> -ALL RIGHTS RESERVED. - 
                    Website Terms and Conditions - WWW.WORK-A-NURSE.COM	
            </p>	
        </footer>
        
        	</header>
	
    </body>
</div>
    </html>
	

