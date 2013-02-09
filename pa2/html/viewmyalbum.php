<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <style> 
  .center {
    height:300px;
    background-color:#b0e0e6;
  }
  .photoModal {
    left:5%; 
    width:90%; 
    height:600px;
    margin-left:0px; 
  }

  .myWell {
    padding: 20px;
    height: auto;
    width: 50%;
    margin: 0px auto;
    margin-top: 10px;
    margin-bottom: 40px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
  }
  .commentWell {
    padding: 20px;
    height: auto;
    margin-top: 10px;
    margin-left: 0px;
    background-color: #f5f5f5;
    border: 4px solid #e3e3e3;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
  }
  .newcommentWell {
    padding: 20px;
    height: auto;
    margin-top: 10px;
    margin-left: 0px;
    background-color: #f5f5f5;
    border: 4px solid #e3e3e3;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
  }
  .round_border {
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
  }
  .btn-file {
    position:relative;
    overflow:hidden;
  }
  .btn-file > input {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    font-size: 23px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
    transform: translate(-300px, 0) scale(4);
    direction: ltr;
  }
  input[type="file"] {
    height: 30px;
    line-height: 30px;
    width: auto;
  }
  .fileupload-preview {
    display: inline-block;
    margin-bottom: 5px;
    overflow: hidden;
    text-align: center;
    vertical-align: middle;
  }
  .fileupload .thumbnail > img {
    display: inline-block;
    max-height: 100%;
    vertical-align: middle;
  }
  .opt {
    width: 25%;  
    padding: 0px;
    padding-top: 5px;
    padding-bottom: 5px;
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
      <li><a href="myalbumlist.php">My Albums </a><span class="divider">/</span></li>
      <li class="active">
        <a class="click_back" ref="#">Album: <?php echo "'$album_title', Owner: '$album_owner'"; ?></a><span class="divider">/</span>
      </li>
    </ul>

    <div id="list">
      <!-- start edit from here -->

        <a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">Add Photo</a>
        <!-- Modal -->
        <div id="myModal" class="modal hide fade" style="width:auto;left:60%" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-body">

            <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type='hidden' name='albumid' value='<?php echo $albumid; ?>'>
            <!-- file uploader -->
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-preview thumbnail" style="width: 300px; height: 250px;"></div>
              <div>
                <input style="width:296px" type="text" placeholder="Caption" name="caption">
              </div>
              <div>
                <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists"></span><input type="file" name="file" id="file" /></span>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                <input class="btn btn-primary pull-right" type="submit" name="addSubmit" value="Upload">
              </div>
            </div>
            </form>

          </div>
        </div>  

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
                  . "<img class='img-rounded center click_photo' value=" 
                  . ($counter+1) . " src=" . $base64 . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              } else {
                echo "<td height='400px' align='center'>"
                  . "<img class='img-rounded center click_photo' value="
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
        <!-- Start of Carousel -->
        <div id="myCarousel" class="carousel slide round_border" style="height:630px;;margin-bottom:0px">
          <!-- Carousel items -->
          <div class="carousel-inner" style="height: 100%; width:100%;">
          <?php
            $flag = 0;
            foreach ($photos as $photo) {
              //if ($flag == 0) {
              //  echo "<div class='item active round_border'>"
              //     . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
              //     . "src=" . $photo['url'] . "><div class='carousel-caption'><p>" 
              //     . $photo['caption'] . "</p></div></div>";
              //  $flag = 1;
              //} else {
              //  echo "<div class='item round_border'>"
              //     . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
              //     . "src=" . $photo['url'] . "><div class='carousel-caption'><p>" 
              //     . $photo['caption'] . "</p></div></div>";
              //}
							$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; //Fetch the 64Base code for current img
              if ($flag == 0) {
								
                echo "<div class='item active round_border'>"
                   . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
                   . "src=" . $base64 . " url=" . $photo['url'] . "></div>";
                $flag = 1;
              } else {
                echo "<div class='item round_border'>"
                   . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
                   . "src=" . $base64 . " url=" . $photo['url'] . "></div>";
              }
            }
            mysql_free_result($result);
            mysql_close($conn);
          ?>
          </div>
          <!-- Carousel nav -->
          <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
          <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
        </div>
        <!-- End of Carousel -->


        <div  class="myWell" > <!-- Buttons and comments -->

          <div class="row-fluid btn-group">
              <a href="#" role="button" class="btn click_back opt" >Back</a>

              <a id="click_email" value=false class="btn opt" rel="popover" data-html=true 
                data-trigger="click" data-placement="top"
                data-content="<input id='email_to' type='email' style='width:194px'><a albumid='<?php echo $albumid ?>' id='email_photo' class='btn'>Send</a>" >
                Email
              </a>

              <a id="click_edit" value=false class="btn opt" rel="popover" data-html=true 
                data-trigger="click" data-placement="top"
                data-content="<a id='delete_photo' albumid='<?php echo $albumid ?>' class='btn btn-danger'>Del</a>">
                Edit
              </a>
              <a role="button" class="btn btn-info click_comments click_collapse opt" >Comments</a>
          </div>

          <!-- Start of Comments -->
          <div id="comments" class="collapse">
            <p style="color:#f5f5f5"> placeholder </p>
            <textarea id="new_comment" rows ="3" placeholder="New comment" style="width:70%;display:inline-block" ></textarea>
            <a id="click_newcomment" role="button" class="btn btn-info" style="display:inline-block;margin-bottom:15px;margin-left:30px;padding:15px 20px 15px 20px;" >Submit</a>
          </div>
          <!-- End of Comments-->

        </div> <!-- End of Buttons and comments -->

        <!-- End of Comments-->

      </div> <!-- end of div single -->

    <!-- edit above -->
    </div> <!-- /container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
    <script src="static/js/bootstrap-fileupload.js"></script>

    <script type="text/javascript">
    $(function () {
      $("#myCarousel").carousel({
        interval: false 
      });

      $(".click_photo").live("click", function() { 
        $("#myCarousel").carousel(parseInt($(this).attr('value')) - 1); 
        setTimeout(function() {$("#list").css("display", "none");}, 350);
        setTimeout(function() {$("#single").css("display","inline");}, 550);
        fetch_comments();
        setTimeout(function () {
          var s = $(".item.active > img").attr("url")
          var img_name = s.substring(s.lastIndexOf('/')+1);
          $(".breadcrumb").append("<li id='active_breadcrumb' class='active'>"+img_name+"</li>");
        }, 1000);
      });

      $(".click_back").live("click", function() { 
        setTimeout(function() {$("#single").css("display","none");}, 10);
        setTimeout(function() {$("#list").css("display", "inline");}, 50);
        $("#active_breadcrumb").remove();
      });

      $(".click_collapse").live("click", function() { 
        $("#comments").collapse('toggle');
      });

      $(".carousel-control").live("click", function() {
        fetch_comments();
        $("#active_breadcrumb").remove();
        setTimeout(function () {
          var s = $(".item.active > img").attr("url")
          var img_name = s.substring(s.lastIndexOf('/')+1);
          $(".breadcrumb").append("<li id='active_breadcrumb' class='active'>"+img_name+"</li>");
        }, 700);
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

      $("#delete_photo").live("click", function() { 
        var albumid = $(this).attr('albumid');
        var url = $(".item.active > img").attr("url");
        $.post('delete_photo.php', {url: url, albumid: albumid}, function(raw_data) {
          //window.reload(true); 
          //alert(raw_data);
          if (raw_data == "index"){
            window.location="index.php";
          }
        });
      });

      $("#upload").live("click", function() { 
        $.post('upload.php', {url: url}, function(raw_data) {
          window.reload(); 
        });
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
