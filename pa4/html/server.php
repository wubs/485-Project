<?php

  include('lib.php'); 

  $json_string = $_POST['data'];
  $data = json_decode($json_string);
  $albumid = $data->{'albumid'};
  $searchterms = $data->{'keyword'};


  $conn = mysql_connect($db_host, $db_user, $db_passwd)
  or die("Connect Error: " . mysql_error());  
  mysql_select_db($db_name) or die("Could not select:" . $db_name);

  /*
		$PORT = the port on which we are connecting to the "remote" machine
		$HOST = the ip of the remote machine (use 'localhost' if the same machine)
	*/
	function queryIndex($port, $host, $searchterms)
	{
		// Send HTTP GET request to server. GET is the default option for cURL, so don't set it.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$host:$port/search?q=".urlencode($searchterms));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resp = curl_exec($ch);
		curl_close($ch);

    

		// Decode response message and send it back to caller
		$resp = json_decode($resp, true);
		$results = $resp["hits"];
  
		return $results;
  }
    //used for testing
   /* if($searchterms == "a"){
      $results = array(
        "id" => array(1, 2),
        "score" => array(1.5, 2.5)      
      );
    }
    else{
      $results = array();
    }*/



    //end of testing


    //Change the html in List
    $load="<table width='100%' height='100%' align='center' valign='center'>";        
    $counter = 0; // control two img per row
    $count = 0; // control pic_id
    $num = 2; // how many pics per row

    $i = 0;
    if(sizeof($results) >= 1){
      while ($i<sizeof($results)) {
        $photo['id'] = array_slice($results['id'], $i, 1);
        $photo['score'] = array_slice($results['score'], $i, 1);
        $url = '/static/images/'.$photo['id'].'.jpg';  
        $query = "SELECT Contain.caption, Photo.date FROM Contain, Photo WHERE Contain.url = '$url' and Contain.url=Photo.url ";
        $result = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_array($result2, MYSQL_ASSOC);
        if ($counter % $num == 0) {
           $load .= "<tr>"
            . "<td height='400px' align='center'>" 
            . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
            . "pic_id='$count' value='" . ($counter+1) . "' src='$url'>"
            . "<div>" . $row['caption'] . "</div>"
            . "<div>" . $row['date'] . "</div>"
            . "<div>" . $photo['score'] . "</div>"
            . "</td>";
        
        }   
        else {
           $load .= "<td height='400px' align='center'>"
            . "<img class='img-rounded center click_photo' onclick='to_single(this)'"
            . "pic_id='$count' value='" . ($counter+1) . "' src=$base64 url='$url'>"
            . "<div>" . $row['caption'] . "</div>"
            . "<div>" . $row['date'] . "</div>" 
            . "<div>" . $photo['score'] . "</div>"
            . "</td>";
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
    $results['html'] = $load;
    
    mysql_close($conn);
    echo json_encode($results);
  
?>
