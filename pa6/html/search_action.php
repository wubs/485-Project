<?php 
  require('server.php');
  $port = "9010";
  $host = "67.194.200.182";
  $db_name = "pa1_db";
  $db_host = "localhost";
  $db_user = "ruoran";
  $db_passwd ="1216";

  $w = $_POST['w'];
  $searchterms = $_POST['keywrd'];
  
  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  //$searchterms indicates the keyword, $w indicate the w value
  $myResults = queryIndex($port, $host, $searchterms, $w);
  $number=sizeof($myResults);
  
  //Change the html in List

  //Give a summary of the search result
  //echo "<p>Number of hits: $number</p>"
  //  ."<p id='time_spent'> </p>";

    //Set the content of showing the result
  //echo "<table class='table span5' align='center' valign='center'>";
  echo " <div class='span6 pull-right' id='summary'></div>";
  echo "<table class='table span6' align='center' valign='center'>";

    if($number > 0){  
      foreach($myResults as $hit) { 
        $seq = $hit['id']; //the sequence # for the result
        $query = "SELECT title FROM Article WHERE id=$seq";
        $result = mysql_query($query) or die("Query failed: " . mysql_error());
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $title = $row['title'];
        $url = "http://en.wikipedia.org/wiki/".$title;       
        
        echo "<tr><td class=span3>" . $seq . "</td>"
            ."<td><a href='".$url."'>".$title."</a></td>"
            ."<td><a class='btn btn-info show_detail' seq='$seq'>Details</a></td></tr>"; 
      }
    }
    else{
       echo "<p> Result not found.</p>"; 
    }

    echo "</table>";

    mysql_close($conn);
?>
