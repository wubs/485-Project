<?php 
  include('lib.php'); 
  $new_albumid = $_POST['albumid'];
  $new_url = $_POST['url'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "DELETE from Contain where albumid =". $new_albumid. " and url='".$new_url."'";

  $result = mysql_query($query) or die(mysql_error());

	$query = "DELETE from Comment where url='".$new_url."'";
  
	$result = mysql_query($query) or die(mysql_error());
  
  $query = "SELECT albumid FROM Contain WHERE url='". $new_url."'"; 

  $result = mysql_query($query) or die("Query failed: " . mysql_error());


  $row = mysql_fetch_array($result,MYSQL_ASSOC);
    if($row['albumid'] == null)
    {
      $query = "DELETE from Photo WHERE url='". $new_url."'";
      $result2 = mysql_query($query) or die(mysql_error());
      unlink($row['url']);
    }
	echo $query;
  $query = "UPDATE Album SET lastupdated=NOW() WHERE albumid=".$new_albumid;
  $result = mysql_query($query) or die(mysql_error());

  // find album list after deletion 
  //$query = "SELECT * FROM Album";   

  //$result = mysql_query($query) or die("Query failed: " . mysql_error());
  // array of dada 
  
  //echo  data;

  mysql_free_result($result);
  mysql_close($conn);
  /*
  foreach ($all_albums as $album) {
    echo $album->$name . $album->$access
  }
  */
?>
