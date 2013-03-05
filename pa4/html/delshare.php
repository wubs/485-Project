<?php 
  include_once('lib.php'); 

  $json_string = $_POST['data'];
  $data = json_decode($json_string);
  $new_username = $data->{'username'};
  $albumid = $data->{'albumid'};

  $data = array();

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  // TO-DO validate title

  $query = "DELETE FROM AlbumAccess where albumid = '$albumid' and username = '$new_username'";

  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  // other_users
  $query = "SELECT username FROM User WHERE username != '$username'";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  $other_users= array();
  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    array_push($other_users, $line['username']);
  }   
  // other_users done


  // shared users
  $session_user = $_SESSION['username'];
  $query = "SELECT username FROM AlbumAccess where albumid=$albumid and username!='$session_user'";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());

  $shared_users= array();
  while ($user_row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    array_push($shared_users, $user_row['username']);
  }   
  // shared_users done

  $data['other_users'] = $other_users;
  $data['shared_users'] = $shared_users;

  echo json_encode($data);

  mysql_close($conn);
?>
