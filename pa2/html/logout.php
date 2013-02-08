<?php 
  session_start();
  session_destroy();
  session_start();
  $_SESSION['msg'] = "logout success";
  header("Location: index.php");
?>
