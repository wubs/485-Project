<?php 
  include('lib.php'); 

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $albumid = $_POST['albumid'];
  $caption = $_POST['caption'];
  $image_name = $_FILES["file"]["name"];
  $image_type = $_FILES["file"]["type"];
  //list($base, $image_type) = $image_type.split("/", $image_type);

  $url = "static/images/$image_name";
  $image_data = chunk_split(base64_encode(file_get_contents($_FILES["file"]["name"])));

  $query = "SELECT url FROM Photo WHERE url='$url' LIMIT 1";
  $result = mysql_query($query) or die(mysql_error());
  $raw = mysql_fetch_array($result, MYSQL_ASSOC);

  // Check url existence  // Check Photo existence
  if ($raw['url'] == null) {
    echo " yes ";
    $query = "INSERT INTO Photo (url, code, format, date) values ('$url', '$image_data', '$image_type', NOW())";
    $result = mysql_query($query) or die("Query failed: " . mysql_error());
  }

  echo " image name " . $image_name;
  echo " immage type " . $image_type;
  echo " aid " . $albumid;
  echo " url " . $url;
  echo " caption " . $caption;
  echo " image_data:" . file_get_contents($_FILES["file"]["tmp_name"]);

  $query = "SELECT url FROM Contain WHERE url='$url' AND albumid=$albumid  LIMIT 1";
  $result = mysql_query($query) or die(mysql_error());
  $raw = mysql_fetch_array($result, MYSQL_ASSOC);
  if ($raw['url'] == null) {
    echo "new in contain";
    $query = "INSERT INTO Photo (url, code, format, date) values ('$url', '$image_data', '$image_type', NOW())";
    $result = mysql_query($query) or die("Query failed: " . mysql_error());
  }

  $query = "SELECT sequencenum FROM Contain ORDER BY sequencenum DESC LIMIT 1";
  $result = mysql_query($query) or die(mysql_error());
  $raw = mysql_fetch_array($result, MYSQL_ASSOC);
  $new_seq = intval($raw['sequencenum']) + 1;

//  $query = "INSERT INTO Contain (albumid, url, caption, sequencenum) values ($albumid, '$url', '$caption', $new_seq)";
//  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  mysql_free_result($result);
  mysql_close($conn);
?>
