<?php 
  include('lib.php'); 
  // data submitted by jQuery when carousel left or right is clicked. 
  $url = $_POST['url']; 
  $caption = $_POST['caption'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "Update Contain SET caption= $caption WHERE url= $url";
  echo $query;
  $result = mysql_query($query) or die(mysql_error());

  mysql_free_result($result);
  mysql_close($conn);
?>
