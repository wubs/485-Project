<?php 
  include('lib.php'); 

  $new_op = $_POST['op'];
  $new_username = $_POST['username'];

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  if (empty($_SESSION['admin'])) {
    // if current user is not admin
    // check if the albumid to delete belongs to the current user, $username == $_SESSION['username']
    $_SESSION['msg'] = "You don't have access!";
    header('Location: index.php');
  } else {
    // is admin

    if ($new_op == "grant") {
      $query = "INSERT INTO Admin (username) values ('$new_username');";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      echo "granted";
    } 
    if ($new_op == "remove") {
      $query = "DELETE FROM Admin where username='$new_username';";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      echo "removed";
    }
  }
  mysql_close($conn);
?>
