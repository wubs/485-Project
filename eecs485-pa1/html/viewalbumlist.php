<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <div class="container">
    <?php include('include/navbar.php'); ?>
    <!-- start edit from here -->

      <h2> Albums -> <a href="editalbumlist.php">Edit</a></h2>
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
            $conn = mysql_connect($db_host, $db_user, $db_passwd)
            or die("Connect Error: " . mysql_error());
            
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            $url_prefix = "viewalbum.php?albumid=";

            if (!isset($_GET['username']) ) {
          
              
              $query = "SELECT * FROM Album order by access desc";
              $result = mysql_query($query) or die("Query failed: " . mysql_error());
              
            } else {
              $cur_username = $_GET['username'];
              echo "<h1>$cur_username</h1>";
              $query = "SELECT * FROM Album WHERE username='$cur_username'";
              $result = mysql_query($query) or die("Query failed: " . mysql_error());

							$queryprivate = "SELECT * FROM Album WHERE access='private'";
							$userlist = mysql_query($queryprivate) or die("Query failed: " . mysql_error());
							
						
            }
				

            
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $url = $url_prefix . $line['albumid'];
              echo "<tr> <td>";
								if($line['access']=='private' && $cur_username!= $line['username']){
									echo $line['title'] . "</td>";
									
								}else{
									echo "<a href=" . $url . ">" . $line['title'] . "</a></td>";						
								}
                 echo "<td>" . $line['username'] . "</td>"
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
