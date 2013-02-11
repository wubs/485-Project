<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    
    <div class="container">
    <!-- start edit from here -->
    <h2> Please enter your email to reset password </h2>
    <span style="display:<?php echo $display_msg?>"class="label label-warning">
          <?php
            if (isset($_SESSION['msg']))
            {
              echo $_SESSION['msg'];
              unset($_SESSION['msg']);
            }
          ?>
    </span>
    <br>

    <form action='forgetpass.php' method='post' class="form-inline">
      <input type='email' name='email'>
      <input type='submit' class='btn' value="Submit">
    </form>
    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

  </body>
</html>
