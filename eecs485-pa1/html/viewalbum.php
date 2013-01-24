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
                . "<a href='viewpicture.php?url=" . $photo['url'] . "'>"
                . "<img class='img-rounded center click_photo' value=" 
                . $photo['sequencenum'] . " src=" . $photo['url'] . "></a>"
                . "<p>" . $photo['caption'] . "</p>"
                . "</td>";
            } else {
              echo "<td height='400px' align='center'>"
                . "<a href='viewpicture.php?url=" . $photo['url'] . "'>"
                . "<img class='img-rounded center click_photo' value="
                . $photo['sequencenum'] . " src=" . $photo['url'] . "></a>"
                . "<p>" . $photo['caption'] . "</p>"
                . "</td>"
                . "</tr>";
            }
            $counter = $counter + 1;
          }
        ?>
      </table>

    <!-- edit above -->
    </div> <!-- /container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function () {
      $(".click_photo").live("click", function() { 
      });
    });
      
    </script>
  </body>
</html>
