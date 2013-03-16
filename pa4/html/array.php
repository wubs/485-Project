<?php
  include('lib.php'); 
  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "SELECT url FROM Contain WHERE albumid = 5 and sequencenum <5 order by sequencenum";
  $result = mysql_query($query) or die(mysql_error()); 
  var_dump($result);

  mysql_close($conn);
?>
