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
  $query = "SELECT firstname, lastname, COUNT(*) FROM User WHERE username='$username'";
  $result = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);

  if($row['COUNT(*)'] == 1) {
    // username exists 

    $query = "SELECT firstname, lastname, COUNT(*) FROM User WHERE username='$username' and password='$passwd20'";
    $result = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);

    if($row['COUNT(*)'] == 1) {
      // username exists and password correct 

      $query = "SELECT COUNT(*) FROM Admin WHERE username='$username'";
      $result = mysql_query($query) or die(mysql_error());
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      if($row['COUNT(*)'] == 1) {
        // This user is an admin
        $_SESSION['admin'] = true;
      }
      
      // write to session
      $_SESSION['username'] = $username;
      $_SESSION['firstname'] = $row['firstname'];
      $_SESSION['lastname'] = $row['lastname'];
      $_SESSION['lastactivity'] = time();
      //query here = select username from User, Admin WHERE 
      // if admin { $_SESSION['admin'] = 1 }
      $_SESSION['msg'] = "login success";
      $_SESSION['msg_flag'] = 1;

      if (isset($_SESSION['tring_to_access'])) {
        $trying_to_access = $_SESSION['tring_to_access'];
        header("Location: $trying_to_access"); 
      } else {
        header("Location: viewalbumlist.php");
      }
    } else { 
      // password not correct 
      $_SESSION['msg'] = "Password incorrect!";
      $_SESSION['msg_flag'] = 1;
      $_SESSION['correct_username'] = $username;
      header("Location: index.php");
    }
  } else {
    // username doesn't exist
    $_SESSION['msg'] = "User doesn't exist!";
    $_SESSION['msg_flag'] = 1;
    header("Location: index.php");
  }
  mysql_free_result($result);
  mysql_close($conn);
?>
