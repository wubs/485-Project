<?php include_once('../lib.php'); ?>
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
          <li><a href="search.php">Search</a></li>
          <li style="display:<?php echo $login_display; ?>"><a href="viewalbumlist.php">All Albums</a></li>
          <li style="display:<?php echo $user_display; ?>"><a href="myalbumlist.php">My Albums</a></li>
        </ul>

        <!-- not logged in yet -->
        <ul style="display:<?php echo $login_display; ?>" class="nav pull-right"> 
          <form method="post" action="login.php" class="navbar-form pull-left">
          <input class="input-small" type="text" name="username" class="span2" placeholder="Username" value="<?php if(isset($_SESSION['correct_username'])) echo $_SESSION['correct_username'] ?>">
            <input class="input-small" type="password" name="passwd" class="span2" placeholder="Password">
            <button class="btn btn-small btn-warning" type="submit">Login</button>
          </form>
          <li><a href="signup.php">Sign up</a></li>
          <li><a href="ask_email.php">Forget password?</a></li>

        </ul>

        <!-- logged in -->
        <ul style="display:<?php echo $user_display; ?>" class="nav pull-right"> 
          <li>
            <a href='<?php echo "edituser.php"; ?>'>
              <?php echo $username; if ($admin_display) { echo " (admin)"; }?></a>
          </li>
          <li><a href="logout.php">Logout</a></li>
        </ul>

        <!-- admin -->
        <ul style="display:<?php if ($admin_display) {echo "inline";} else {echo "none";} ?>" class="nav pull-right"> 
          <li>
            <a href='<?php echo "manage.php"; ?>'> Manage Site </a>
          </li>
        </ul>

      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
