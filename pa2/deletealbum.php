<?php 
  include('lib.php'); 
  include('include/navbar.php');
  $new_op = $_POST['op'];
  $new_albumid = $_POST['albumid'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);


  if (empty($_SESSION['admin'])) {
    // if current user is not admin
    // check if the albumid to delete belongs to the current user, $username == $_SESSION['username']
    $query = "SELECT COUNT(*) from Album where albumid=$new_albumid and username='$username'";
    $result = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    if ($row['COUNT(*)'] < 1) {
      // this user is not the own of album
      $_SESSION['msg'] = "You don't have access!";
      header('Location: index.php');
    }
  }

  $query = "DELETE from Album where albumid=$new_albumid";

  $result = mysql_query($query) or die(mysql_error());
  
  $query = "DELETE from AlbumAccess where albumid = '". $new_albumid ."'";

  $result = mysql_query($query) or die(mysql_error());
  
  $query = "SELECT url, albumid, COUNT(*) FROM Contain GROUP BY url";   

  $result_url = mysql_query($query) or die("Query failed: " . mysql_error());

  while($row = mysql_fetch_array($result_url,MYSQL_ASSOC)){
    if($row['COUNT(*)'] == 1 && $row['albumid']==$new_albumid)
    { 
      $query = "DELETE from Comment where url='". $row['url'] ."'";
    
		  $result = mysql_query($query) or die(mysql_error());
    }
  }
  
  $query = "SELECT url, albumid, COUNT(*) FROM Contain GROUP BY url";   
  $result_url = mysql_query($query) or die("Query failed: " . mysql_error());
  
  $query = "DELETE from Contain where albumid = '". $new_albumid ."'";
  $result = mysql_query($query) or die(mysql_error());
  
	while($row = mysql_fetch_array($result_url,MYSQL_ASSOC)){
    if($row['COUNT(*)'] == 1 && $row['albumid']==$new_albumid)
    { 
      $query = "DELETE from Photo WHERE url = '". $row['url'] ."'";
			$result2 = mysql_query($query) or die(mysql_error());
    }
  }
  
  $query = "DELETE from Album where albumid = '". $new_albumid ."'";
  $result = mysql_query($query) or die(mysql_error());


  mysql_free_result($result);
  mysql_close($conn);
?>
