<?php 
  echo "<h1> hi </h1>";
  include_once('lib.php'); 

  $new_username = $_POST['username'];
  $id = $_POST['albumid'];
  
  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  // TO-DO validate title

  $query = "DELETE FROM AlbumAccess where albumid = '$id' and username = '$new_username'";

  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  mysql_close($conn);
  /*
  foreach ($all_albums as $album) {
    echo $album->$name . $album->$access
  }
  */
?>
