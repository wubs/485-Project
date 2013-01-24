<?php
  $url = $_POST['url'];
  $albumid = $_POST['albumid'];
  $seq = $_POST['seq'];
  $action = $_POST['action'];  // left or right 


  $conn = mysql_connect($db_host, $db_user, $db_passwd)
    or die("Connect Error: " . mysql_error());

  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = 'SELECT * FROM Contain WHERE albumid='
          . $albumid . ' ORDER BY sequencenum';

  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  $next_url     // next seq's url
  

  $comments
?>
