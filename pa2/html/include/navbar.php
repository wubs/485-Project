<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <a class="brand" href="index.php"><? echo $config_title ?></a>
      <div class="nav-collapse collapse">
        <ul class="nav">

          <!-- customize bellow -->
          <li><a href="index.php">Home</a></li>
          <li><a href="viewalbumlist.php">Albums</a></li>
          <li><a href="about.php">About</a></li>

        </ul>
        <ul class="nav pull-right"> 
          <form method="post" action="login.php" class="navbar-form pull-left">
            <input class="input-small" type="text" name="username" class="span2" placeholder="Username">
            <input class="input-small" type="password" name="passwd" class="span2" placeholder="Password">
            <button class="btn btn-small btn-warning" type="submit">Login</button>
          </form>
          <li><a href="signup.php">Or sign up</a></li>

        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
