<?php
 session_start(); 
   include 'cdatabase.php';
    
    $one= databasetop(1); 
   $two= databasetop(2);  
    $three= databasetop(3); 
    $four= databasetop(4); 
    
?>

<!-- this is the function for the power point--> 
<script type= "text/javascript"> 
var imagecount = 0; 
var total= 5; 

function slide(x){
	var image = document.getElementById('img'); 
	
	imagecount= imagecount + x;
	if (imagecount > total)
	{
		imagecount= 0; 
	}
	
	if (imagecount < 0 )
	{
		imagecount= total; 
	}
	
	image.src = "slide/image" + imagecount + ".png"; 
}

window.setInterval(function autoslide(){
	var image = document.getElementById('img'); 
	
	imagecount= imagecount + 1;
	if (imagecount > total)
	{
		imagecount= 0; 
	}
	
	if (imagecount < 0 )
	{
		imagecount= total; 
	}
	
	image.src = "slide/image" + imagecount + ".png"; 
}, 5000); 


</script>
<!DOCTYPE html>
	<html>
	<head>
    	<meta charset="utf-8">
    	<title>Work-A-Nurse</title>
        <base href="http://web.engr.oregonstate.edu/~payneal/cs494/final/workanurse/index.php"/>
    	<link rel="stylesheet" type="text/css" href="nurse.css">
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
    
    		<!-- image slider --> 
            <div id="container"> 
           		
                <!-- first image in the slideshow --> 
                <img src="slide/image0.png" id="img"/>
           	  	
                <div id ="left_holder">
                	<img id="left" onclick="slide(-1)" src="slide/left.png"> 
                 </div>
              
       		  	<div id ="right_holder">
                 	<img id="right" onclick="slide(1)" src="slide/right.png"> 
                </div>
            </div> <!-- end of image slider --> 
            
            
            
            
            <div class ="quicksearch">
            	<form> 
                <h4 id="quick" > Quick Job Search </h4>
                <select name="typeofwork" style="width:150px">
                	<option value="">-Select assignmnet-</option>
                	<option value="travel"> Travel assignment </option>
                    <option value="perm"> Perm placement </option>
                    <option value="pd"> Per Diem </option>
                 </select>
                 <br><br>
                 <select name="speciality"style="width:150px">
                	<option value="">-Select Speciality-</option> </option>
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
                 <br><br>
                 <select name="state" id='homepagestate' style='width:150px'>
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
                <br> <br>
                <input type="submit" value="Submit" align="right" style='width:100px; color: white; background-color:red'>
               </form>
            </div>
    		<div id="topjobs"> 
    			<img src="pictures/topjobstable.png">
    	
                
                <div style="position:absolute; top:140px; right: 170px;" > <?php echo "$one" ?> </div>
                <div style="position:absolute; top:180px; right: 170px;" > <?php echo" $two" ?> </div>
                <div style="position:absolute; top:213px; right: 170px;" > <?php echo" $three" ?> </div>
                <div style="position:absolute; top:250px; right: 170px;" > <?php echo" $four" ?> </div>
                
        </div>
			
            <div id="companyinfo">
            	<h1 > About Work-A-Nurse</h1>
                <h3> &nbsp; Since established Work-A Nurse has been a leading provider in   hiring and retaining top nursing talent. We staff qualified nurses to fill permanent, travel, and per diem assignmnet nationwide. We make staffing easy and thats why we are able to partner with many of the most well-know health care providers. If your a nurse looking for a job or a facility in need do not hesitate to give us a call at 1-800-WE-NURSE.  </h3>
            </div>
          
            <footer>
	  			<p>
                	Copyright WORK-A-NURSE &copy;  <?php echo date("Y"); ?> - 
        			ALL RIGHTS RESERVED. - Website Terms and Conditions - WWW.WORK-A-NURSE.COM	
                </p>	
            </footer>
	</body> 
	</div>  <!-- end of the jello div -->
</html>

