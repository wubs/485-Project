<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->
    
    <?php
			$firstname = $_POST['inputFirstName']; 
			$lastname = $_POST['inputLastName']; 
			$email = $_POST['inputEmail']; 
			$password = $_POST['inputPassword']; 
			$verify_password = $_POST['inputVerify']; 
			
			echo "<td>".$firstname."</td>";
			echo "<td>".$lastname."</td>";
			echo "<td>".$email."</td>";
			echo "<td>".$password."</td>";
			echo "<td>".$verify_password."</td>";	
		?>



    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
