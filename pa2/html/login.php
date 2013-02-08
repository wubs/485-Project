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
    // login success
    $_SESSION['username'] = $username;
    $_SESSION['firstname'] = $row['firstname'];
    $_SESSION['lastname'] = $row['lastname'];
    $_SESSION['lastactivity'] = time();
    $_SESSION['msg'] = "login success";
    $_SESSION['msg_flag'] = 1;

    if (isset($_SESSION['tring_to_access'])) {
      $trying_to_access = $_SESSION['tring_to_access'];
      header("Location: $trying_to_access"); 
    } else {
      header("Location: viewalbumlist.php");
    }
  } else {
    $_SESSION['msg'] = "login success";
    // login fail
    
    //echo "failed";
    //echo "<p>$username</p>";
    //echo "<p>$passwd</p>";
    //echo "<p>$md5passwd</p>";
    //echo "<p>$passwd20</p>";
    $_SESSION['msg'] = "login error";
    $_SESSION['msg_flag'] = 1;
    header("Location: index.php");
  }
?>
