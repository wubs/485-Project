<?php 
  include('lib.php'); 
  include('include/navbar.php'); 
  $new_username = $_POST['username'];
  $id = $_POST['albumid'];
  
  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  // TO-DO validate title

  $query = "DELETE FROM AlbumAccess where albumid = '$id' and username = '$new_username'";
  echo $query;

  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  mysql_free_result($result);
  mysql_close($conn);
  /*
  foreach ($all_albums as $album) {
    echo $album->$name . $album->$access
  }
  */
?>
