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
  
  echo "<table class='table span6' align='center' valign='center'>";

    if($number > 0){  
      foreach($myResults as $hit) { 
        $seq = $hit['id']; //the sequence # for the result
        $weight = $hit['score']; //the sequence # for the result
        
        echo "<tr><td class=span3>" . $seq . "</td>"
            ."<td>$weight</td>"
            ."<td><a class='btn show_detail' seq='$seq'>Details</a></td></tr>"; 
      }
    }
    else{
       echo "<p> Result not found.</p>"; 
    }

    echo "</table>";

    mysql_close($conn);
?>
