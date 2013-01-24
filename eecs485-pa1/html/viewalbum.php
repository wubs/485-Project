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
  </style>
  <body>
    <div class="container">
    <?php include('include/navbar.php'); ?>

    <div id="list" class="span12">
      <!-- start edit from here -->
        <h2> Album -> <?php echo $albumid ?></h2>

        <a href="#" role="button" class="btn btn-primary" >Add a photo</a>
        <a href="#" role="button" class="btn " >Edit</a>

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
              //if ($counter % $num == 0) {
              //  echo "<div class='row-fluid' style='height:300px'>"
              //    . "<div class='span5'>" 
              //    . "<img style='height:200px' class='img-rounded' src=" . $photo['url'] . ">"
              //    . "</div>";
              //} else {
              //  echo "<div class='span5'>"
              //    . "<img class='img-rounded' src=" . $photo['url'] . ">"
              //    . "</div>"
              //    . "</div>";
              //}
            }
          ?>
        </table>

        <!-- Modal -->
        <div id="myModal" class="modal hide fade photoModal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-body" style="height:100%; width=100%; max-height: 800px; padding:0px">
              <div style="width=30%;position: absolute;right:0px;top: 0px; padding:10px">
                <h2 style="width=100%"> Comments </h2>
                <h2> Comments </h2>
                <h2> Comments </h2>
                <h2> Comments </h2>
                <h2> Comments </h2>
                <h2> Comments </h2>
                <h2> Comments </h2>
                <h2> Comments </h2>
              </div>
          </div>
        </div>
        <!-- End of Modal -->
      </div> <!-- end of div list -->

      <div id="single" class="span12" style="visibility:hidden;position:absolute;z-index: 10; top:0; padding-top:60px;">
        <h1> single Picture </h1> 
            <!-- Start of Carousel -->
            <div id="myCarousel" class="carousel slide" style="height:100%;width:70%;margin-bottom:0px">
              <!-- Carousel items -->
              <div class="carousel-inner" style="height: 100%; width:100%;">
              <?php
                $flag = 0;
                foreach ($photos as $photo) {
                  if ($flag == 0) {
                    echo "<div class='item active'>"
                       . "<img style='height:100%;margin-left:auto;margin-right:auto;'" 
                       . "src=" . $photo['url'] . "></div>";
                    $flag = 1;
                  } else {
                    echo "<div class='item'>"
                       . "<img style='height:100%;margin-left:auto;margin-right:auto;'" 
                       . "src=" . $photo['url'] . "></div>";
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
      </div> <!-- end of div single -->

    <!-- edit above -->
    </div> <!-- /container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function () {
      $("#myCarousel").carousel({
        interval: false 
      });

      $(".click_photo").live("click", function() { 
        //$("#myModal").modal("show");
        ////$("#" + $(this).attr('value')).addClass("active");
        //$("#myCarousel").carousel(parseInt($(this).attr('value')-1)); 
        $("#list").css("visibility", "hidden");
        $("#single").css("visibility", "visible");

      });
    });
      
    </script>
  </body>
</html>
