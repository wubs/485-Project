<?php 
  include('lib.php'); 
  $json_string = $_POST['data'];
  $data = json_decode($json_string);
  $albumid = $data->{'albumid'};
  $keyword = $data->{'keyword'};

  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());
  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);
  $query = "select Contain.albumid, Contain.caption, Contain.url, Contain.sequencenum, Photo.code, Photo.format, Photo.date from Contain, Photo where Contain.caption LIKE '%$keyword%' and Contain.albumid = $albumid and Contain.url=Photo.url order by Contain.sequencenum";
  $query2 = "select COUNT(*) from Contain, Photo where Contain.caption LIKE '%$keyword%' and Contain.albumid = $albumid";
  $result = mysql_query($query) or die(mysql_error()); 
  $result2 = mysql_query($query2) or die(mysql_error()); 

  $row = mysql_fetch_array($result2, MYSQL_ASSOC);
  //echo $query2;
  $load="<table width='100%' height='100%' align='center' valign='center'>";        
  $counter = 0; // control two img per row
  $count = 0; // countrol pic_id
  $num = 2; // how many pics per row
 
  $data = array(); 
  if($row['COUNT(*)'] >= 1){
    while ($photo = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $url = $photo['url'];
      $base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; //Fetch the 64Base code for current img
      $url = $photo['url'];

      if ($counter % $num == 0) {
         $load .= "<tr>"
          . "<td height='400px' align='center'>" 
          . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
          . "pic_id='$count' value='" . ($counter+1) . "' src=$base64 url='$url'>"
          . "<div>" . $photo['caption'] . "</div>"
          . "<div>" . $photo['date'] . "</div>"
          . "</td>";
        
      }   
      else {
         $load .= "<td height='400px' align='center'>"
          . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
          . "pic_id='$count' value='" . ($counter+1) . "' src=$base64 url='$url'>"
          . "<div>" . $photo['caption'] . "</div>"
          . "<div>" . $photo['date'] . "</div>" 
          . "</td>"
          . "</tr>";
      } 
      $counter++;
      $count++;
    }
  }
  else{
     $load .="<p>Result not found</p>"; 
  }
  
  $load .= "</table>";
  $data['html'] = $load;

  echo json_encode($data);

  mysql_close($conn);
?>
