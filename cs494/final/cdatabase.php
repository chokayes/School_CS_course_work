<?php 

 // changes the idex page top jobs
function databasetop($x)
{
    // storeInfo.php same as hold.php holds database password 
    include 'login/storedInfo.php'; 
    
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
    
    $thelocation;
                    $thetitle; 
                    $thetype; 
    
    
     if (!($stmt = $mysqli->prepare("SELECT jtitle, jlocation, jtype FROM RN_top
                WHERE id='$x'"))) 
                {
                    echo "Prepare failed (SELECT):
                        (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) 
                {
                    echo "Execute failed:
                        (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->bind_result($jtitle, $jlocation, $jtype)) 
                {
	               echo "select failed (bind): 
                        (" . $stmt->errno . ") " .$stmt->error;
                }
                while($stmt->fetch()) 
                {
                    $thelocation= $jlocation;
                    $thetitle= $jtitle; 
                    $thetype= $jtype; 
                } 
    
    
        $string= $thetitle. "      ".$thelocation."        ".$thetype; 
    
    
    $stmt->close(); 
            return $string; 
}

?>