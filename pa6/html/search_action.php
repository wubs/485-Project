<?php 
  require('server.php');
  $port = "9010";
  $host = "localhost";

  $w = $_POST['w'];
  $searchterms = $_POST['keywrd'];

  //$searchterms indicates the keyword, $w indicate the w value
  //$myResults = queryIndex($port, $host, $searchterms, $w);
  //$number=sizeof($myResults);
  $number=0;
  $myResult=array();
  //Change the html in List
  //resize the search textboxes
  echo "<div style='text-align:left'><h3> Wiki Search Result</h3></div>"
    ."<form class='form-inline' style='text-align:left' action='#'>"
    ."<input id='keyword' type='text' style = 'width:40%' placeholder='".$searchterms."' name='keyword'>"
    ."<br><br><p>"
    ."<a class= 'btn btn-success click_back'>Back</a>"
    ."</form></div><hr>";

    //Give a summary of the search result
    echo "<p>Number of hits: $number</p>"
      ."<p id='time_spent'> </p>";

    //Set the content of showing the result
    echo "<table class='table' align='center' valign='center'>";
    if($number > 0){  
      foreach($myResults as $hit) { 
        //$seq = $hit['id']; //the sequence # for the result
        //$url = $hit['url']; //the url for the page
        //$img_url = $hit['img_url']; //the url for the image
        //$text = $hit['text']; //the summary for the page
        //$title = $hit['title']; //the title used to show the link ?? is it necessary?
        echo "<tr><td>".$seq."</td><td><a href='$url'>".$title."</a></td>"
          ."<td><a value=false class='btn btn-info show_detail' rel='popover' data-html=true" 
          ." data-trigger='click' data-placement='right'"
          ."data-content='<img src= $img_url align='center'><br>$text' >"
          ."Details</a></td></tr>";
      }
    }
    else{
       echo "<p> Result not found.</p>"; 
    }
  
    echo "</table>";

    //mysql_close($conn);
    //echo json_encode($load);
?>
