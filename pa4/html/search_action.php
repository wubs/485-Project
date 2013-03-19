<?php
  include('lib.php'); 
  require('server.php');
  $port = "9010";
  $host = "localhost";

  $albumid = $_POST['albumid'];
  $searchterms = $_POST['keyword'];
  if (isset($_POST['cur'])) {
    $cur_pic = $_POST['cur']; 
  }

  $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  $myResults = queryIndex($port, $host, $searchterms);
  if(isset($cur_pic)) {
    $number = sizeof($myResults)-1; 
  } else {
    $number =  sizeof($myResults);
  }

    //Change the html in List
    echo "<hr><h3>Search Result</h3>"
      ."<p>Number of hits: $number</p>"
      ."<p id='time_spent'> </p>"
      ."<table width='100%' height='100%' align='center' valign='center'>"; 
    //$load = empty();   
    $counter = 0; // control two img per row
    $count = 0; // control pic_id
    $num = 4; // how many pics per row

    $i = 0;
    if(sizeof($myResults) >= 1){
      foreach($myResults as $hit) { 
        $seq = $hit['id'];
        $url = "static/images/" . $hit['id'] . ".jpg";

        if (isset($cur_pic) && intval($cur_pic) == intval($seq) ) {
          continue;
        }

        $query = "SELECT caption FROM Contain WHERE sequencenum=$seq and albumid='".$albumid."'";
        $result = mysql_query($query) or die("Query failed: " . mysql_error());
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        

        if ($counter % $num == 0) {
          echo "<tr>"
           . "<td height='200px' align='center'>" 
           . "<img class='img-rounded center click_photo' value=" 
           . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq' albumid='$albumid'>"
           . "<div>" . $row['caption'] . "</div>"
           . "<div>score: " .$hit['score']."</div>" 
           . "</td>";
        } else if($counter % $num == 1){
          echo "<td height='200px' align='center'>" 
           . "<img class='img-rounded center click_photo' value=" 
           . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq' albumid='$albumid'>"
           . "<div>" . $row['caption'] . "</div>"
           . "<div>score: " .$hit['score']."</div>" 
           . "</td>";
        }else if($counter % $num == 2){
           echo "<td height='200px' align='center'>" 
            . "<img class='img-rounded center click_photo' value=" 
           . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq' albumid='$albumid'>"
           . "<div>" . $row['caption'] . "</div>"
           //. "<div>" . $photo['date'] . "</div>"
            . "<div>score: " .$hit['score']."</div>" 
            . "</td>";
        }else if($counter % $num == 3){
            echo "<td height='200px' align='center'>"
            . "<img class='img-rounded center click_photo' value=" 
           . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq' albumid='$albumid'>"
           . "<div>" . $row['caption'] . "</div>"
         //. "<div>" . $photo['date'] . "</div>"
             . "<div>score: " .$hit['score']."</div>" 
             . "</td>"
             . "</tr>";
        }
        $counter++;
        $count++;
      }
    }
    else{
       echo "<p> Result not found.</p>"; 
    }
  
    echo "</table>";

    mysql_close($conn);
    //echo json_encode($load);
?>
