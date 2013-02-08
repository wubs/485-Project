<?php 
  include('lib.php');
  session_start();

  $username = $_POST['username'];
  $passwd = $_POST['passwd'];
  $md5passwd = md5($passwd);
  $passwd20 = substr($md5passwd, 0, 20);
  

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
    or die("Connect Error: " . mysql_error());

  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  $query = "SELECT firstname, lastname, COUNT(*) FROM User WHERE username='$username' and password='$passwd20'";
  $result = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);

  if($row['COUNT(*)'] == 1) {
    $_SESSION['username'] = $username;
    $_SESSION['firstname'] = $row['firstname'];
    $_SESSION['lastname'] = $row['lastname'];
    header("Location: viewalbumlist.php");
  } else {
    echo "failed";
    echo "<p>$username</p>";
    echo "<p>$passwd</p>";
    echo "<p>$md5passwd</p>";
    echo "<p>$passwd20</p>";
  }
?>
