<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    
    <?php
			$username = $_SESSION['username']; 
			$conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
			mysql_select_db($db_name) or die("Could not select:" . $db_name);
			
			$query = "SELECT firstname, lastname, email FROM User WHERE username='$username'";
			$result = mysql_query($query) or die("Query failed: " . mysql_error());
			$temp = mysql_fetch_array($result, MYSQL_ASSOC); 
			$first_name = $temp['firstname']; 
			$last_name = $temp['lastname']; 
			$email = $temp['email']; 
			mysql_free_result($result);
      mysql_close($conn);
    ?>
    
    <!-- start edit from here -->
     
		<form name="frm" action="modUser.php" method="post">
		
			<input type='hidden' name='username' value='<?php echo $username; ?>'>
			
			<div class="control-group">
				<label class="control-label" for="f_name">First Name</label>
				<div class="controls">
					<input type="text" id="f_name" name="f_name" placeholder="<?php echo $first_name?>">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="l_name">Last Name</label>
				<div class="controls">
					<input type="text" name="l_name" id="l_name" placeholder="<?php echo $last_name?>">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="email">Email</label>
				<div class="controls">
					<input type="text" name="email" id="email" placeholder="<?php echo $email?>">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="password">Password *</label>
				<div class="controls">
					<input type="password" name="password" id="password" placeholder="Enter New Password">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="confirm">Verify Password *</label>
				<div class="controls">
					<input type="password" name="confirm" id="confirm" placeholder="Verify Password">
				</div>
			</div>
      <p><b> * indicates required filed </b></p>
      <p><b>Note: Please enter and verify your password to change your names and email.</b></p>
			
			<div class="control-group">
				<div class="controls">
						<input class="btn btn-primary edit" value="Submit"> </button>
            <input type="reset" class="btn btn-primary">
		        <input class="btn btn-danger delete" value="Delete"></button>
				</div>
			</div>
			
		</form>
		

    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
    $(function () { // jQuery main()
    $(".edit").live("click", function() {  
          var username = $("#username").val();
          var email = $("#email").val();
          var password = $("#password").val();
          var confirm = $("#confirm").val();
          var f_name = $("#f_name").val();
          var l_name = $("#l_name").val();
          
          var message = "";
          function validform(){this.value=1;}
          var validateform = new validform();
          validateEmail();
          validatePassword();
          validateConfirm();

          if(validateform.value==1){
          	$("form").submit();
          }
          else{
            alert(message);
            signup.disabled="disabled";
          }

        
         function validateEmail(){
            var Regex = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
            if(email.length != 0){
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
			});
		});
		
		$(function () { // jQuery main()
    $(".delete").live("click", function() {  
          var username = '<?php echo $username; ?>';
          $.post("deleteuser.php", 
            {"username": username},
            function(data) {
              window.location.replace("logout.php");
            }
          );   
          
			});
		});
		
		
		</script>
    
  </body>
</html>
