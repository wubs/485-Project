<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->




<?php 
  include('lib.php'); 
  $new_op = $_POST['op'];
  $new_albumid = $_POST['albumid'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  
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


<!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
