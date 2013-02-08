<?php 
  session_start();

  if (empty($_SESSION['username'])) {
    // user not logged in  ----- visitor
    $login_display = "inline";
    $user_display = "none";
  } else {
    // if user logged in
    $login_display = "none";
    $user_display = "inline";
    $username = $_SESSION['username'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
  }

  // check admin
  
  $admin_display = false;
  if (empty($_SESSION['admin'])) {
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

          <li><a href="viewalbumlist.php">All Albums</a></li>
          <li><a href="myalbumlist.php">My Albums</a></li>
          <li><a href="about.php">About</a></li>
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
            <a href='<?php echo "edituser.php?username=$username"; ?>'>
              <?php echo $username; if ($admin) { echo "(admin)"; }?></a>
          </li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
