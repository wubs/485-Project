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
    <?php
      //$conn = mysql_connect($db_host, $db_user, $db_passwd)
      //           or die("Connect Error: " . mysql_error());

      //mysql_select_db($db_name) or die("Could not select:" . $db_name);

      //$query = 'SELECT * FROM Contain WHERE albumid='
      //               . $albumid . ' ORDER BY sequencenum';
      //$result = mysql_query($query) or die("Query failed: " . mysql_error());

      $url = $_GET['url'];
      <div class="row">
        <div class="img_container span8">
        <div class="row">
        <a href="#" role="button" class="btn btn-primary" id="left"><i class="icon-arrow-left"></i></a>
        <a href="#" role="button" class="btn btn-primary" id="right"><i class="icon-arrow-right"></i></a>
        </div>
        <img id="cur_image" src=<?php echo $url?>>
        </div>
        <div class="img_container span2">
        <h2> Comments </h2>
        <h2> Comments </h2>
        </div>
      </div>
    ?>
    <!-- edit above -->
    </div> <!-- /container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
      $(function () { ///

        $("#left").live("click", function() { 
          $.post("viewpicture_post.php", {"url": url, "albumid": albumid, "seq":seq},
            function(data) {
              $("#cur_image").attr("src", data);
            }); 
        });

        $("#right").live("click", function() { 
          $.post("viewpicture_post.php", {"url": url, "albumid": albumid, "seq":seq},
            function(data) {
              $("#cur_image").attr("src", data);
            }); 
        });

      }); 
    </script>
  </body>
</html>
