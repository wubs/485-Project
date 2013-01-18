<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <div class="container">
    <?php include('include/navbar.php'); ?>
    <!-- start edit from here -->

      <h2> <a href="viewalbumlist.php">Albums</a> -> Edit </h2>
      <table class="table table-hover">
        <thead>
          <tr>
            <td class="span3">Album</td> 
            <td class="span3">Access</td> 
            <td class="span1">Action1</td> 
            <td class="span1">Action2</td>
          </tr>
        </thead>

        <tbody>
          <?php 
            $conn = mysql_connect($db_host, $db_user, $db_passwd)
            or die("Connect Error: " . mysql_error());
            
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            
            $query = "SELECT title,access FROM Album WHERE access='public'";
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
              echo "<tr> <td>" . $line['title'] . "</td>"
                . "<td>" . $line['access'] . "</td>"
                 . "<td><a class='btn btn-primary'>Edit</a></td>"
                 . "<td><a class='btn btn-danger'>Del</a></td></tr>";
            }
            
            mysql_free_result($result);
            mysql_close($conn);
            /*
            foreach ($all_albums as $album) {
              echo $album->$name . $album->$access
            }
            */
          ?>
        </tbody>

      </table>

          <form action="addalbum.php" method="post">
            <input type="text" placeholder="user" name="username">
            <input type="text" placeholder="new album" name="title">
            <input name="access" type="radio" value="public">public&nbsp
            <input name="access" type="radio" value="private">private
            <input name="op" type="hidden" value="add">
            <input class='btn btn-success' type="submit" value="Add">
          </form>
    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
  </body>
</html>
