<?php 
  include('lib.php'); 

  $to_username = $_POST['to_username'];
  $albumid = $_POST['albumid'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "SELECT COUNT(*) from User where username='$to_username'";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);

  $data = array();
  if ($row['COUNT(*)'] == 1 || $row['COUNT(*)']=="1") {
    $query = "INSERT INTO AlbumAccess (albumid, username) VALUES ($albumid, '$to_username')";
    $result = mysql_query($query) or die("Query failed: " . mysql_error());
    $data['msg'] = "success";

    $query = "SELECT username FROM User WHERE username != '$username'";
    $result = mysql_query($query) or die("Query failed: " . mysql_error());

    $usernames = array();
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      array_push($usernames, $line['username']);
    }   

    $data['usernames'] = $usernames;

  } else {
    // the user doesn't exist
    $data['msg'] = "failed";
  }
  echo json_encode($data);

  //mysql_free_result($result);
  mysql_close($conn);
?>
