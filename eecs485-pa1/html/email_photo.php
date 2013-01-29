<?php 
  include('lib.php'); 

  $new_subject = $_POST['subject'];
  $new_url = $_POST['url'];
  $new_contents = $_POST['contents'];
  $new_from = $_POST['from'];
  $new_to = $_POST['to'];


  $subject = "testing email";
  $to = "wubaishun@gmail.com,wubs@umich.edu,ruoran@umich.edu,dailin@umich.edu";
  $url = "static\images\football_s1.jpg"; 
  $contents = "Hello guys, this is Group36.     I believe email part is working!!\r\n\r\nsent from our website"; 
  $from = "eecs485group36@example.com";
  $filename = explode("static\\images\\",$url);

  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  
  $query = 'SELECT Photo.code, Photo.format FROM Photo WHERE url=Photo.url';
  $result = mysql_query($query) or die(mysql_error());
  $photo = mysql_fetch_array($result, MYSQL_ASSOC);

  $base64 =  $photo['code'];

  $bound_text = "group36";
  $bound =  "--".$bound_text."\r\n";
  $bound_last = "--".$bound_text."--\r\n";
     
  $headers =  "From: ".$from."\r\n";
  $headers .= "MIME-Version: 1.0\r\n"
              ."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
  
  $message .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
      ."Content-Transfer-Encoding: 7bit\r\n\r\n"
        .$contents."\r\n"
          .$bound;

  $message .= $contents."\r\n".$bound;
     
  $message .= "Content-Type: imgae/".$photo['format']."; name=\"".$filename[0]."\"\r\n"
      ."Content-Transfer-Encoding: base64\r\n"
        ."Content-disposition: attachment; file=\"".$filename[0]."\"\r\n"
          ."\r\n"
            .chunk_split($base64)
              .$bound_last;
  mail($to, $subject, $message, $headers);
?>

