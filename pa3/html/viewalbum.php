<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <style> 
  .center {
    height:300px;
    background-color:#b0e0e6;
  }
  #single {
    width: 940; 
  /*
    height: 400px; 
*/
  }
  .round_border {  // this is for the td
    position: relative;
    left: -600px;
    vertical-align: middle;
    width: 600px;
    height: 400px; 
   /* border: 2px solid black; */
  }
  .new_image {
    max-height: 600px;
    max-width: 400px; 
    margin-left:auto;
    margin-right:auto;
  }
  .new_viewer {
    background-color: black;
  }
  #blocker-left, #blocker-right {
    zIndex: 100;
    position: relative;
    height: 400px;
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
    <ul class="breadcrumb">
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
        <table style="overflow: hidden" id="new_viewer">
        <tr style="width: auto; overflow: hidden">
          <?php
            $count = 0;
            $flag = 0;
            foreach ($photos as $photo) {
              if ($count == 3) break;
			  			$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"';
              if ($flag == 0) {
                echo "<td class='item active round_border'>"
                   . "<img class='new_image' " 
                   . "src=" . $base64 . " url=" . $photo['url'] . "></td>";
                $flag = 1;
              } else {
                echo "<td class='item round_border'>"
                   . "<img class='new_image' " 
                   . "src=" . $base64 . " url=" . $photo['url'] . "></td>";
              }
              $count++;
            }
            mysql_free_result($result);
            mysql_close($conn);
          ?>
        </tr> <!-- end of div single -->
        <!--
        <div id="blocker-left">
        </div>

        <div id="blocker-right">
        </div>
        -->
      </table>

    </div> <!-- end of new viewer -->
    <script type="text/javascript">
      var left = document.getElementById('blocker-left'); 
      var right = document.getElementById('blocker-right'); 
      var viewer = document.getElementById('new_viewer'); 

      function to_single() {
        console.log("to_single");
        var list = document.getElementById('list'); 
        var single = document.getElementById('single');
        single.style.display = "inline";
        list.style.display = "none";

        var tds = document.getElementsByClassName('round_border');
        var td_width = tds[tds.length - 1].offsetWidth;
        var td_height = tds[tds.length - 1].offsetHeight;
        console.log(2*td_width);
        right.style.left = 2 * td_width + "px";

        //console.log(left.offsetHeight);
        //console.log(right.offsetHeight);
        //console.log(viewer.offsetHeight);

        right.style.top = (- left.offsetHeight - viewer.offsetHeight) + "px";
        //console.log(right.style.top);
        left.style.top = (- viewer.offsetHeight) + "px";

        right.style.width = td_width + "px";
        left.style.width = td_width + "px";

        right.style.height = td_height + "px";
        left.style.height = td_height + "px";

        right.style.backgroundColor = "black";
        left.style.backgroundColor = "black";
      }

      function to_list() {
        console.log("to_list");
        var list = document.getElementById('list'); 
        var single = document.getElementById('single');
        single.style.display = "none";
        list.style.display = "inline";
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
