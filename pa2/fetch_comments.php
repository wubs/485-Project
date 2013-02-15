<?php 
  include('lib.php'); 
  // data submitted by jQuery when carousel left or right is clicked. 
  $url = $_POST['url']; 

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $query = "select * from Comment where url='$url' ORDER BY commentseqnum  DESC";

  $result = mysql_query($query) or die(mysql_error());

  $comments = array();
  while ($comment = mysql_fetch_array($result, MYSQL_ASSOC) ) {
    $comment_obj = array('datetime'=> '', 'comments'=> $comment['comments'], 'username'=> '');
    array_push($comments, $comment_obj); 
  }

  echo json_encode($comments);

  mysql_free_result($result);
  mysql_close($conn);
?>
