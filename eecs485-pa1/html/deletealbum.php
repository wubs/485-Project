<?php 
  include('lib.php'); 
  $new_op = $_POST['op'];
  $new_albumid = $_POST['albumid'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "DELETE from Album where albumid =". $new_albumid;

  $result = mysql_query($query) or die(mysql_error());
  
  $query = "DELETE from AlbumAccess where albumid =". $new_albumid;

  $result = mysql_query($query) or die(mysql_error());
  
  $query = "SELECT url, COUNT(*) FROM Contain WHERE albumid =". $new_albumid." GROUP BY url";   

  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    if($row['COUNT(*)'] == 1)
    {
      $query = "DELETE from Photo WHERE url = '". $row['url'] ."'";
      $result2 = mysql_query($query) or die(mysql_error());
      unlink($row['url']);
    }
  }

  $query = "DELETE from Contain where albumid =". $new_albumid;
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