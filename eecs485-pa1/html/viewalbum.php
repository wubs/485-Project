<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <div class="container">
    <?php include('include/navbar.php'); ?>

    <!-- start edit from here -->

      <h2> Albums -> <?php echo $albumid ?></h2>
      <table class="table table-hover">
        <thead>
          <tr>
            <td class="span3">Album</td> 
            <td class="span2">Created by</td> 
            <td class="span1">Access</td> 
            <td class="span1">Created</td> 
            <td class="span1">Last Updated</td>
          </tr>
        </thead>
        <tbody>
    <?php 
        $albumid = $_GET['albumid'];

        $conn = mysql_connect($db_host, $db_user, $db_passwd)
        or die("Connect Error: " . mysql_error());
        
        mysql_select_db($db_name) or die("Could not select:" . $db_name);
        
        $query = 'SELECT * FROM Contain WHERE albumid=' 
          . $albumid . ' ORDER BY sequencenum';
        $result = mysql_query($query) or die("Query failed: " . mysql_error());
        
        while ($photo = mysql_fetch_array($result, MYSQL_ASSOC) ) {
          echo "<div class='row span12'>"
             . "<div class='span6'>" 
             . "<img src=" . $photo['url'] . ">"
             . "</div>"
             . "<div class='span6'>"
             . "</div>"
             . "</div>";
        }
        
        mysql_free_result($result);
        mysql_close($conn);
        ?>
        </tbody>
      </table>

    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
