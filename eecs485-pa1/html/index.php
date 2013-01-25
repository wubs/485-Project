<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->

      <h2> Welcome! </h2>
      <p> This is <?php echo $config_title ?></p>
      <p> Team members: </p>
      <?php 
        foreach ($config_team_members as $member) {
          echo "<li>$member</li>";
        }
      ?>
      <form action="viewalbumlist.php", method="get"> 
        <input type="text" name="username" placeholder="user id">
        <input class="btn" type="submit" value="go">
      </form>

    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
