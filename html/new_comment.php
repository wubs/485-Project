<?php 
  include('lib.php'); 
  // data submitted by jQuery when carousel left or right is clicked. 
  $comments = $_POST['comments']; 
  $url = $_POST['url']; 
  #$username = $_POST['username']; 
  #$datetime = $_POST['datetime']; 

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);


  $query = "SELECT commentseqnum FROM Comment ORDER BY commentseqnum DESC LIMIT 1";
  $result = mysql_query($query) or die(mysql_error());
  $raw = mysql_fetch_array($result, MYSQL_ASSOC);
  $next_seq = intval($raw['commentseqnum']) + 1;

  $query = "INSERT INTO Comment (url, commentseqnum, comments) values ('$url', $next_seq, '$comments')";
  $result = mysql_query($query) or die(mysql_error());

  mysql_free_result($result);
  mysql_close($conn);
?>
