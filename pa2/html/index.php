<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->
      <div class="offset2">
        <span style="display:<?php echo $display_msg?>"class="label label-warning">
          <?php
            if (isset($_SESSION['msg']))
            {
              echo $_SESSION['msg'];
              unset($_SESSION['msg']);
            }
          ?> 
        </span>
        <h2> PA2 </h2>
        <p> This is <?php echo $config_title ?> <b>group36</b> </p>
        <h3>(spacejunkie is admin) </h3>
      </div>
      <div class="offset2 row-fluid">
        <div class="span4">
          <h2> Team members </h2>
          <?php 
            foreach ($config_team_members as $member) {
              echo "<li>$member</li>";
            }
          ?>
        </div>
        <div class="span4">
          <h2> Users </h2>
          <?php
            $conn = mysql_connect($db_host, $db_user, $db_passwd)
               or die("Connect Error: " . mysql_error());
                
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            $query = "SELECT * FROM User";
            $result = mysql_query($query) or die("Query failed: " . mysql_error());

            while ($user = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo "<li>".$user['username']."</li>";
              }
            mysql_free_result($result);
            mysql_close($conn);
          ?>
          <!---
          <form action="viewalbumlist.php", method="get"> 
            <input type="text" name="username" placeholder="user id">
            <input class="btn" type="submit" value="go">
          </form>
          -->
        </div>
      </div>
    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
