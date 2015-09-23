<?php
#error_reporting(E_ALL);

$decision = $_POST['oldornew'];

$email = $_GET['email'];
 if ($email) {
   //echo "in here";

   $qry_str = "email=" . $email;

   //echo $qry_str;

   $ch = curl_init();

   //Set query data here with the URL
   curl_setopt($ch, CURLOPT_URL, 'http://papertrader2-1007.appspot.com/homework?' . $qry_str);

   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_TIMEOUT, '3');
   $content = trim(curl_exec($ch));
   curl_close($ch);

   print $content;
   
 }


if ($decision == 'old') {

  $email = $_POST['email'];
  $password = $_POST['password'];
  //echo "this is: " . $email . "this is pass: " . $password . "this is decision: " . $decision;


  //echo "you are verifying a user";
  $data = array("oldornew" => $decision, "password" => $password, "email" => $email);
  $data_string = json_encode($data);

  //echo $data_string;
  $ch = curl_init();
//  $url = 'http://localhost:8080/homework';
  curl_setopt($ch, CURLOPT_URL,'http://papertrader2-1007.appspot.com/homework');
  curl_setopt($ch, CURLOPT_POST, 1);
#  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

  //#$ch = curl_init("http://google.com");    // initialize curl handle
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  $result = curl_exec($ch);

  if ($result === false)
  {
    //echo 'curl error: ' . curl_error($ch);
  }
  else
  {
    //echo 'completed wuth no errors';
  }

  curl_close($ch);
  //echo $result;
} elseif ($decision == 'new'){

  $username = $_POST['username'];
  $icon = $_POST['icon'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $broker = $_POST['broker'];
  $cash = $_POST['cash'];

//  echo "this is username: " . $username . "this is icon: " . $icon . "this is email: ". $email . "this is password: " . $password . "this is broker: " . $broker . "this si cash: "  . $cash;
  $data = array("oldornew" => $decision, "username" => $username, "icon" => $icon, "email" => $email, "password" => $password, "broker" => $broker, "cash" => $cash);
  $data_string = json_encode($data);

  $ch = curl_init();
//  $url = 'http://localhost:8080/homework';
  curl_setopt($ch, CURLOPT_URL,'http://papertrader2-1007.appspot.com/homework');
  curl_setopt($ch, CURLOPT_POST, 1);
#  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

  //#$ch = curl_init("http://google.com");    // initialize curl handle
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  $result = curl_exec($ch);

  if ($result === false)
  {
    //echo 'curl error: ' . curl_error($ch);
  }
  else
  {
    //echo 'completed wuth no errors';
  }

  curl_close($ch);


}







 ?>
