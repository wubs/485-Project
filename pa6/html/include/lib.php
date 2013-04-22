<?php
  include('db_config.php');
  session_start();

  // general attributes
  $config_title = "EECS485 PA 4";
  $config_team_members = array("Baishun Wu", "Dailin Liu", "Ruoran Wang");
  
  $sensitive_array = Array("myalbumlist.php", "viewalbum.php", "edituser.php", "addalbum.php","editalbumlist.php", "delete_photo.php", "modUser.php", "viewmyalbum.php", "email_photo.php", "editalbum.php",  "deleteuser.php", "delshare.php", "share.php");

  // If visitor was trying to access those page, after login, they will be directed there.
  $redirect_allow = Array("myalbumlist.php", "grant_remove_admin.php", "editalbumlist.php", "viewmyalbum.php");

  $admin_only = Array("manage.php", "admin_edituser.php", "admin_editalbumlist.php", "admin_editalbum.php", "admin_delphoto..php");

  $cur_url = basename($_SERVER["REQUEST_URI"]); // this is important for site to work on production

  if (empty($_SESSION['username'])) {
    // user not logged in  ----- visitor

    if (in_array($cur_url, $sensitive_array)) {
      session_destroy();
      session_start();
      if (in_array($cur_url, $redirect_allow)) {
        $_SESSION['tring_to_access'] = $cur_url;
      }
  
      header("Location: sensitive.php");
      exit;
    }

    $login_display = "inline";
    $user_display = "none";
    $username = "visitor";
    $firstname = "Anonymous";
    $lastname = "Visitor";

  } else {
    // if user logged in
    //
    // 1. Check timeout or Update lastactivity

    if (time() - $_SESSION['lastactivity'] > 5 * 60 && empty($_SESSION['msg'])) {
      // in active for 5 mins, login again
      session_destroy();
      header("Location: timeout.php");
    } else {
      $_SESSION['lastactivity'] = time();
    }

    //
    $login_display = "none";
    $user_display = "inline";
    $username = $_SESSION['username'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
  }

  // check admin
  $admin_display = false;
  if (!empty($_SESSION['admin'])) {
    // user is admin
    $admin_display = true;
  } else { 
    // user is not admin
    if (in_array($cur_url, $admin_only)) {
      $_SESSION['msg'] = "You don't have privilege.";
      header("Location: index.php");
    }
  }  

  if($user_display=="inline") { 
    $home_url = "viewalbumlist.php";
  } else { 
    $home_url = "index.php";
  }

  if (!empty($_SESSION['msg'])) {
    $display_msg = "inline";
  } else {
    $display_msg = "none";
  }
?>
