<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <style> 
  .center {
    height:300px;
    background-color:#b0e0e6;
  }
  </style>
  <body>
    <div class="container">
    <?php include('include/navbar.php'); ?>

    <!-- start edit from here -->
      <h2> Album -> <?php echo $albumid ?></h2>
      <a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>

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
      <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="myModalLabel">Modal header</h3>
        </div>
        <div class="modal-body">
          <!-- Start of Carousel -->
          <div id="myCarousel" class="carousel slide">
            <!-- Carousel items -->
            <div class="carousel-inner">
            <?php
              $flag = 0;
              foreach ($photos as $photo) {
                if ($flag == 0) {
                  echo "<div class='item active'><img src=" . $photo['url'] . "></div>";
                  $flag = 1;
                } else {
                  echo "<div class='item'><img src=" . $photo['url'] . "></div>";
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
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Option</button>
        </div>
      </div>
      <!-- End of Modal -->

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
        $("#myModal").modal("show");
        //$("#" + $(this).attr('value')).addClass("active");
        $("#myCarousel").carousel(parseInt($(this).attr('value')-1)); 
      });
    });
      
    </script>
  </body>
</html>
