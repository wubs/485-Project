<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->

<?php 
  include('lib.php'); 

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $albumid = $_POST['albumid'];
  $caption = $_POST['caption'];
  $image_name = $_FILES["file"]["name"];
  $image_type = $_FILES["file"]["type"];
  $pieces = explode("/",  $image_type);
  $image_type = strtolower($pieces[1]);

  $query = "SELECT COUNT(*) FROM Album WHERE username='$username' and albumid=$albumid";
  $result = mysql_query($query) or die(mysql_error());
  $raw = mysql_fetch_array($result, MYSQL_ASSOC);

  if ($raw['COUNT(*)'] < 1) {
    // wrong use tring to upload
    $_SESSION['msg'] = "You don't have privilege";
    header("Location: index.php");
  } else {
    // right user uploading
    $supported_ext = array("jpg", "gif", "png", "bmp", "tif");

    if ($image_type == "jpeg" ) {
      $image_type == "jpg";
    } else if ( !in_array($image_type, $supported_ext) ) {
      echo "Image type not supported";
      return;
    } 

    $url = "static/images/$image_name";
    $image_data = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

//    echo " image name " . $image_name;
//    echo " image type " . $image_type;
//    echo " aid " . $albumid;
//    echo " url " . $url;
//    echo " caption " . $caption;
//    echo " image_data:" . base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

    $query = "SELECT url FROM Photo WHERE url='$url' LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $raw = mysql_fetch_array($result, MYSQL_ASSOC);

    // Check url existence  // Check Photo existence
    if ($raw['url'] == null) {
      echo "<p>Added new photo</p>";
      $query = "INSERT INTO Photo (url, code, format, date) values ('$url', '$image_data', '$image_type', NOW())";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
    }

    $query = "SELECT sequencenum FROM Contain ORDER BY sequencenum DESC LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $raw = mysql_fetch_array($result, MYSQL_ASSOC);
    $new_seq = intval($raw['sequencenum']) + 1;

    $query = "SELECT url FROM Contain WHERE url='$url' AND albumid=$albumid  LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $raw = mysql_fetch_array($result, MYSQL_ASSOC);
    if ($raw['url'] == null) {
      echo "<p>Added photo to album</p>";
      $query = "INSERT INTO Contain (albumid, url, caption, sequencenum) values ($albumid, '$url', '$caption', $new_seq)";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
    } else {
      echo "<p> Photo already exists in this album </p>";
    }
  }

  //mysql_free_result($result);
  mysql_close($conn);
?>

<!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
