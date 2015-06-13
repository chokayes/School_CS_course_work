<?php   

// print out the top 4 jobs on the employee page
function printtop4()
    {
    
            include 'storedInfo.php';
          
            //my passowrd is on hidden page
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "payneal-db",$myPassword, 
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
          
         echo"<table align=center border=1>
            <tr>
            <th> job # </th>
            <th>Title</th>
            <th>location</th>
            <th>Employeement type</th>
            </tr>"; 
  
 
        
        if (!($stmt = $mysqli->prepare("Select id, jtitle, jlocation, jtype from RN_top"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($id, $t, $l, $ty)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            { 
                echo "<tr>";
                 echo "<td> $id</td>"; 
                echo "<td> $t </td>"; 
                 echo "<td> $l </td>";
                 echo "<td> $ty </td>";
                echo "</tr>";  
            } 
            echo "</table>"; 
    
        $stmt->close();  
      
    }

// print all of the specalities out on the search page for rns
function allspecalities()
{
    
    include 'storedInfo.php';
     //my passowrd is on hidden page
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "payneal-db",$myPassword, 
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
        
         echo"Specality: <br> <select name='specalitysearch' id='specalitysearch'> "; 
            echo" <option value=' '>-Select Specality-</option>";  
  
        if (!($stmt = $mysqli->prepare("Select speciality from RN_specalities"))) 
            {
                echo "Prepare failed (SELECT): (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($s)) 
            {
	           echo "select failed (bind): (" . $stmt->errno . ") " .$stmt->error;
            }
            while($stmt->fetch()) 
            { 
              
              echo " <option value='$s'> $s </option>"; 
            }
     
            echo "</select>"; 
        $stmt->close();        
}


?>