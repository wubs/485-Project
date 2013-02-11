<?php 
  include('lib.php'); 
  session_start();
  $new_email = $_POST['email'];
  //$new_url = $_POST['url'];


  $subject = "User password from album of EECS485-group36";

  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  function rand_string( $length ) { //generate a random string with length $length
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return substr(str_shuffle($chars),0,$length);
  }

  $password = rand_string(5);

  $query = "SELECT COUNT(*) from User WHERE email='$new_email'";
  $result = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);

  if ($row['COUNT(*)'] < 1) {
    $_SESSION['msg'] = "This email doesn't exist";
    header("Location: ask_email.php");
  } else {
    $query = "UPDATE User SET password = MD5('$password') WHERE email = '$new_email'";
    $result = mysql_query($query) or die(mysql_error());

    $contents = "Your new password is". $password."Please change your password after login"; 
    $from = "eecs485group36@example.com";
    $cc = "eecs485group36@example.com"; //change the email address if necessary

    $bound_text = "group36";
    $bound =  "--".$bound_text."\r\n";
    $bound_last = "--".$bound_text."--\r\n";
       
    $headers =  "From: ".$from."\r\n";
    $headers .= "MIME-Version: 1.0\r\n"
                ."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
    $headers .= "Cc: ".$cc."\r\n"; //add cc here
    
    $message .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
        ."Content-Transfer-Encoding: 7bit\r\n\r\n"
          .$contents."\r\n"
            .$bound;

    $message .= "\r\n" . $contents."\r\n".$bound;
    mail($new_email, $subject, $message, $headers);
    $_SESSION['msg'] = "Email sent";
    header("Location: ask_email.php");
  }
  mysql_close($conn);
?>

