<?php
$decision = $_POST['move'];

if ($decision == 'buy') {

    //echo"buy stock";
    
    $email = $_POST['email'];
    $desc = $_POST['desc'];
    $symb = $_POST['symb'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    
    //echo "you are verifying a user";
    $data = array("email" => $email, "desc" => $desc,  "symb" => $symb, "price" => $price, "qty" => $qty );
    
    $data_string = json_encode($data); 
   // echo $data_string; 
    
    
    //echo $data_string;
    $ch = curl_init();
    //$url = 'http://localhost:8080/homework';
    curl_setopt($ch, CURLOPT_URL,'http://papertrader2-1007.appspot.com/homework');
   
    
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HEADER, TRUE);

    $result = curl_exec($ch);

    if (!$result)
    {
        echo 'curl error: ' . curl_error($ch);
    }
    else{
        echo $result; 
    }
  

    curl_close($ch);
    
    
} else {

    //echo"sell stock"; 
     $email = $_POST['email'];
    $symb = $_POST['symb'];
    $qty = $_POST['qty'];
    
     //echo "you are verifying a user";
  $data = array("email" => $email,  "symb" => $symb, "qty" => $qty );
    
    $data_string = json_encode($data);
    
    
    //echo $data_string; 
    
    
    //echo $data_string;
    $ch = curl_init();
    //$url = 'http://localhost:8080/homework';
    curl_setopt($ch, CURLOPT_URL,'http://papertrader2-1007.appspot.com/homework?email='.$email.'&symb='.$symb.'&qty='.$qty);
   
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HEADER, TRUE);

    $result = curl_exec($ch);

     
 echo  json_encode($result); 
   
   

    curl_close($ch);
    
}



?>