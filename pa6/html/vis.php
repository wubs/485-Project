<?php 
  require('server.php');
  $port = "9010";
  $host = "67.194.200.182";
  $db_name = "pa1_db";
  $db_host = "localhost";
  $db_user = "ruoran";
  $db_passwd ="1216";

  $id = $_POST['id'];
  
  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  //$searchterms indicates the keyword, $w indicate the w value
  //
  
  $myResults = queryVis($port, $host, $id);
  $number=sizeof($myResults);
  


  $node_list = array();

  $query = "SELECT title FROM Article WHERE id=$id";
  $result = mysql_query($query) or die("Query failed: " . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $r_title = $row['title'];
  $r_data = array('$dim'=>'15');
  $r_adj = array();

  

  if($number > 0){  
    foreach($myResults as $hit) { 
      $seq = $hit['id']; //the sequence # for the result
      $weight = $hit['score']; //the sequence # for the result

      $query = "SELECT title FROM Article WHERE id=$seq";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      $row = mysql_fetch_array($result, MYSQL_ASSOC);

      $title = $row['title'];

      $data = array('$dim'=>'5');

      $adj_data = array('weight' => $weight);
      $adj = array('nodeTo' => $id, 'data' => $adj_data);
      
      $node = array('id' => $seq, 'name' => $title, 'data' => $data, 'adjacencies' => $adj);

      array_push($node_list, $node);

      $r_adj_data = array('weight' => $weight);
      $r_adj_one = array('nodeTo' => $seq, 'data' => $r_adj_data);
      array_push($r_adj, $r_adj_one); 
    }
    $root_node = array('id' => $id, 'name' => $r_title, 'data' => $r_data, 'adjacencies' => $r_adj);
  }

  array_unshift($node_list, $root_node);

  echo json_encode($node_list);

  mysql_close($conn);
?>
