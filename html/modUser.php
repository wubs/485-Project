<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->
    
    <?php
    	$username = $_POST["username"]; 

			$conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
			mysql_select_db($db_name) or die("Could not select:" . $db_name);			
			$query = "SELECT firstname, lastname, email, password FROM User WHERE username='$username'";
    	$result = mysql_query($query) or die("Query failed: " . mysql_error());
			$temp = mysql_fetch_array($result, MYSQL_ASSOC); 
			
			$firstname = $_POST["inputFirstName"]; 
			if(empty($firstname))
				$firstname = $temp['firstname']; 
				
			$lastname = $_POST["inputLastName"]; 
			if(empty($lastname))
				$lastname = $temp['lastname']; 
				
			$email = $_POST["inputEmail"]; 
			if(empty($email))
				$email = $temp['email']; 
				
			$password = $_POST["inputPassword"]; 
			if(empty($password))
				$password = $temp['password']; 
		
			echo "<p>Successfully Edit User Information! </p>".
					 "<p>First Name: ".$firstname."</p>".
					 "<p>Last Name: ".$lastname."</p>".
					 "<p>Email: ".$email."</p>";
			
			$password = md5($password);
			$query = "UPDATE User SET firstname='$firstname', lastname='$lastname', email='$email', password='$password' WHERE username='$username'";
			$result = mysql_query($query) or die("Query failed: " . mysql_error());
		
			mysql_free_result($result);
			mysql_close($conn);
		?>



    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
