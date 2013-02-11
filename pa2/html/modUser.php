<?php
	include('lib.php');
	
	$username = $_POST['username']; 
	$firstname = $_POST['f_name']; 
	$lastname = $_POST['l_name']; 
	$email = $_POST['email']; 
	$password = $_POST['password']; 

	$conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
	mysql_select_db($db_name) or die("Could not select:" . $db_name);
	
	if (!empty($firstname)) {
		$query = "UPDATE User SET firstname='$firstname' WHERE username = '$username'";
		$result = mysql_query($query) or die("Query failed: " . mysql_error());
	}
	
	if (!empty($lastname)) {
		$query = "UPDATE User SET lastname ='$lastname' WHERE username = '$username'";
		$result = mysql_query($query) or die("Query failed: " . mysql_error());
	}

	if (!empty($email)) {
		$query = "UPDATE User SET email='$email' WHERE username = '$username'";
		$result = mysql_query($query) or die("Query failed: " . mysql_error());
	}
	
	if (!empty($password)) {
		$query = "UPDATE User SET password=MD5('$password') WHERE username = '$username'";
		$result = mysql_query($query) or die("Query failed: " . mysql_error());
	}
	
	mysql_free_result($result);
	mysql_close($conn);
	header("Location: edituser.php");
	
?>
