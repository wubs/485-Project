<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <style> 
  .cap {
    position: relative;
    bottom: 0px;
    height: 10%;
    color: white;
  }
  .center {
    height:200px;
    background-color:#b0e0e6;
  }

  #single {
    height: 700px; 
  }
  #single_block {
    position: relative;
    overflow-x: hidden;
    overflow-y: hidden;
    height: 520px; 
  }

  #new_viewer {
    display: inline-block;
    height: 520px; 
  }

  .round_border {  /* this is for the td */
    display: inline-block;
    height: 520px;
    /*line-height: 520px;*/
    text-align: center;
    vertical-align: middle;
    margin-right:auto;
    z-index: 200;
  }
  .new_image {    /* inside round_border */
    display: inline-block;
    max-height: 80%;
    max-width: 90%; 
    margin-left:auto;
    margin-right:auto;
    padding: 5%;
  }
  #blocker-left, #blocker-right {
    z-index: 100;
    position: relative;
    height: 520px;
  }
  #blocker-right {
  }
  #blocker-left {
  }
</style>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->

      <?php 
        $albumid = 5; 
        $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
        mysql_select_db($db_name) or die("Could not select:" . $db_name);
        $query = "SELECT title, username FROM Album WHERE albumid=$albumid";
        $result = mysql_query($query) or die("Query failed: " . mysql_error());
        $temp = mysql_fetch_array($result, MYSQL_ASSOC); 
        $album_title = $temp['title']; 
        $album_owner = $temp['username']; 
      ?>

      <div id="offset2">
        <span style="display:<?php echo $display_msg?>"class="label label-warning">
          <?php
            if (isset($_SESSION['msg']))
            {
              echo $_SESSION['msg'];
              unset($_SESSION['msg']);
            }
          ?> 
        </span>
        <h2> Search </h2>
        <form class="form-inline" action="#">
          <input id="keyword" type="text" placeholder="keyword" name="keyword">
          <a class= "btn btn-success click_search" albumid = "5">Search</a>
        </form>
      </div>

      <div id="list">
      <!-- start edit from here -->
        <table width="100%" height="100%" align="center" valign="center">
          <?php 
            $query = 'SELECT Contain.albumid, Contain.caption, Contain.url, Contain.sequencenum, Photo.date FROM Contain, '
              .'Photo WHERE Contain.albumid='
              .$albumid
              . ' and Contain.url=Photo.url ORDER BY Contain.sequencenum';
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            $counter = 0; // control two img per row
            $count = 0; // countrol pic_id
            $num = 4; // how many pics per row
            $photos = array();
            while ($photo = mysql_fetch_array($result, MYSQL_ASSOC) ) {
              array_push($photos, $photo);
              //$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; //Fetch the 64Base code for current img
              $url = $photo['url'];
              $seq = $photo['sequencenum'];
              if ($counter % $num == 0) {
                echo "<tr>"
                  . "<td height='200px' align='center'>" 
                  . "<img class='img-rounded center click_photo' method = 'post' value=" 
                  . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq'>"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              } else if($counter % $num == 1){
                echo "<td height='200px' align='center'>" 
                  . "<img class='img-rounded center click_photo' method = 'post' value=" 
                  . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq'>"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              }else if($counter % $num == 2){
                echo "<td height='200px' align='center'>" 
                  . "<img class='img-rounded center click_photo' method = 'post' value=" 
                  . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq'>"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              }else if($counter % $num == 3){
                echo "<td height='200px' align='center'>"
                  . "<img class='img-rounded center click_photo' method = 'post' value=" 
                  . "pic_id='$count' value='" . ($counter+1) . "' src=$url seq='$seq'>"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>"
                  . "</tr>";
              }
              $counter++;
              $count++;
            }
          ?>
        </table>

      </div> <!-- end of div list -->
    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type = "text/javascript">

    function ajax_post(url, data, callback) {
        var httpRequest = new XMLHttpRequest();
        var url = url;
        var data = JSON.stringify(data);
        var callback = callback;

        var handler = function() {
          if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
              // action
              returned_obj = JSON.parse(httpRequest.responseText);
              callback(returned_obj);
              // action
            } else {
              alert('There was a problem with the request.');
            }
          }
        };

        httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = handler;
        httpRequest.open('POST', url);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send('data=' + encodeURIComponent(data));
    }

    $(function () {
        $(".click_search").live("click", function(){
          var id = $(this).attr('albumid');
          var keywrd = $("#keyword").val();
          alert("search: "+id+" "+keywrd);
          $.post('search_action.php', {'albumid':id, 'keyword':keywrd}, function(data){
            var list = document.getElementById("list");
            list.innerHTML = data;
          });
        });

        $(".click_photo").live("click", function() { 
          var seq = $(this).attr("seq");
          //$.post('viewphoto.php',{'url':url}, function(data){
          //  alert(url);
          //});
          location.href = "viewphoto.php?seq="+seq;
        });
   });
    </script>
  </body>
</html>
