<?php 
  include('lib.php'); 
  $new_id = $_GET['albumid'];
  $new_title = $_GET['title'];
  $new_access = $_GET['access'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  // TO-DO validate title

  //$query = "INSERT INTO Album (title, created, lastupdated, access, username) values ('$new_title', NOW(), NOW(), '$new_access', '$new_username')";

  $query = "UPDATE Album SET title='$new_title', lastupdated=NOW(), access='$new_access' WHERE albumid = '$new_id'";
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
