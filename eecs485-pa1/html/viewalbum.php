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
    margin-top: 30px;
    margin-bottom: 20px;
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
  </style>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">

    <div id="list">
      <!-- start edit from here -->
        <h2> Album -> <?php echo $albumid ?></h2>


        <!-- file uploader -->
        <div class="fileupload fileupload-new" data-provides="fileupload">
          <div class="fileupload-preview thumbnail" style="width: 300px; height: 250px;"></div>
          <div>
            <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists"></span><input type="file" /></span>
            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
          </div>
        </div>


        <table width="100%" height="100%" algin="center" valign="center">
          <?php 
            $albumid = $_GET['albumid'];

            $conn = mysql_connect($db_host, $db_user, $db_passwd)
            or die("Connect Error: " . mysql_error());
            
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            
            $query = 'SELECT * FROM Contain WHERE albumid=' 
              . $albumid . ' ORDER BY sequencenum';
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            $counter = 0;
            $num = 2; // how many pics per row
            $photos = array();
            while ($photo = mysql_fetch_array($result, MYSQL_ASSOC) ) {
              array_push($photos, $photo);
              if ($counter % $num == 0) {
                echo "<tr>"
                  . "<td height='400px' align='center'>" 
                  . "<img class='img-rounded center click_photo' value=" 
                  . $photo['sequencenum'] . " src=" . $photo['url'] . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "</td>";
              } else {
                echo "<td height='400px' align='center'>"
                  . "<img class='img-rounded center click_photo' value="
                  . $photo['sequencenum'] . " src=" . $photo['url'] . ">"
                  . "<p>" . $photo['caption'] . "</p>"
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
              if ($flag == 0) {
                echo "<div class='item active round_border'>"
                   . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
                   . "src=" . $photo['url'] . "><div class='carousel-caption'><p>" 
                   . $photo['caption'] . "</p></div></div>";
                $flag = 1;
              } else {
                echo "<div class='item round_border'>"
                   . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
                   . "src=" . $photo['url'] . "><div class='carousel-caption'><p>" 
                   . $photo['caption'] . "</p></div></div>";
              }
              //if ($flag == 0) {
              //  echo "<div class='item active round_border'>"
              //     . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
              //     . "src=" . $photo['url'] . "></div>";
              //  $flag = 1;
              //} else {
              //  echo "<div class='item round_border'>"
              //     . "<img class='img-rounded' style='height:100%;margin-left:auto;margin-right:auto;'" 
              //     . "src=" . $photo['url'] . "></div>";
              //}
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

        <!-- Start of Comments
        <div class="span3 myWell" style"height:100%">
          
          <?php 
          ?>
        </div>
        End of Comments-->
        <div  class="myWell" >
          <div class="row-fluid">
            <a href="#" role="button" class="btn btn-primary click_back span3" >Back</a>
            <a href="#" role="button" class="btn btn-success click_back span3" >Email</a>
            <a href="#" role="button" class="btn btn-success click_back span3" >Edit</a>
            <a role="button" class="btn btn-info click_collapse span3" >Comments</a>

          </div>
          <div id="comments" class="collapse">
            <div class="commentWell">
              <p> comment </p>
            </div>
            <div class="commentWell">
              <p> comment </p>
            </div>
          </div>
        </div>

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
        $("#myCarousel").carousel(parseInt($(this).attr('value')-1)); 
        setTimeout(function() {$("#list").css("display", "none");}, 350);
        setTimeout(function() {$("#single").css("display","inline");}, 400);
      });

      $(".click_back").live("click", function() { 
        setTimeout(function() {$("#single").css("display","none");}, 10);
        setTimeout(function() {$("#list").css("display", "inline");}, 50);
      });

      $(".click_collapse").live("click", function() { 
        $("#comments").collapse('toggle');
      });
    });
      
    </script>
  </body>
</html>
