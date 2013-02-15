<?php 
  include('lib.php'); 

  $new_albumid = $_POST['albumid'];
  $new_url = $_POST['url'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $current_user = $_SESSION['username'];

  $query = "SELECT COUNT(*) from Contain, Album where Contain.albumid =". $new_albumid. " and Contain.url='".$new_url."' and Album.username='$current_user' ";
  $result = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);

  if ($row['COUNT(*)'] !=1) {
    $_SESSION['msg'] = "Action not allowed";
    echo "index";
  } else {
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
      }
    $query = "UPDATE Album SET lastupdated=NOW() WHERE albumid=".$new_albumid;
    $result = mysql_query($query) or die(mysql_error());
  }
  mysql_close($conn);
?>
