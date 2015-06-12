<?php
//ini_set('display_errors', 'On');
session_start();
?>

<html>
    <head>
        <meta charset= "utf-8">
        <title> Employer Statistics </title>
        <link rel="stylesheet" tpye="text/css" href= "theme.css">
        <script> 
            // maybe i need an addition javascript 
        </script>
    </head>
    <body>
        <h1> Employer statistics</h1> 
        <p> Type in a position and view rating 
            and statistics for the role and  company 
        <a href='http://www.glassdoor.com/index.htm'>powered by <img src='http://www.glassdoor.com/static/img/api/glassdoor_logo_80.png' title='Job Search' />
        </a>
        </p>
    
        <form action= "employer_statistics.php"  method="POST">
  		<fieldset>
    			<legend>Search for a particular role </legend>
                <p>Job title: <input type="text"  name="role" value= ""> <span style="color:red">*</span></p> 
                 <p>location(city, state, or country) : <input type="text" name='location'></p>  
                <p style="font-size: 25%" > *Required Fields indicated with red asterisk</p>
                
  		</fieldset>
    <input type="submit" name='search'  value="submit" >
    </form>

    </body>
</html>

<?php
    // if search button is clicked do the following 
    // really just set up the session information array that will be continually used 
if(isset($_REQUEST['search']))
{
    //error check to see if search was hit 
    //echo "the search button has been hit"; 
        
    //hold all of the HTML post 
    $title = $_POST['role']; 
    $location = $_POST['location']; 
    
    if (trim($title, " ")== "")
    {
        echo "TITLE MUST BE ENTERED <br>"; 
    }
    else 
    {
         //turns out if two words are entered ex. sales manager(title) or new york(city) things break
        //quick fix 
        $title = replaceSpaces($title); 
        $location =  replaceSpaces($location);

        //array created that keeps all info input 
        $query  = array ( "title" => $title, "location" => $location, "currentpg" => 1, "lastpg"  => NULL, "userip" => NULL, "count" => 0); 

        //create a session to save info 
        $_SESSION['query']= $query; 

        //does actual title search 
        gettitles(); 
    }
}

//used to replace spaces with %20 for sake of url 
function  replaceSpaces($word){
    //the holds new string created
    $newword= ""; 
    //goes throught current string placing %20  where spaces are 
    for ( $i =  0;  $i <  strlen($word); $i++) 
    {
        if($word[$i] == " ")
           {
                $newword= $newword . "%20";    
           }
           else 
           {
               $newword = $newword . $word[$i]; 
           }
    }
            //new word created 
           return  $newword; 
}

//this gets the api information initially ( first time hitting search) find last page
function gettitles(){
 
    // take information from the session to return search results 
    $q  = $_SESSION['query']['title'];  
    $l = $_SESSION['query']['location']; 
    $pn =  $_SESSION['query']['currentpg']; 
    // need visiting useres ip adress for url (tracking ???)
    $userip = $_SERVER['REMOTE_ADDR']; 
    
    $_SESSION['query']['userip'] = $userip;
     
    //info on urls incase you want to know 
    //documenatation found here: https://www.glassdoor.com/api/companiesApiActions.htm
    //t.p = partner id 
    //t.k  = parnet key 
    // both t.p and t.k are assigned by glassdoor once registered 
    
    //error check - find out what is the searchg
    //echo  "this is what is being searched:  $q  with this location: $l<br>";  
    
    //set url 
    $url= "http://api.glassdoor.com/api/api.htm?t.p=31021&t.k=gZ9FD6EhQH5&userip=$userip&useragent=&format=json&v=1&action=employers&q=$q&l=$l&pn=$pn"; 
    
    //curl works but not initially had to set up cloud9 so this wont be used
    // $curl= curl_init();
    // curl_setopt($curl, CURLOPT_URL, $url); 
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    // $result = curl_exec($curl); 
    // curl_close($curl); 
    //$decode =  json_decode($result); 
    // above was using curl which is  possible but so is file_get_contents
    
    //same as curl but not as secure use if curl is not avalible 
    $decode = file_get_contents($url); 
    // makes $decode and json 
    $decode =  json_decode($decode);     
    
    //error check see what was returned
    //var_dump($decode);
    
    //error check to see the url to check against results
    //echo "$url <br>" ; 
    
    //set the total nubmer of pages
    $_SESSION['query']['lastpg'] =  $decode->{'response'}->{'totalNumberOfPages'}; 
    
    //error check - dump session variblses 
    //var_dump($_SESSION);
    //echo "<br>"; 
    
    //make sure that there are results to be returned 
    if ( $decode->{'response'}->{'totalRecordCount'} == 0)
    {
        echo "No Results Found <br>";        
    }
    else 
    {    
        // pass json object to print function 
        printtitles($decode); 
    }
}
                     
function printtitles($decode){
    
    //check the page number and last page number 
    $pgnumber = $decode->{'response'}->{'currentPageNumber'}; 
    $lastpgnumber= $_SESSION['query']['lastpg']; 
    //if the pg # is the last page we willl not loop  10 time; 
    $loop; 
    
    if ( $pgnumber == $lastpgnumber ) {
        //if this is the last pg we must find out how many more search results we have  
        $totalResults= $decode->{'response'}->{'totalRecordCount'}; // total result# from search 
        $count=  10 * ($pgnumber-1); //amount of entries seen so far 
        
        //only  this amount of search results left
        $loop  = $totalResults -  $count; 
    }
    else 
    {
        //api says you can determine how many results returned and default is 20 
        // but everytime I tested it it only returned 10 results so we used 10 
        $loop =10;  
    }
    
    // place next and previou here 
    if ($pgnumber > 1) 
    {
        //previous button should be displayed
        //echo "previous page is possible <br>"; 
         echo "<form action= 'employer_statistics.php'  method='POST'>"; 
        echo "<input type= 'submit' name='previous'  value='previous'>"; 
        echo "</form>"; 
    
    }
    
    if ($pgnumber < $lastpgnumber)
    {
        // next button should be displayed  
       // echo "nexts page is possible <br>"; 
        echo "<form action= 'employer_statistics.php'  method='POST'>"; 
        echo "<input type= 'submit' name='next' id='next'  value='next'>"; 
        echo "</form>"; 
        
    }
    
        
    //loop to print valuable information
    for ($i = 0; $i < $loop ; $i++)
    {
        $hold = $decode->{'response'}->{'employers'}[$i]; 
        
        //for each entry add count so user know what the count is 
         $_SESSION['query']['count']++; 
        $count =  $_SESSION['query']['count']; 
        
        echo"$count.) " ; 
        //company infor 
        $cname= $hold->name; 
        $industry= $hold->industry;
        $website= "https://$hold->website";        
        echo " <b>$cname</b> <br> Industry: $industry <br>" ; 
        echo " link: <a href='$website'>Visit $cname website</a> <br>"; 
            
        // so here is what darnel wanted to change 
        // try and run the program and for the job title enter sports reporter 
        
        // you should get something like
        /*
        
        1.) Verde Independent 
        Industry: Publishing 
        link: Visit Verde Independent website 
        Overall rating: 4 Satisfied 
        Culture and Values: 0.0     
        Senior Leadership: 0.0 
        Compensation and Benifits: 0.0 
        Career Opportunities: 0.0 
        Work Life Balance: 0.0 
        Recommend to a Friend: 0.0 
        Below is a former employees prespective : 
        Job Title: Sports Reporter 
        Location: Cottonwood, AZ 
        Employee quote: Former Sports Reporter 
        Pros: Great Editor and department. Worked with great reporters and staff. Easy-going environment where creativity is allowed and new ideas are welcomed. 
        Cons: The pay could be better for the area, but the freedom of the job made up for a lot of that. 
        Individuals rating of Job: 4 
        Below is upper management information: 
        Pam Miller is CEO/Publisher 
        Approval = 0% 
        Disapproval = 100% 
        
        */
        
        // im thinking we could just do an if statment for all the ones that handle rating 
        // maybe if the rating == 0.0 make the varile n/a rather than 0.0 
        
        
            
        //statistics 
        $or = $hold->overallRating; 
        $rd = $hold->ratingDescription; 
        $cav = $hold->cultureAndValuesRating; 
        $slr = $hold->seniorLeadershipRating; 
        $cabr = $hold->compensationAndBenefitsRating; 
        $cor = $hold->careerOpportunitiesRating; 
        $wlbr = $hold->workLifeBalanceRating; 
        $rtrf = $hold->recommendToFriendRating; 
       
        // makes sure overall rating isnt 0.0
        if ($or == "0.0")
        {
            echo "Overall Rating:<b>N/A</b> <br>"; 
        }
        else
        {
            echo "Overall Rating: <b>$or $rd</b> <br>"; 
        }
        // make sure Culture and values raiting isnt 0.0
        if ($cav == "0.0")
        {
            echo "Culture and Values: <b>N/A</b> <br>";
        }
        else
        {
            echo "Culture and Values: <b>$cav</b>  <br>"; 
        }
        //make sure senior leadership isnt 0.0 
        if ($slr == "0.0")
        {
            echo "Senior Leadership: <b>N/A</b> <br>";
        }
        else
        {
            echo "Senior Leadership: <b>$slr</b>  <br>"; 
        }
        //make sure compensation and benifits isnt 0.0 
        if ($cabr == "0.0")
        {
            echo "Compensation and Benefits: <b>N/A</b> <br>";
        }
        else
        {
            echo "Compensation and Benefits: <b>$cabr</b>  <br>"; 
        }
        //make sure career opportunities isnt 0.0 
        if ($cor == "0.0")
        {
            echo "Career Opportunities: <b>N/A</b> <br>";
        }
        else
        {
            echo "Career Opportunities: <b>$cor</b>  <br>"; 
        }
        // make sure work life balance isnt 0.0 
        if ($wlbr == "0.0")
        {
            echo "Work Life Balance: <b>N/A</b> <br>";
        }
        else
        {
            echo "Work Life Balance: <b>$wlbr</b>  <br>"; 
        }
        // make sure recommend to a friend isn't 0.0
        if ($rtrf == "0.0")
        {
            echo "Recommend to a Friend: <b>N/A</b> <br>";
        }
        else
        {
            echo "Recommend to a Friend: <b>$rtrf</b>  <br>"; 
        }
        
        //backup raw echo statements
        //echo "Senior Leadership: <b>$slr</b> <br> "; 
        //echo "Compensation and Benifits: <b>$cabr</b> <br>"; 
        //echo "Career Opportunities: <b>$cor</b> <br>"; 
        //echo "Work Life Balance: <b>$wlbr </b> <br>"; 
        //echo "Recommend to a Friend: <b>$rtrf</b> <br>"; 
        
        echo "<b>Below is a former employees prespective : </b><br>";     
        $hold = $hold->{'featuredReview'}; 
        
         if ($hold == NULL ) 
        {
            // no ceo info provided 
            echo "No former employee perspective avalible <br>";  
        }
        else 
        {
            // employee with similar title entered review 
            $jt = $hold->jobTitle; 
            $l = $hold->location; 
            $hl = $hold->headline; 
            $p = $hold->pros; 
            $c = $hold->cons; 
            $r = $hold->overallNumeric;  
            echo "Job Title: $jt  <br>";
            echo "Location: $l <br>"; 
            echo "Employee quote: $hl <br>";
            echo "Pros: $p <br>"; 
            echo "Cons: $c <br>"; 
            echo "Individuals rating of Job: $r <br>"; 
        }
        
        echo "<b>Below is upper management information: </b><br>";  
        $hold = $decode->{'response'}->{'employers'}[$i]->{'ceo'}; 
        //check to see if glassdoor has any ceo information
        if ($hold == NULL ) 
        {
            // no ceo info provided 
            echo "No upper managemnet information avalible <br>";  
        }
        else 
        {
            //boss rating 
            $n = $hold->name; 
            $t = $hold->title;
            $a = $hold->pctApprove; 
            $d = $hold->pctDisapprove; 
            echo "$t is $n <br>"; 
            echo "Approval = $a% <br>"; 
            echo "Disapproval = $d% <br>"; 
        }
        //new line between each result 
        echo"<br>"; 
    }
    
}

//next button was hit
if(isset($_REQUEST['next'])){
    //moves results to the next page
    moveit(1); 
}

//next button was hit
if(isset($_REQUEST['previous'])){
    
    // moves back the count 
    if ($_SESSION['query']['currentpg'] == $_SESSION['query']['lastpg'])
    {
        //if this is the case we cant just move back 10 because it will mess up 
        //count if there is not 10 entries on this page so we must do  some 
        //additional math 
        
        //var_dump($_SESSION); 
        //echo "<br>"; 
        
        //check total 
         $totalResults= $_SESSION['query']['count']; 
        //check number we have passed 
        //echo " this is the value of total: $totalResults <br>";
        $count=  10 * ($_SESSION['query']['currentpg']-1); //amount of entries seen so far 
        //echo " this is the value of count: $count <br>"; 
        
        $count = $totalResults - $count; 
        
        //echo " this is the value of total - count : $count <br>"; 
        // subtract 10 + the number we just found 
        $_SESSION['query']['count'] = $_SESSION['query']['count'] - ( 10 + $count);
        //call moveit(-1)
        moveit(-1); 
    }
    else 
    {
        //  since each page is 10 entries we know we must go back 20
        // 10 for current page  and 10 for last page
        
        $_SESSION['query']['count'] = $_SESSION['query']['count'] -20;
        //moves results to the previous page 
        moveit(-1); 
    }
    
}

function moveit($x) 
{
    //moves to the next pg 
    $_SESSION['query']['currentpg'] = $_SESSION['query']['currentpg'] + $x; 
    
    
    $q  = $_SESSION['query']['title'];  
    $l = $_SESSION['query']['location']; 
   $pn =  $_SESSION['query']['currentpg']; 
    // need visiting useres ip adress for url (tracking ???)
    $userip = $_SERVER['REMOTE_ADDR']; 
    
    $_SESSION['query']['userip'] = $userip;
    
     //set url 
    $url= "http://api.glassdoor.com/api/api.htm?t.p=31021&t.k=gZ9FD6EhQH5&userip=$userip&useragent=&format=json&v=1&action=employers&q=$q&l=$l&pn=$pn"; 
    
    $decode = file_get_contents($url); 
    // makes $decode a json 
    $decode =  json_decode($decode);  
    
     // pass json object to print function 
    printtitles($decode); 
}

//everything is completed except the test go ahead and try it out 


?>
