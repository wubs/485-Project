<?php 
  session_start();

  $sensitive_array = Array("/myalbumlist.php", "/edituser.php", "/editalbumlist.php", "/delete_photo.php", "/modUser.php", "/viewmyalbum.php", "/email_photo.php");


  $cur_url = $_SERVER["REQUEST_URI"];

  if (empty($_SESSION['username'])) {
    // user not logged in  ----- visitor
    
    if (in_array($cur_url, $sensitive_array)) {
      session_destroy();
      session_start();
      $_SESSION['tring_to_access'] = $cur_url;
      header("Location: sensitive.php");
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

    if (time() - $_SESSION['lastactivity'] > 5 * 60) {
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
    $admin_display = true;
  }   
  // check if user allow to enter here

  // redirect if necessary


  // some variables
   
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
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <a class="brand" href="<?php echo $home_url;?>"><?php echo $config_title ?></a>
      <div class="nav-collapse collapse">
        <ul class="nav">

          <!-- customize bellow -->
          
          <li><a href="<?php echo $home_url; ?>">Home</a></li>

          <li style="display:<?php echo $login_display; ?>"><a href="viewalbumlist.php">All Albums</a></li>
          <li style="display:<?php echo $user_display; ?>"><a href="myalbumlist.php">My Albums</a></li>
        </ul>

        <!-- not logged in yet -->
        <ul style="display:<?php echo $login_display; ?>" class="nav pull-right"> 
          <form method="post" action="login.php" class="navbar-form pull-left">
            <input class="input-small" type="text" name="username" class="span2" placeholder="Username">
            <input class="input-small" type="password" name="passwd" class="span2" placeholder="Password">
            <button class="btn btn-small btn-warning" type="submit">Login</button>
          </form>
          <li><a href="signup.php">Or sign up</a></li>

        </ul>

        <!-- logged in -->
        <ul style="display:<?php echo $user_display; ?>" class="nav pull-right"> 
          <li>
            <a href='<?php echo "edituser.php"; ?>'>
              <?php echo $username; if ($admin_display) { echo "(admin)"; }?></a>
          </li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
