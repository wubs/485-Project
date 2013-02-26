<?php 
  include('lib.php'); 

  $json_string = $_POST['data'];
  $data = json_decode($json_string);
  $to_username = $data->{'to_username'};
  $albumid = $data->{'albumid'};

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "SELECT COUNT(*)from User where username='$to_username'";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);

  $query2 = "SELECT access from Album where albumid='$albumid'";
  $result2 = mysql_query($query2) or die("Query failed ". mysql_error());
  $row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
  $data = array();
  if($row2['access']=='public'){
    //No sharing for public albums
    $data['msg'] = "public, ignore action";
  }
  else if ($row['COUNT(*)'] == 1 || $row['COUNT(*)']=="1") {
    $query = "INSERT IGNORE AlbumAccess (albumid, username) VALUES ($albumid, '$to_username')";
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
    $data['msg'] = "success";

  } else {
    // the user doesn't exist
    $data['msg'] = "failed";
  }
  echo json_encode($data);

  //mysql_free_result($result);
  mysql_close($conn);
?>
