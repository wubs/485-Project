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
    height: 700px; 
  }
  #single_block {
    position: relative;
    overflow-x: hidden;
    overflow-y: hidden;
    height: 500px; 
  }

  #new_viewer {
    display: inline-block;
    height: 500px; 
  }

  .round_border {  /* this is for the td */
    display: inline-block;
    height: 500px;
    line-height: 500px;
    text-align: center;
    vertical-align: middle;
    margin-right:auto;
    z-index: 200;
  }
  .new_image {    /* inside round_border */
    display: inline-block;
    max-height: 90%;
    max-width: 90%; 
    margin-left:auto;
    margin-right:auto;
    padding: 5%;
  }
  #blocker-left, #blocker-right {
    z-index: 100;
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
						$query = 'SELECT Contain.albumid, Contain.caption, Contain.url, Contain.sequencenum, Photo.code, Photo.format, Photo.date FROM Contain, Photo WHERE Contain.albumid='
							.$albumid
							.' and Contain.url=Photo.url ORDER BY Contain.sequencenum';
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            $counter = 0; // control two img per row
            $count = 0; // countrol pic_id
            $num = 2; // how many pics per row
            $photos = array();
            while ($photo = mysql_fetch_array($result, MYSQL_ASSOC) ) {
              array_push($photos, $photo);
							$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; //Fetch the 64Base code for current img
              if ($counter % $num == 0) {
                echo "<tr>"
                  . "<td height='400px' align='center'>" 
                  . "<img class='img-rounded center click_photo' onclick='to_single(this)' "
                  . "pic_id='" . $count . "'value=" 
                  . ($counter+1) . " src=" . $base64 . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              } else {
                echo "<td height='400px' align='center'>"
                  . "<img class='img-rounded center click_photo' onclick='to_single(this)' "
                  . "pic_id='" . $count . "'value=" 
                  . ($counter+1) . " src=" . $base64 . ">"
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

      <div id="single" style="display:none;z-index:10;">

        <div id="to_list" onclick='to_list()'> <h3>Close<h3> </div>

        <div id="single_block">
          <div id="new_viewer">
            <?php
              $count = 0;
              $flag = 0;
              foreach ($photos as $photo) {
                $base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"';
                // inline-block of round_border is required !!!!!!
                echo "<div class='round_border'>"
                  . "<img class='new_image' pic_id='" . $count ."'" 
                  . "src=" . $base64 . " url=" . $photo['url'] . "></div>";
                $count++;
              }
              mysql_free_result($result);
              mysql_close($conn);
            ?>
          </div>
        </div>
        <div id="blocker-left">
        </div>

        <div id="blocker-right">
        </div>
        <input type='hidden' id="last_pic_id" value="<?php echo $count-1; ?>">
      
      </div> <!-- end of single -->
    <script type="text/javascript">

      // global left position
      left_pos = null;
      new_viewer = null;

      pre_left_pos = null;

      starting_pos = null;
      starting_left = null;

      cur_pic_id = null;
      
      last_pic_id = document.getElementById('last_pic_id').getAttribute('value');

      function swipe_start(e) {
        var e = e || window.event;
        if (new_viewer == null) {
          return true;
        }
        if (e.target.id != "to_list") {
          pre_left_pos = e.clientX;
          starting_pos = e.clientX;
          //console.log("starting Swip x: " + starting_pos);
          starting_left = position_to_int(new_viewer.style.left);
          document.onmousemove = mouse_move;
          return false;
        }
        return false;
      }

      function mouse_move(e) {
        var e = e || window.event;
        if (new_viewer == null) {
          return true;
        }
        move_viewer(e.clientX - pre_left_pos);
        pre_left_pos = e.clientX;
      }

      function restore_left_pos() {
        // move back to original left
        console.log("back to original left");
        var step = move_slowly(starting_left);
        int_handle = setInterval(step, 10);
      }
      function move_to_next_left(starting_left, distance_swiped, direction) {
        if (direction > 0) {
          if ( cur_pic_id >= last_pic_id ) {
            restore_left_pos();
          } else {
            // move right
            console.log("moving right");
            var step = move_slowly(starting_left - td_width);
            int_handle = setInterval(step, 10);
            cur_pic_id++;
          }
        } else {
          // move left
          if ( cur_pic_id == 0 ) {
            restore_left_pos();
          } else {
            console.log("moving left");
            var step = move_slowly(starting_left + td_width);
            int_handle = setInterval(step, 10);
            cur_pic_id--;
          }
        }
      }

      function swipe_end(e) {
        var e = e || window.event;

        if (new_viewer == null) {
          return true;
        } else {

          //console.log("end x : " + e.clientX);
          var direction = starting_pos - e.clientX;
          //console.log("dir : " + direction);
          var distance_swiped = Math.abs(direction);

          console.log(distance_swiped);
          // after swipe down, finish the left distance,
          // that means distance_swiped + more to move = td_width
          // or restore original left

          document.onmousemove = null;
          document.onmousedown = null;
          if (distance_swiped >= td_width / 4.0 ) {
            // finish rest of the move
            move_to_next_left(starting_left, distance_swiped,  direction);
          } else {
            restore_left_pos();
          }
        }

      }

      function to_single(target) {
        // start the event handlers
        document.onmousedown = swipe_start; 
        document.onmouseup = swipe_end; 

        // pick up the pic id to show
        //
        // Global
        cur_pic_id = target.getAttribute('pic_id');
        console.log("cur pic: " + cur_pic_id);

        var left = document.getElementById('blocker-left'); 
        var right = document.getElementById('blocker-right'); 

        // this should be the frame to view a single picture
        single = document.getElementById('single');
        // get screen width
        single_width = document.getElementById('breadcrumb').offsetWidth;
        single.style.width = single_width + "px"; 
        single.style.display = "inline";
        single.style.position = "absolute";
        single.style.top = window.pageYOffset + 90 + "px";

        // this is the viewer, overflow hidden 
        single_block = document.getElementById('single_block');
        var single_block_w = single_width;
        single_block.style.position = "relative";
        single_block.style.width = single_block_w * 0.7 + "px";
        single_block.style.left = single_block_w * 0.15 + "px";

        // this is the scrollable part 
        new_viewer = document.getElementById('new_viewer');

        list = document.getElementById('list'); 
        //list.style.display = "none";
        list.style.opacity = "0.4";

        spans = document.getElementsByClassName('round_border'); 

        td_width = single_block_w * 2.0 / 3.0;

        // most important left shift
        // when id = 0 show first pic
        left_pos = (- 0.65 * single_block_w - (cur_pic_id - 1) * td_width) + "px";
        new_viewer.style.left = left_pos;
        //new_viewer.style.backgroundColor = "black";
        single_block.style.backgroundColor = "black";

        // five is 3 pic plus two edges
        // pic_count 
        var pic_count = last_pic_id + 1;
        new_viewer.style.width = (pic_count * (td_width + 5) ) + "px";
        new_viewer.style.position = "relative";
        
        for (var i = 0; i < spans.length; i++ ) {
          spans[i].style.width = td_width + "px";
          spans[i].style.height = 500 + "px";
          spans[i].style.position = "relative";
        }

        // focus
        //document.body.style.backgroundColor = "black";
      }

      function to_list() {
        clearInterval(int_handle);
        var list = document.getElementById('list'); 
        var single = document.getElementById('single');
        single.style.display = "none";
        list.style.display = "inline";
        list.style.opacity = "1";

        left_pos = null;
        cur_pic_id = null;
        new_viewer = null;
        document.body.style.backgroundColor = "white";
        document.onmousedown = null;
        document.onmouseup = null; 
      }

      function move_viewer(distance) {
        // if distance is positive -> right
        // if distance is negative -> left
        new_viewer = document.getElementById('new_viewer');
        new_viewer.style.left = position_to_int(new_viewer.style.left) + distance + "px";
      }

      function move_slowly(abs_left_pos) {

        this.period = 50.0;
        this.abs_left_pos = abs_left_pos;
        this.cur_left_pos = position_to_int(new_viewer.style.left);
        this.step = Math.abs(this.abs_left_pos - this.cur_left_pos) / 10.0;


        var move_a_step = function() {
          // get cur pos
          this.cur_left_pos = position_to_int(new_viewer.style.left);

          if (Math.abs(cur_left_pos- abs_left_pos) > 10) {
            if (cur_left_pos > abs_left_pos) {
              //console.log("move slow right");
              new_viewer.style.left = (cur_left_pos - this.step) + "px";
            } else {
              //console.log("move slow left");
              new_viewer.style.left = (cur_left_pos + this.step) + "px";
            }
          } else {
            // int_handle is a global variable
            new_viewer.style.left = this.abs_left_pos + "px";
            clearInterval(int_handle);

            // clear global variables here;
            int_handle = null;
            pre_left_pos = null;
            starting_pos = null;
            starting_left = null;
            document.onmousedown = swipe_start;
          }
        }

        return move_a_step;
      }

      function position_to_int(pos) {
        var i = parseInt(pos.replace('px', ''));
        if (i) {
          return i;
        } else {
          return 0;
        }
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
