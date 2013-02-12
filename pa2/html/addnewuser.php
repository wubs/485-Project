<?php 
  include('lib.php'); 
  session_start();
  $new_username = $_POST['username'];
  $new_email = $_POST['email'];
  $new_password = $_POST['password'];
  $new_f_name = $_POST['f_name'];
  $new_l_name = $_POST['l_name'];
  
  
  
  $subject = "Welcom To Our EECS485 PA2 Website";
  $contents = "Dear ".$new_f_name." "
    .$new_l_name." you have successfully registered as "
    .$new_username;
  $from = "eecs485pa2@example.com";
  $to = $new_email;
  $CC = "wubs@umich.edu, ruoran wang <ruoran@umich.edu>, Dailin Liu <dailin@umich.edu>";

  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $bound_text = "group36";
  $bound =  "--".$bound_text."\r\n";
  $bound_last = "--".$bound_text."--\r\n";
     
  $headers =  "From: ".$from."\r\n";
  $headers .= "Cc: ".$CC. "\r\n";

  $headers .= "MIME-Version: 1.0\r\n"
              ."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
  
  $message .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
      ."Content-Transfer-Encoding: 7bit\r\n\r\n";

  $message .= $bound."\r\n\r\n".$contents."\r\n\r\n".$bound
              .$bound_last;
  mail($to, $subject, $message, $headers);
  
  
  $query = "SELECT username from User where username='$new_username'";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  if($row['username']==null){
      $query2 = "INSERT INTO User (username, password, firstname, lastname, email) values('$new_username', MD5('$new_password'), '$new_f_name', '$new_l_name', '$new_email')";
      //echo $query2;
      $result2 = mysql_query($query2) or die("Query failed: " . mysql_error());
      $_SESSION['username'] = $new_username;
      $_SESSION['firstname'] = $new_f_name;
      $_SESSION['lastname'] = $new_l_name;
      $_SESSION['lastactivity'] = time();
      $_SESSION['msg'] = "Signup success!";
      $_SESSION['msg_flag'] = 1;

      header("Location: viewalbumlist.php");
  }
  else{
      $_SESSION['msg'] = "User name already exists";
      $_SESSION['msg_flag'] = 1;
      header("Location: signup.php");
  }

  mysql_free_result($result);
  mysql_close($conn);
?>

