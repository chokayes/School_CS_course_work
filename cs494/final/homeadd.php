<?php

  include 'login/storedInfo.php';
//echo "here"; 


            $recruiter='open'; 
            $title =$_POST["title"];
            $fname = $_POST["fname"]; 
		    $lname = $_POST["lname"];  
            $cell = $_POST["cell"];
            $home = $_POST["home"];
            $email = $_POST["email"];
            $adress = $_POST["adress"];
           $city = $_POST["city"];
            $state = $_POST["state"];
           $license = $_POST["license"];
            $shift = $_POST["shift"];
            $hour = $_POST["hour"];
            $typeofwork = $_POST["typeofwork"];
            $s1 = $_POST["specality"];
            $s2 = $_POST["sother"];
            $dstate = $_POST["dstate"];
            $resume = $_POST["resume"];



  if( $fname =="" || $fname == NULL || strlen($fname)>255)
        {
             if(strlen($fname)>255)
             { 
                 //too long to hold in datadbase 
                 $arr["fnamelength"] = "Error-first name is too long to put in DB"; 
                 //add to keep track of errors
                 $error++; 
             }
             $arr["fname"] = "First name is required";
             //add to keep track of all requirements not met
             $count++; 
        }
        
        //make sure lname is not empy, null, or greater than 255 length 
        if ( $lname =="" || $lname== NULL|| strlen($lname)>255)
         {
             //certain error if fname is greater than 255
            if(strlen($lname)>255)
             {
                $arr["lnamelength"] = "Error-last name is too long to put in DB"; 
                $error++; 
             }
              $arr["lname"] = "Last name is required";
             // //add to keep track of all requirements met
             $count++; 
         }
        
        //make sure that at least one license has been selected
        if ( $license =="" || $license == NULL|| $license == "||")
        {
             $arr["license"] = "License is required";
             $count++; 
        }
        
        //make sure that specality is selected
        if ($s1 =="" || $s1 == NULL)
        {
           $arr["rnspecality"] = "Primary specality is required";
           $count++; 
        }
        
        //phone must be entered with out ( or - and only 10 digits
        if (strlen($cell)>10)
        { 
            $arr["celllength"] = "Error-cell number is too long";
             $error++;    
        }
        
        //checks cell phone for anything but numbers
        for( $x =0; $x< strlen($cell); $x++)
        {
            if(!is_numeric($cell[$x]))
            { 
                $arr["cell"] = "Error-cell invalid
                    -ex (000)-000-0000 shoudl be input as 0000000000";
                $x=  strlen($cell); 
                $error++; 
            }    
        }
        //same as cell just wanted as amny ways to contact  RNS
        if(strlen($home)>10)
        {
             $arr["homelength"] = "Error-home number is too long";
        }
        
        for( $x =0; $x< strlen($home); $x++ )
        {
            if(!is_numeric($home[$x]))
            {
                $arr["home"] = "Error-home invalid -ex
                    (000)-000-0000 shoudl be input as 0000000000";
                $x=  strlen($home); 
                $error++; 
            }
        }
        
        //check the email length because dadtabse only set up to handdle 225 chars
         if(strlen($email)>225)
        {
             $arr["emaillength"] = "Error-email is too long to put in database";
        }
        
        //make sure that @ and . are in email
        $at=0; 
        for( $x =0; $x< strlen($email); $x++ )
        {
            if($email[$x]=='@' || $email[$x]=='.')
            {$at++; }
        }
        
        // if no @ or . in email then error
        if(!$at== 2)
        {
            $arr["email"] = "Email invalid.";
            $error++; 
        }   
    
        //makes sure adress will fit in db I only gave it 50 chars
        if ( strlen($adress)>50)
        {
            $arr["adresslength"] = "Error-adress is too long to put in database";
            $error++; 
        }   
        
        //makes sure city length is uner 25 letters
        if ( strlen($city) > 25)
        {
            $arr["citylength"] = "Error-city is too long to put in database
                (No city in America is longer than 25 letters)";
            $error++; 
        }
       
        
        // check to see only letters are in city name
        for( $x =0; $x< strlen($city); $x++ )
        {
            if(is_numeric($city[$x]))
            {
                $x=  strlen($city); 
                $error++; 
                $arr["city"] = "Error-city can only contain letters - numbers ";
            }
            else if (!ctype_alpha($city[$x]) && $city[$x]!=" ")
            {
                $x=  strlen($city); 
                $error++; 
                $arr["city"] = "Error-city can only can only contain letters 
                    - special char ";   
            }
        } 
        
            //error check - gives number of req not meet and errors
          //  echo" <h1> you reached here</h1> <p> this is the count or reqs= ".$count. "this is number of erroes= " . $error. "</p>";   
                
        // if there is information that is required that we dont have inform user
        if( sizeof($arr) > 0)
        {
            //puts all errors in json object structure
            //http://php.net/manual/en/function.json-encode.php
            $print =json_encode($arr);
            echo $print; 
        }
        else 
        {   
            // put name in database
            
            //my passowrd is on hidden page taken from include @ line 3
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
            
        
            
            
            //inputs to RN_HCP
        if(!($stmt = $mysqli->prepare("INSERT INTO RN_HCP 
           (title, fname, lname, cellphone, homephone, email,
            state, city, adress, recruiter, notes) VALUES ('$title', '$fname', 
            '$lname', '$cell', '$home', '$email', '$state', '$city', '$adress', 
            '$recruiter', '$resume')"))) 
            {
			 echo "Prepare failed (INSERT): (" . $mysqli->errno . ") " . $mysqli->error;
             } 
            if(!$stmt->execute()) 
            {
			     echo "Execute (INSERT INTO) failed:
                 (" . $mysqli->errno . ") ".$mysqli->error;
		    }
		    echo "$fname $lname your information has been submitted";  
            
            //collects HCP's distinct ID
            $hcp_id; 
            
            if (!($stmt = $mysqli->prepare("SELECT id FROM RN_HCP WHERE
                fname='".$fname."' and lname='".$lname."' and email='".$email."'
                and cellphone='".$cell."'"))) 
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
                //collects HCP's distinct ID
                $hcp_id= $theid;  
            } 
            
            //error check - shows individuals last name
            //echo " id= " .$hcp_id; 

            /*        
            Now that we have the hcpid we need to insert this entry to RN(we hold one hcp               for for every RN. We hold a RN for every stare that the HCP is licensed in which             can be idintified by the hcpid
            
            Since that is the case we must use a for loop to see how many rns of this HCP               will be created
            */          
            
            //array that will hold all states rn is licensed        
            $statelicense = array(); 
            //blank string that we will use to seperate the array 
            $hold="";
            
            //go through entire string, which is seperated by "|" per state
            for ($x=0; $x<strlen($license); $x++)
            { 
                if ($license[$x] == '|')
                { 
                    //is a state so push to array
                    $statelicense[]= $hold;    
                    //clear hold for next state
                    $hold="";  
                }
                else 
                {
                    //add letter to hold
                    $hold.= $license[$x]; 
                }
            }
          
            //error check
            //echo" the length of the string = " .strlen($license); 
            //echo"these are the licenses that this person holds<br>"; 
            // print_r($statelicense);
            //echo" the size of that array is ".sizeof($statelicense);
            
            //now we know the size its time to add all of the entries
            for($x=0; $x< sizeof($statelicense); $x++)
            {
                if(!($stmt = $mysqli->prepare("INSERT INTO RNs (pid, 
                    speciality, license) VALUES ('$hcp_id', '$s1', '$statelicense[$x]')"))) 
                {
                    echo "Prepare failed (INSERT):
                        (" . $mysqli->errno . ") " . $mysqli->error;
                } 
                if(!$stmt->execute()) 
                {
                    echo "Execute (INSERT INTO) failed:
                        (" . $mysqli->errno . ") ".$mysqli->error;
		        }
                
            //error: check lets you know each state they had licenese entered for                       echo "you have Insered the Health care Professional(RN):                                        ".$fname." ".$lname. "for the state of: " .  $statelicense[$x]. "<br>";    
            }
  
            
            //for RN_experience I will take rnipd aka ($hcp_id) and the id of the specality                 in which they floated or have experience in and  create one entry for                       experience of each case
            
            // reason being I have created a table of all states in which this HCP has  a                   liscense (also holds there primary experience)-see above.  Now this table                   being created below will hold all of this HCPs floats and other                             experiences. This will allow me to use less memory  yet hold all of state                   licensure and experience. 

            // this is array that will hold experience
            $experience = array(); 
            $hold="";
            
            //for look all of char is $s2 which is all selected floats and other exp
            for ($x=0; $x<strlen($s2); $x++)
            { 
                //exxperencies are seperated by "|"
                if ($s2[$x] == '|'){
                    //one we get top | we know the letters previous need to be pushed
                    $experience[]= $hold;
                    //clear hold
                    $hold="";  
                }
                else {
                    //add letter to hold 
                    $hold.= $s2[$x]; }
            }
            
            //used to track how many exp entered that are not same as primary 
             $count=1;
           //checks to see if user eneded primary specality also as other experiences/float 
            for($x=0; $x< sizeof($experience); $x++)
            {
                //for every exp that is not there primary we will add 
                if ($s1 != $experience[$x])
                { $count++; } //add
            }
            
            //error check
            //echo " this is the count ".$count."<br>"; 
            //echo " this is the size ".sizeof($experience). "<br>";
            
            
            //we can infer that they did not add there primary if the numbers do not add up             and we still want to put the primary as a experience in case anything changes
            if($count != sizeof($experience))
            {
                //puts primary rn specaility in the array
                $experience[]= $s1;
            }
            
            //error check
            // print_r($experience);
        
          //enter this many instances of experience(pushed each word so its now total count)
            for($x=0; $x< sizeof($experience); $x++)
            {
                //used to hole  the specality id
                $sid; 
            
                // thes table holds id of specality so me must get teh actuall id we have                       words 
                if (!($stmt = $mysqli->prepare("SELECT id FROM RN_specalities 
                    WHERE speciality='".$experience[$x]."'"))) 
                {
                    echo "Prepare failed (SELECT)
                    : (" . $mysqli->errno . ") " . $mysqli->error;
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
                    //collects id of specality
                    $sid= $theid;  
                }    
            
                //now that we have specality we can insert each instance of the users                           experience
                if(!($stmt = $mysqli->prepare("INSERT INTO RN_experience 
                    (rnpid, experience) VALUES ('$hcp_id', '$sid')"))) 
                    {
                        echo "Prepare failed (INSERT): 
                        (" . $mysqli->errno . ") " . $mysqli->error;
                    }    
                    if(!$stmt->execute()) 
                    {
                        echo "Execute (INSERT INTO) failed:
                        (" . $mysqli->errno . ") ".$mysqli->error;
		            }
                    
                      //error check- list all of entered experience
		              //echo "you have Insered the Health care Professional(RN): ".$fname."                       ".$lname. "for the  experience of shifts of: ".$sid."<br>"; 
            }
            /*               
            So the RN-desire holds rnip aka(hcpid) and what type of assignmnet they are                 looking for per diem, travel, or perm. I made it this way because thoes are the              options of employeement some prefer one others keep there options open
            */          
                
                //error checker - prints out all types of work user selected
                 //  print_r($typework);
                
              // this is array that will hold the type -per diem, travel, or perm   
            $holdtype = array(); 
            $hold="";
            
            // for loop gooes through all characters of typework
            for ($x=0; $x<strlen($typeofwork); $x++)
            {
                //indicator of next word
                if ($typeofwork[$x] == '|'){ 
                    //pushes word to array 
                    $holdtype[]= $hold;    
                    // clears the holder
                    $hold="";  
                }
                else {
                    // adds chars to collect word
                    $hold.= $typeofwork[$x]; }
            }
            
            // error checker that shows everything in the array
            //  print_r($holdtype);
        
            // per diem, travel, and perm is in table as T/F so we assume they are false    
            $pd= 0; 
            $p= 0; 
            $t= 0; 
            
            // we know how many were entered so we can now loop through the array    
             for($x=0; $x< sizeof($holdtype); $x++)
            {
               if ($holdtype[$x] == "travel")
               {
                   //user selected travle so its true
                   $t=1; 
               }
                if ($holdtype[$x] == "perm")
               {
                    //user selected perm so its true
                   $p=1; 
               } 
                if ($holdtype[$x] == "pd")
               {
                    //user selected per diem so its true
                   $pd=1; 
               }
            }
            
            //error checker
            //echo" this is the value of travel: ".$t. " this is the value of perm: ".$p."                 this is the value of per diem".$pd."<br>";     
    
            //Now that we have the answer for travel perm and perdiem we can insert in                     RN_desire
             if(!($stmt = $mysqli->prepare("INSERT INTO RN_desire 
                (rnpid, travler, perdiem, perm) VALUES ('$hcp_id', '$t', '$pd', '$p')"))) 
                {
                    echo "Prepare failed (INSERT):
                    (" . $mysqli->errno . ") " . $mysqli->error;
                } 
            if(!$stmt->execute()) 
                {
                    echo "Execute (INSERT INTO) failed: 
                    (" . $mysqli->errno . ") ".$mysqli->error;
		        }
            
            // shows what the user was entered as
		      //echo "you have Insered the Health care Professional(RN):".$fname." ".$lname.                  "for: travel= " .$t." per diem= ".$pd. " perm= ".$p."<br>"; 
            
            //Now that we have entered everything into the desire table we will need to tale               the auto incremented key that is given for each hcp that is inserted. we will                use this key to reference us back to the hcp's id 
        
            // this holds the desired id
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
                $desiredid= $theid;  
            } 
        
            //error checker
            //echo "here is the desired id ".$desiredid. "<br"; 
            
            //Now we want to input the indiviudals desired shifts so in the html they can               select any amount of shifts so we need to once again find out how many shifts               they selected
            
            //error check- prints out all of the shifts selected 
            //  echo $shift;    
                
            //array that will hold all the shifts user entered
            $shifts = array(); 
            $hold="";
        
            //for loop that goes through all of the characters in shift
            for ($x=0; $x<strlen($shift); $x++)
            { 
                // "|" is an indicator of the next word 
                if ($shift[$x] == '|')
                { 
                   //this pushes the word into the array shift
                    $shifts[]= $hold;     
                    // cleears the varible hold so new word can be stored
                    $hold="";  
                }
                else 
                {
                    //adds next char to hold
                    $hold.= $shift[$x]; 
                }
            }  
            
            //error checker to see the array  
            //  print_r($shifts);
            
            // night, day, and evening are in table as T/F so we assume they are false 
            $night= 0; 
            $day= 0; 
            $evening= 0; 
           
            //like abover we know how many were entered so we can now loop through the array
             for($x=0; $x< sizeof($shifts); $x++)
            {
               if ($shifts[$x] == "day")
               {
                   //user selected day so true
                   $day=1; 
               }
                if ($shifts[$x] == "night")
               {
                    //user selected night so true
                   $night=1; 
               } 
                if ($shifts[$x] == "evening")
               {
                    //user selected eventing so true
                   $evening=1; 
               }
            }
   
            // error check shows values of entires for night/day/evening 
            //echo" this is the value of day: ".$day. " this is the value of night:                     ".$night." this is the value of evening".$evening."<br>";     
    
            // now we insert thoes values to shift, and now know there desired shift      
            if(!($stmt = $mysqli->prepare("INSERT INTO RN_shift 
                (desireid, night, day, evenings) 
                VALUES ('$desiredid', '$night', '$day', '$evening')"))) 
            {
                echo "Prepare failed (INSERT)
                    : (" . $mysqli->errno . ") " . $mysqli->error;
            } 
            if(!$stmt->execute()) 
            {
                echo "Execute (INSERT INTO) failed
                    : (" . $mysqli->errno . ") ".$mysqli->error;
	        }

            // eror checker shows  what was inserted as day/night/evening
            // echo "you have Insered the Health care Professional(RN): ".$fname." ".$lname.             "for the  shifts of: day= " .$day." night= ".$night. " evenings= ".$evening."               <br>";             
            
            //Now we can input the desired hours(RNs work various shifts 12,10,and 8 are                    common
           
            // error chek - prints out the string of hours selected
             //echo" the value of hour is: " .$hour. "<br>";     
                
            // array that will hold all of the datat collected
            $hours = array(); 
            $hold="";
        
            //for loop that goes through all chars of the string hour
            for ($x=0; $x<strlen($hour); $x++)
            { 
                //"like previously "|" indictates new word
                if ($hour[$x] == '|')
                {            
                    //push the word to the array
                    $hours[]= $hold;     
                    //clear the holder that was collecting all letters/chars
                    $hold="";  
                }
                else 
                {    
                    //add next char 
                    $hold.= $hour[$x]; 
                }
            } 
                
            //error checker that shows all of array
            //  print_r($hours);
            
            // 8's, 10's, and e12's are in table as T/F so we assume they are false
            $eight= 0; 
            $ten= 0; 
            $twelve= 0; 
            
            for($x=0; $x< sizeof($hours); $x++)
            {
               if ($hours[$x] === "8")
               {
                   // selected 8hr shifts 
                   $eight=1; 
               }
                if ($hours[$x] === "10")
               {
                    //selected 10 hour shifts
                   $ten=1; 
               } 
                if ($hours[$x] === "12")
               {
                    // selected 12 hour shifts
                   $twelve=1; 
               }
            }
           
            // error checker        
            //echo" this is the value of eight: ".$eight. " this is the value of ten:                   ".$ten." this is the value of twelve= ".$twelve."<br>";     
                
            // now we have the needed info we can insert the bool for hours    
            if(!($stmt = $mysqli->prepare("INSERT INTO RN_hour 
                (desireid, eights, tens, twelves) 
                VALUES ('$desiredid', '$eight', '$ten', '$twelve')"))) 
            {
                echo "Prepare failed (INSERT)
                : (" . $mysqli->errno . ") " . $mysqli->error;
            } 
            if(!$stmt->execute()) 
            {
                echo "Execute (INSERT INTO) failed
                    : (" . $mysqli->errno . ") ".$mysqli->error;
            }
            
            // error checker    
            //echo "you have Insered the Health care Professional(RN): ".$fname." ".$lname.             "for the hours of: eight=" .$eight." ten= ".$ten. " twelve= ".$twelve."<br>"; 
            
            
            /*    
                    Now I am going to add to the table dlocation. Dlocation holda every                         state that the user selected as a desired state to work. So If the user                     had 30 desuired states we will have 30 entries with there rnip                               (hcp_id).This was done because open position  with teh government do not                     require a license also indian resivors, etc. Some facilities will wait                       for individuals to get a license so its kind of important to know there                      desired areas 
                    
                    //also  with this form it will easy to add city, radius, and even season                        later on but not doing it for this version
            */
                
            //error checker
            //echo" the value of the desired stater: " .$dstate. "<br>";     
                
            //find how many states they entered 
            $states = array(); 
            $hold="";
            
            // do a for loop through the string of dstate
            for ($x=0; $x<strlen($dstate); $x++)
            { 
                // "|" indicates a new state 
                if ($dstate[$x] == '|')
                {            
                    //hold the state abbr
                    $states[]= $hold;    
                    //clear the holder
                    $hold="";  
                }
                else 
                {    
                    // add letter to state abbr.
                    $hold.= $dstate[$x]; 
                }
            }  
            
            // error checker - prints out array 
            //  print_r($states);
            
            // the way that desired states what set up was to input the state id and the                    rnip  aka hcpid so now  that we have the state name we need the state id 
                
            // finds state id for each state and then inputs each instance    
            for($x=0; $x< sizeof($states); $x++)
            {
                //holds the state id
                $thestate; 
                //finds the id
                if (!($stmt = $mysqli->prepare("SELECT id FROM RN_licensure 
                WHERE state='".$states[$x]."'"))) 
                {
                    echo "Prepare failed (SELECT):
                        (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) 
                {
                    echo "Execute failed:
                        (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->bind_result($theid)) 
                {
	               echo "select failed (bind): 
                        (" . $stmt->errno . ") " .$stmt->error;
                }
                while($stmt->fetch()) 
                {
                    //collects id of specality
                    $thestate= $theid;  
                }
                
                // inserts the desired location
                if(!($stmt = $mysqli->prepare("INSERT INTO
                    RN_dlocation (desireid, stateid) VALUES ('$desiredid', '$thestate')"))) 
                {
                    echo "Prepare failed 
                        (INSERT): (" . $mysqli->errno . ") " . $mysqli->error;
                } 
                if(!$stmt->execute()) 
                {
                    echo "Execute (INSERT INTO) 
                        failed: (" . $mysqli->errno . ") ".$mysqli->error;
		        }

                //error checker
		        //  echo "you have Insered the Health care Professional(RN): ".$fname."                       ".$lname. "for the  experience of shifts of: ".$sid."<br>"; 
            }  
            
            //closes the opened database connection
            $stmt->close();       
      }
        
 ?>