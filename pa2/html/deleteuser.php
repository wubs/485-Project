<?php
	include('lib.php');
	$username = $_POST['username']; 
	
	$conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

	$query = "SELECT albumid FROM AlbumAccess WHERE username = '".$username."'";	
  $result_id = mysql_query($query) or die(mysql_error());
	
	while($temp = mysql_fetch_array($result_id, MYSQL_ASSOC)){
		$new_albumid = $temp['albumid']; 
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
  
  }
    
  $query = "DELETE from User where username = '". $username ."'";
  $result = mysql_query($query) or die(mysql_error());

  $query = "DELETE FROM Admin where username = '$username'";
  $result = mysql_query($query) or die(mysql_error());
	
  //mysql_free_result($result);
  mysql_close($conn);
	
?>
