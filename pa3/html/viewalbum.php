<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <style> 
  .center {
    height:300px;
    background-color:#b0e0e6;
  }
  #single_block {
    
    overflow-x: hidden;
    overflow-y: hidden;
    height: 500px; 
  }

  #new_viewer {
    display: inline-block;
    height: 500px; 
  }

  .round_border {  // this is for the td
    display: inline-block;
    height: 500px;
    line-height: 500px;
    text-align: center;
    vertical-align: middle;
  }
  .new_image {
    display: inline-block;
    max-height: 100%;
    max-width: 100%; 
    margin-left:auto;
    margin-right:auto;
  }
  #blocker-left, #blocker-right {
    zIndex: 100;
    position: relative;
    height: 500px;
  }
  #blocker-right {
  }
  #blocker-left {
  }
  </style>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <?php 
      $albumid = $_GET['albumid']; 
      $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
      mysql_select_db($db_name) or die("Could not select:" . $db_name);
      $query = "SELECT title, username FROM Album WHERE albumid=$albumid";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      $temp = mysql_fetch_array($result, MYSQL_ASSOC); 
      $album_title = $temp['title']; 
      $album_owner = $temp['username']; 
    ?>
    <ul id="breadcrumb" class="breadcrumb">
      <li><a href="viewalbumlist.php">Album List</a><span class="divider">/</span></li>
      <li class="active">
        <a class="click_back" ref="#">Album: <?php echo "'$album_title', Owner: '$album_owner'"; ?></a><span class="divider">/</span>
      </li>
    </ul>

    <div id="list">
      <!-- start edit from here -->

<!-- When adding a photo, do as follows
		 $path = "/path/to/image.jpg";
     $imagesrc = file_get_contents($path);
		 $base64 = base64_encode($imagedata);
		 $format = pathinfo($path, PATHINFO_EXTENSION);
		 update tables: Contain, Photo, Album;
-->
        <table width="100%" height="100%" algin="center" valign="center">
          <?php 
            //$query = 'SELECT * FROM Contain WHERE albumid=' 
            //  . $albumid . ' ORDER BY sequencenum';
            //Change the query to include the column Photo.code;
						$query = 'SELECT Contain.albumid, Contain.caption, Contain.url, Contain.sequencenum, Photo.code, Photo.format, Photo.date FROM Contain, Photo WHERE Contain.albumid='
							.$albumid
							.' and Contain.url=Photo.url ORDER BY Contain.sequencenum';
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            $counter = 0;
            $num = 2; // how many pics per row
            $photos = array();
            while ($photo = mysql_fetch_array($result, MYSQL_ASSOC) ) {
              array_push($photos, $photo);
							$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; //Fetch the 64Base code for current img
              if ($counter % $num == 0) {
                echo "<tr>"
                  . "<td height='400px' align='center'>" 
                  . "<img class='img-rounded center click_photo' onclick='to_single()' value=" 
                  . ($counter+1) . " src=" . $base64 . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              } else {
                echo "<td height='400px' align='center'>"
                  . "<img class='img-rounded center click_photo' onclick='to_single()' value="
                  . ($counter+1) . " src=" . $base64 . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>"
                  . "</tr>";
              }
              $counter = $counter + 1;
            }
          ?>
        </table>

      </div> <!-- end of div list -->

      <div id="single" style="display:none;z-index:10;">
        <div id="to_list" onclick='to_list()'> Close </div>
        <div id="single_block">
          <div  id="new_viewer">
            <?php
              $count = 0;
              $flag = 0;
              foreach ($photos as $photo) {
                if ($count == 3) break;
			      		$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"';
                if ($flag == 0) {
                  // inline-block of round_border is required !!!!!!
                  echo "<div class='round_border' style='display: inline-block;'>"
                     . "<img class='new_image' " 
                     . "src=" . $base64 . " url=" . $photo['url'] . "></div>";
                  $flag = 1;
                } else {
                  echo "<div class='round_border' style='display: inline-block;'>"
                     . "<img class='new_image' " 
                     . "src=" . $base64 . " url=" . $photo['url'] . "></div>";
                }
                $count++;
              }
              mysql_free_result($result);
              mysql_close($conn);
            ?>
            
           
          </div>
        </div>
            <div id="blocker-left">
              left
            </div>

            <div id="blocker-right">
              right
            </div>
      
      </div> <!-- end of single -->
    <script type="text/javascript">

      function to_single() {
        left = document.getElementById('blocker-left'); 
        right = document.getElementById('blocker-right'); 
        list = document.getElementById('list'); 
        single_block = document.getElementById('single_block');
        single = document.getElementById('single');
        single_width = document.getElementById('breadcrumb').offsetWidth;
        single.style.width = single_width + "px"; 


        new_viewer = document.getElementById('new_viewer');
        single.style.display = "inline";
        list.style.display = "none";

        spans = document.getElementsByClassName('round_border'); 

        td_width = single_block.offsetWidth * 2.0 / 3.0;

        new_viewer.style.left = (- 0.5 * single_block.offsetWidth) + "px";
        new_viewer.style.width = (3 * td_width) + "px";
        new_viewer.style.position = "relative";

        block_width = td_width - 0.5 * single_block.offsetWidth;

        left.style.width = block_width + "px";
        right.style.width = block_width + "px";

        left.style.position = "relative";
        left.style.top = -500 + "px";
        left.style.height = 500 + "px";
        left.style.backgroundColor = "black";

        right.style.position = "relative";
        right.style.top = -1000 + "px";
        right.style.left = single_width - block_width + "px";
        right.style.backgroundColor = "black";
        
        for (var i = 0; i < spans.length; i++ ) {
          spans[i].style.width = td_width + "px";
          spans[i].style.height = 500 + "px";
          spans[i].style.position = "relative";
          var img = spans[i].childNodes[0];
          img.style.maxWidth = "100%";
          img.style.maxHeight = "100%";
          img.style.marginLeft = "auto";
          img.style.marginRight = "auto";
        }

        // focus
        document.body.style.backgroundColor = "black";
      }

      function to_list() {
        console.log("to_list");
        var list = document.getElementById('list'); 
        var single = document.getElementById('single');
        single.style.display = "none";
        list.style.display = "inline";
        document.body.style.backgroundColor = "white";
      }
    </script>

    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function () {
      $("#myCarousel").carousel({
        interval: false 
      });

      //$(".click_back").live("click", function() { 
      //  setTimeout(function() {$("#single").css("display","none");}, 10);
      //  setTimeout(function() {$("#list").css("display", "inline");}, 50);
      //  $("#active_breadcrumb").remove();
      //});

      //$(".click_collapse").live("click", function() { 
      //  $("#comments").collapse('toggle');
      //});

      //$(".carousel-control").live("click", function() {
      //  fetch_comments();
      //  $("#active_breadcrumb").remove();
      //  setTimeout(function () {
      //    var s = $(".item.active > img").attr("url")
      //    var img_name = s.substring(s.lastIndexOf('/')+1);
      //    $(".breadcrumb").append("<li id='active_breadcrumb' class='active'>"+img_name+"</li>");
      //  }, 700);
      //});

      $("#click_newcomment").live("click", function() { 
        // data.datetime data.comments data.username
        var url = $(".item.active > img").attr("url");
        var text = $("#new_comment").val();
        $.post('new_comment.php', {url: url, datetime: '', comments: text, username: ''}, function(data) {
          fetch_comments();
          $("#new_comment").val("");
        });
      });

      $("#click_edit").live("click", function() { 
        if ( $(this).val() == 0 ) {
          $(this).popover("show");
          $(this).val(1);
        } else {
          $(this).popover("hide");
          $(this).val(0);
        }
      });

      $("#email_photo").live("click", function() { 
        var to = $("#email_to").val();
        var url = $(".item.active > img").attr("url")
        $.post('email_photo.php', {subject:'testing subject', url:url, contents:'testing', from:'foo', to:to}, function(data) {
          location.reload();
        });
      });

      $("#click_email").live("click", function() { 
        if ( $(this).val() == 0 ) {
          $(this).popover("show");
          $(this).val(1);
        } else {
          $(this).popover("hide");
          $(this).val(0);
        }
      });


      function fetch_comments() {
        setTimeout(function() {
          var url = $(".item.active > img").attr("url");
          
          $.post('fetch_comments.php', {url: url}, function(raw_data) {
            // empty comments div, poppulate with new data
            // data.datetime data.comments data.username
            var data = $.parseJSON(raw_data);
            var block = "";
            var comments_block = $("#comments");
            $(".commentWell").remove();

            for (i=0;i<data.length;i++) {
              block = "<div class='commentWell'><p>" + data[i].comments + "</p></div>";
              comments_block.append(block);
            }
          });
        }, 700);
      }
    });
      
    </script>
  </body>
</html>
