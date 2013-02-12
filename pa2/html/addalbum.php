<?php 
  include('lib.php'); 
  session_start();
  $new_op = $_POST['op'];
  $new_title = $_POST['title'];
  $new_access = $_POST['access'];
  $new_username = $_SESSION['username'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  // TO-DO validate title

  $query = "INSERT INTO Album (title, created, lastupdated, access, username) values ('$new_title', NOW(), NOW(), '$new_access', '$new_username')";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  $query = "INSERT INTO AlbumAccess (albumid, username) values (LAST_INSERT_ID(), '$new_username')";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  //mysql_free_result($result);
  mysql_close($conn);
  /*
  foreach ($all_albums as $album) {
    echo $album->$name . $album->$access
  }
  */
?>
