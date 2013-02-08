<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    
    <?php
			$username = $_GET['username']; 
			$conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
			mysql_select_db($db_name) or die("Could not select:" . $db_name);
			
			$query = "SELECT firstname, lastname, email FROM User WHERE username='$username'";
			$result = mysql_query($query) or die("Query failed: " . mysql_error());
			$temp = mysql_fetch_array($result, MYSQL_ASSOC); 
			$first_name = $temp['firstname']; 
			$last_name = $temp['lastname']; 
			$email = $temp['email']; 
			mysql_close($conn);
    ?>
    
    <!-- start edit from here -->
     
		<form class="form-horizontal">
			<input type='hidden' name='username' value='<?php echo $username; ?>'>
			<div class="control-group">
				<label class="control-label" for="inputFirstName">First Name</label>
				<div class="controls">
					<input type="text" id="inputFirstName" name="inputFirstName" placeholder="<?php echo $first_name?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputLastName">Last Name</label>
				<div class="controls">
					<input type="text" name="inputLastName" id="inputLastName" placeholder="<?php echo $last_name?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputEmail">Email</label>
				<div class="controls">
					<input type="text" name="inputEmail" id="inputEmail" placeholder="<?php echo $email?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputPassword">Password</label>
				<div class="controls">
					<input type="password" name="inputPassword" id="inputPassword" placeholder="Enter New Password">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputVerify">Verify Password</label>
				<div class="controls">
					<input type="password" name="inputVerify" id="inputVerify" placeholder="Verify Password">
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
						<button class="btn btn-primary edit" >Submit
						</button><INPUT type="reset" class="btn btn-primary">
				</div>
			</div>
		</form>
		
		<button type="delete" class="btn btn-danger">Delete</button>

    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
    $(function () { // jQuery main()
    $(".edit").live("click", function() {  
          var username = $("#username").val();
          var email = $("#inputEmail").val();
          var password = $("#inputPassword").val();
          var confirm = $("#inputVerify").val();
          var f_name = $("#inputFirstName").val();
          var l_name = $("#inputLastName").val();
          var message = "";
          function validform(){this.value=1;}
          var validateform = new validform();

          validateUsername();
          validateEmail();
          validatePassword();
          validateConfirm();
          validateName();

          if(validateform.value==1){
	          alert(validateform.value);
            $.post("modUser.php", 
              {username: username, inputEmail:email, inputPassword:password, inputFirstName:f_name, inputLastName:l_name},
              function() {
                location.reload();
              }
            );
          }
          else{
            alert("Change failed.");
            alert(message);
            message="";
          }

          function validateUsername(){
            var nameRegex = /^[a-zA-Z0-9\_]+$/;
            if(typeof username=='undefined' || username.length==0){        
               message+="Please enter your username.\n"; 
               validateform.value=0;        
            }
            else if(username.length<3){
               message+="Your username is too short. Must be at least 3 characters.\n";
               validateform.value=0; 
            }
            else{
              var validUsername = document.frm.username.value.match(nameRegex);
              if(validUsername == null){
                message+="Your username is not valid. Only letters, numbers and '_' are  acceptable.\n";
                validateform.value=0;
              }
            }       
            //return message;
         }
        
         function validateEmail(){
            var Regex = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
            if(typeof email=='undefined'||email.length==0){
               message+="Please enter your email.\n";
               validateform.value=0;
            }
            else{
               var validemail = document.frm.email.value.match(Regex);
               if(validemail == null){
                  message+="Please enter a valid email address.\n";
                  validateform.value=0;
               }
            }
         }

         function validatePassword(){
            if(typeof password=='undefined'||password.length==0){
               message+="Please enter your password.\n";
               validateform.value=0;
            }
            else if(password.length<5){
               message+="Password cannot be less than 5 characters.\n";
               validateform.value=0;
            }
            else if(password.length>15){
               message+="Password cannot exceed 15 characters.\n";
               validateform.value=0;
            }
            //return message;
         }
         
         function validateConfirm(){
            if(confirm != password){
              message+="The repeated password does not match!\n";
              validateform.value=0;
            }
            //return message;
         }
         
         function validateName(){
            if(typeof f_name=='undefined' || f_name.length==0){
               message+="Please enter your first name.\n";
               validateform.value=0;
            }
            if(typeof l_name=='undefined' || l_name.length==0){
                message+="Please enter your last name.\n";
                validateform.value=0;
            }
         }
			});
		});
		</script>
    
  </body>
</html>
