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


      //
      // Ruoran: Baishun, 这里要判断一下上面哪些值 是非空的。
      // 然后动态的创建下面这样子的Query。因为每次的field不一样。
      //
      // $query2 = "INSERT INTO User (username, password, firstname, lastname, email) values('$new_username', MD5('$new_password'), '$new_f_name', '$new_l_name', '$new_email')";
      
			echo "<td>".$firstname."</td>";
			echo "<td>".$lastname."</td>";
			echo "<td>".$email."</td>";
			echo "<td>".$password."</td>";
			echo "<td>".$verify_password."</td>";	

      //
      // 做好了就 uncomment below statement
      //
      // header("Location: edituser.php");
      //
      // 因该就可以了
		?>



    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
