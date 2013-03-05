<?php 
  include('lib.php'); 
  $json_string = $_POST['data'];
  $data = json_decode($json_string);
  $url = $data->{'url'};

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "select Photo.format, Photo.code, Contain.caption from Photo, Contain where Photo.url='$url' and Contain.url='$url'";

  $result = mysql_query($query) or die(mysql_error());

  $photo = mysql_fetch_array($result, MYSQL_ASSOC);
  
  // $base64
  // front-end src=$base64 
  $data = array();
  $data['src'] = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; 
  $data['cap'] = $photo['caption'];


  echo json_encode($data);

  mysql_close($conn);
?>
