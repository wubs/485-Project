<?php 
  include('lib.php'); 
  $url = $_POST['url']; 

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "select format, code from Photo where url='$url';";

  $result = mysql_query($query) or die(mysql_error());

  $photo = mysql_fetch_array($result, MYSQL_ASSOC);
  
  // $base64
  // front-end src=$base64 
  $base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; 


  echo json_encode($base64);

  mysql_close($conn);
?>
