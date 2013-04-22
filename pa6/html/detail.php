<?php 

  $db_name = "pa1_db";
  $db_host = "localhost";
  $db_user = "ruoran";
  $db_passwd ="1216";

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
    ."<a class='btn btn-danger click_close pull-right' style='align:right;'>Close</a>"
    ."</div><div class='span5 row' id='summary'>"
    ."<div><img src='$img_url'/></div><br>"
    ."<div>$summary</div><br>"
    ."<div>$body</div>";
    
  $query = "SELECT category FROM Category WHERE id=$seq";
  $result = mysql_query($query) or die("Query failed 4: " . mysql_error());

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) { 
    echo "<div>".$row['category']."</div>";
  }
  
  echo "</div>";

  //Set the content of showing the result
  //echo "<table class='table span5' align='center' valign='center'>";
  echo "<div class='span12 row' id='list'>"
    ."<div class='span5' id='result'>"
    ."<table class='table span5' align='center' valign='center'>";



    echo "<script>$(function () {"
      ."$('.click_close').live('click', function(){"
      ."   var list = document.getElementById('summary');"
      ."   list.innerHTML='';"
      ."  });"
      ."}); </script>";
   

    //mysql_close($conn);
    //echo json_encode($load);
?>
