<?php
  include('lib.php'); 
  require('server.php');
  $port = "9010";
  $host = "localhost";

  $albumid = $_POST{'albumid'};
  $searchterms = $_POST{'keyword'};

  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $myResults = queryIndex($port, $host, $searchterms);

    //Change the html in List
    $load="<table width='100%' height='100%' align='center' valign='center'>";        
    $counter = 0; // control two img per row
    $count = 0; // control pic_id
    $num = 4; // how many pics per row

    $i = 0;
    if(sizeof($myResults) >= 1){
      foreach($myResults as $hit) { 
        $seq = $hit['id'];
        $url = "static/images/" . $hit['id'] . ".jpg";

        $query = "SELECT caption FROM Contain WHERE sequencenum=$seq";
        $result = mysql_query($query) or die("Query failed: " . mysql_error());
        $row = mysql_fetch_array($result, MYSQL_ASSOC);

        if ($counter % $num == 0) {
           $load .= "<tr>"
            . "<td height='400px' align='center'>" 
            . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
            . "pic_id='$count' value='" . ($counter+1) . "' src='$url' seq='$seq'>"
            . "<div>" . $row['caption'] . "</div>"
            . "<div>" . $row['date'] . "</div>"
            . "<div>" . $hit['score'] . "</div>"
            . "</td>";
        
        }   
        else if($counter % $num == 1){
           $load .= "<td height='400px' align='center'>"
            . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
            . "pic_id='$count' value='" . ($counter+1) . "' src='$url' seq='$seq'>"
            . "<div>" . $row['caption'] . "</div>"
            . "<div>" . $row['date'] . "</div>" 
            . "<div>" . $hit['score'] . "</div>"
            . "</td>";
        } 
        else if($counter % $num == 2){
           $load .= "<td height='400px' align='center'>"
            . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
            . "pic_id='$count' value='" . ($counter+1) . "' src='$url' seq='$seq'>"
            . "<div>" . $row['caption'] . "</div>"
            . "<div>" . $row['date'] . "</div>" 
            . "<div>" . $hit['score'] . "</div>"
            . "</td>";
        } 
        else if($counter % $num == 3){
           $load .= "<td height='400px' align='center'>"
            . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
            . "pic_id='$count' value='" . ($counter+1) . "' src='$url' seq='$seq'>"
            . "<div>" . $row['caption'] . "</div>"
            . "<div>" . $row['date'] . "</div>" 
            . "<div>" . $hit['score'] . "</div>"
            . "</td>"
            . "</tr>";
        } 
        $counter++;
        $count++;
        $i++;
        }
    }
    else{
       $load .="<p>Result not found.</p>"; 
    }
  
    $load .= "</table>";

    mysql_close($conn);
    echo json_encode($load);
?>
