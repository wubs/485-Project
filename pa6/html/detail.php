<?php 

  require('db.php');

  $seq = $_POST['seq'];
  
  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  
  //Get variables from database
  $query = "SELECT url FROM imageUrl WHERE id=$seq";
  error_log($query);
  $result = mysql_query($query) or die("Query failed 1: $query " . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $img_url = $row['url'];
 
  $query = "SELECT body FROM Article WHERE id=$seq";
  $result = mysql_query($query) or die("Query failed 2: " . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $body = $row['body'];
  
  $query = "SELECT summary FROM infoBox WHERE id=$seq";
  $result = mysql_query($query) or die("Query failed 3: " . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $summary = $row['summary'];
  
  echo "<div class='span5' style='margin-bottom: 20px;' id='close'>"
    ."<a class='btn btn-danger click_close pull-right' style='align:right;margin-left:15px'>Close</a>"
    ."<a class='span2 btn btn-success show_vis pull-right' style='align:right;'>Visulize similar</a>"
    ."</div><div class='span5 row' id='summary'>"
    ."<div><img src='$img_url'/></div><br>";

  $query = "SELECT category FROM Category WHERE id=$seq";
  $result = mysql_query($query) or die("Query failed 4: " . mysql_error());
  echo "<h2>Categories</h2>";
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) { 
    echo "<div class='catWell'>".$row['category']."</div>";
  }

  if ($summary != "\n") {
    echo "<h2>Info Box</h2><div class='myWell'>$summary</div><br>";
  }

  echo "<h2>Abstract</h2><div class='myWell'>$body</div>";
    

  
  echo "</div>";


    mysql_close($conn);
    //echo json_encode($load);
?>
