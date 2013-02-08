<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    
    <div class="container">
    <!-- start edit from here -->
    <h1> Welcome, New Member! </h1>
    <div class="row-fluid">
    <div class="span8">
        <form action="addnewuser.php" method="post" name="frm" accept-charset="utf-8">
            <p>
            <label><label for="username">Username</label></label>
            <input id="username" name="username" type="text">           
            </p>

            <p>
            <label><label for="email">Email</label></label>
            <input id="email" name="email" type="text">            
            </p>

            <p>
            <label><label for="password">Password</label></label>

            <input id="password" name="password" type="password">

            
            </p>

            <p>
            <label><label for="confirm">Repeat Password</label></label>

            <input id="confirm" name="confirm" type="password">

            
            </p>

            <p>
            <label><label for="f_name">First Name</label></label>

            <input id="f_name" name="f_name" type="text">

            
            </p>

            <p>
            <label><label for="l_name">Last Name</label></label>

            <input id="l_name" name="l_name" type="text">

            
            </p>
            <input class="btn btn-success" type="submit" name="signup" value="Sign Up">
        </form>
        

    </div>
    <div class="span4">
    </div>
</div>


    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function () { // jQuery main()
    $(".signup").live("click", function() {  
          var username = $("#username").val();
          var email = $("#email").val();
          var password = $("#password").val();
          var confirm = $("#confirm").val();
          var f_name = $("#f_name").val();
          var l_name = $("#l_name").val();
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
            $.post("addnewuser.php", 
              {username: username, email:email, password:password, f_name:f_name, l_name:l_name},
              function() {
                location.reload();
              }
            );
          }
          else{
            alert("Sign up failed.");
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
