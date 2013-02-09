<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <?php
      //if ( $username == "visitor" ) {
      //  $_SESSION['msg'] = "You don't have privilege to access this page";
      //  header("Location: index.php");
      //}
    ?>
    <div class="container">
    <!-- start edit from here -->
      <ul class="breadcrumb">
        <li class="active"><a href="#">My Albums</a><span class="divider">/</span></li>
      </ul>
      <span style="display:<?php echo $display_msg?>" class="label label-warning">
        <?php 
          if (isset($_SESSION['msg'])) 
          { 
            echo $_SESSION['msg']; 
            unset($_SESSION['msg']);
          }
        ?>
      </span>

      <h4> <?php echo "$username: $firstname $lastname"; ?> Here are all your albums</h4>
      <a href="editalbumlist.php" class="btn btn-primary"> Edit My Album </a>
      <br>
      <br>
      <table class="table table-hover">
        <thead>
          <tr>
            <td class="span3">Album</td> 
            <td class="span1">Access</td> 
            <td class="span1">Created</td> 
            <td class="span1">Last Updated</td>
          </tr>
        </thead>

        <tbody>
          <?php 
            $conn = mysql_connect($db_host, $db_user, $db_passwd)
            or die("Connect Error: " . mysql_error());
            
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            $url_prefix = "viewmyalbum.php?albumid=";

            $query = "SELECT * FROM AlbumAccess, Album where AlbumAccess.albumid = Album.albumid and AlbumAccess.username = '$username' order by access desc";
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
              
				    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $url = $url_prefix . $line['albumid'];
              echo "<tr> <td>"
								 . "<a href=" . $url . ">" . $line['title'] . "</a></td>"						
                 . "<td>" . $line['access'] . "</td>"
                 . "<td>" . $line['created'] . "</td>"
                 . "<td>" . $line['lastupdated'] . "</td> </tr>";
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
