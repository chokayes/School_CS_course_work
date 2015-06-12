<?php 
session_start(); 
?> 
 
<html>
    <head>
        <meta charset="utf-8">
        <title> How to: Show job feeds from Indeed.com</title>
        <link rel="stylesheet.css" href="curl.css">
    </head>
<body>

    <h1> Welcome to the Job Search</h1>

<form action= "indeed.php"  method="POST">
  		<fieldset>
    			<legend>Seach for a Job </legend>
    				Job/title/keyword : <input type="text" name='job' id= "job"> <br>
                    location(city, state, or zip) : <input type="text" name='location'> <br>
                    radius: <input type="number" name='radius'  min="0"> <br> 
                    JOB TYPE 
                    <select name="jobtype"> 
                        <!-- full time is default selected --> 
                        <option selected value ="fulltime">Full Time</option>
                        <option value ='parttime'>Part Time</option>
                        <option value ='contract'>Contract</option>
                        <option value ='internship'>Internship</option>
                        <option value ='temporary'>Temporary</option>
                    </select> <br>
                     SORT
                    <select name="sort"> 
                        <!-- full time is default selected --> 
                        <option selected value ="revelance">relevance</option>
                        <option value ='date'>date</option>
                    </select> <br>
                    JOBS PER Page
                    <select name="amount"> 
                        <!-- full time is default selected --> 
                        <option value ='5'>5</option>
                        <option value ='10'>10</option>
                        <option value ='15'>15</option>
                        <option value ='20'>20</option>
                        <option selected value ='25'>25</option>
                        
                    </select> <br>
  		</fieldset>
    <input type="submit" name='search'  value="submit" >
</form>
</body>
</html>

<?php 
ini_set('display_errors', 'On');
include'holdsid.php'; //  function indeedid() is on this file, returns my indeed id  given my indeed and is used for all calls to indeed api , file not included in repo

//if search button is clicked 
if(isset($_REQUEST['search'])){
    
    //check to see if the seach button was hit
    //echo" the search button has been hit"; 
    
    //holds all of the HTML post Request
        $job = $_POST['job'];
        $loc = $_POST['location'];
        $r = (int)$_POST['radius'];
        $jt = $_POST['jobtype'];
        $sort = $_POST['sort'];
    
        //holds the users requested search amount per page used int so convert to integer
        $amount = (int)$_POST['amount']; 
        
        // used to go through all of the jobs and keep track of postion
        $start = 0; 
        $total = 0; 
        // made finsih equal amount because api only allowed max 25 so loop will be 25
        $finish= $amount;     
    
//hold all the information I need for later in the program    
 $hold = array ( "job" => $job, "location" => $loc, "jobtype" => $jt, "radius" => $r, "sort" =>$sort, "amount" => $amount, "startloop" => $start, "totalresults" => $total, "finishloop" => $finish);  

//had to creat a session to save info so i can research when user hits next page
$_SESSION['hold'] = $hold;
    
    //used for debugging 
    //echo " you shoudl diaplay this many jobs" . $finish, "<br>" ; 
    
    //does job search
    getjobs();  
}

//does job search 
function getjobs(){

    // took information from session so I can use to start job search
    $q = $_SESSION['hold']['job']; 
    $l = $_SESSION['hold']['location']; 
    $jt = $_SESSION['hold']['jobtype']; 
    $r = $_SESSION['hold']['radius']; 
    $s = $_SESSION['hold']['sort'];
    $amount =$_SESSION['hold']['amount']; 
    $start = $_SESSION['hold']['startloop'];
    $total = $_SESSION['hold']['totalresults']; 
    $finish = $_SESSION['hold']['finishloop']; 
    $myindeed = indeedid();
    
    //if you look closely I put in the users post response look for the $(varible) sign
    $url = "http://api.indeed.com/ads/apisearch?publisher=$myindeed&q=$q&l=$l&sort=$s&radius=$r&st=&jt=&start=&limit=$amount&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2"; 
    
    //function is used to Convert the well-formed XML document an object.(link below) 
   # http://php.net/manual/en/function.simplexml-load-file.php
    $xml = simplexml_load_file($url); 
    
    
    // testing to see if the opject exist 
    $query =  $xml->query; 
    $place=  $xml->location; 
    $totalresults = (int)$xml->totalresults; 
    #echo "you searched the following: " . $query . "<br>";
    #echo "the location of the search was: " . $place . "<br>";
    #echo "this search returned a total of " .$totalresults . "<br>"; 
   
    //added the 
    $_SESSION['hold']['totalresults'] = $totalresults; 
    
    //varible to check if I shoudl print 
    $print= 'yes'; 
    
    if ( $totalresults ==0) 
    {
        echo " No results found<br>";
        $print = 'no'; 
    }
    
    if( $print == 'yes')
    {
        // checks if less results than request made by user
        if ($totalresults < $amount)
        {
            //make sure we dont try to access jobs that dont exist                        
            $_SESSION['hold']['amount'] = abs($totalresults - $amount); 
        }
        // print the jobs
        printjobs($xml); 
    }    
}
//prints jobs
function printjobs($xml){
    
    //varible used to hold dynamaically created html 
    $html = ""; 
    
    //error checking
     //var_dump($_SESSION['hold']);
    
    //set up varibles to use from session 
    $amount= $_SESSION['hold']['amount']; 
    $finish= $_SESSION['hold']['finishloop']; 
    
    // loops through the xml and gathers all information on open jobs
for ($x = 0; $x< $amount; $x++){
    $jobtitle =  $xml->results->result[$x]->jobtitle;
    $adress=  $xml->results->result[$x]->url;
    $company =  $xml->results->result[$x]->company; 
    $location =  $xml->results->result[$x]->formattedLocation; 
    $source =  $xml->results->result[$x]->source;
    $date  =  $xml->results->result[$x]->date;
    $snippet =  $xml->results->result[$x]->snippet;
    $time =  $xml->results->result[$x]->formattedRelativeTime;

    //add to the varible that will be output in html
    $html .= "<p>Job:$jobtitle</p><p>company: $company</p><p>location: $location</p>
        <p>source: $source</p><p>Date Posted: $date</p><p>Description: $snippet</p>
        <p>Last updated:$time</p><br>"; 
    }
    
    //after the loop I want to set end of loop to start of next loop 
    $_SESSION['hold']['startloop']= $finish;
    
    //loop end now = the previous loop end plus the request amout of searches per page 
   $_SESSION['hold']['finishloop'] = $finish + $_SESSION['hold']['amount']; 
                
    
echo "<h1> Results </h1>"; 

//prints out all of the results
echo $html; 

    //see if the startloop(how many jobs we have show)  is less than total amount of jobs
    if ($_SESSION['hold']['startloop'] < $_SESSION['hold']['totalresults'])
    {
        //if so we habe more jobs to show so create a next button to show more jobs
        echo "for more results hit the next button<br>"; 
        echo "<form action= 'indeed.php'  method='POST'>"; 
        echo "<input type= 'submit' name='next'  value='next'>"; 
        echo "</form>"; 
    }
}

//this shows the next page of the results on the same page
if(isset($_REQUEST['next'])){

    //error checking, shows if next was selected and all session varibles
   // echo" you hit the NEXT button"; 
    //var_dump($_SESSION['hold']);
    
    // access session varibles so that they can be used 
    $q = $_SESSION['hold']['job']; 
    $l = $_SESSION['hold']['location']; 
    $jt = $_SESSION['hold']['jobtype']; 
    $r = $_SESSION['hold']['radius']; 
    $s = $_SESSION['hold']['sort'];
    $a =$_SESSION['hold']['amount']; 
    $start = $_SESSION['hold']['startloop'];
    $t = $_SESSION['hold']['totalresults']; 
     $myindeed = indeedid();
    
    //receate the search
    //look closesly and you can see varibles used in new $url 
    //the most important change is start (also indeed at most allows 25 jobs to be shown)  
    $url = "http://api.indeed.com/ads/apisearch?publisher=$myindeed&q=$q&l=$l&sort=$s&radius=$r&st=&jt=&start=$start&limit=$a&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2"; 
    
    //the xml feed
    $xml = simplexml_load_file($url); 
   
    //if start + amount is less than the total we know that we dont have to adjust how 
    //many jobs will be shown
    if ( $start + $a <= $t ) 
    {
        //regular print
       printjobs($xml); 
    }
    else 
    {
        // since start+amount > total we must adjust how many jobs we will show
        $_SESSION['hold']['amount']  =  $t - $start; 
        printjobs($xml); 
    } 
}
?> 
