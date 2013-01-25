<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
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

        <tbody id="table_body" >
          <?php 
            $conn = mysql_connect($db_host, $db_user, $db_passwd)
            or die("Connect Error: " . mysql_error());
            
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            
            $query = "SELECT title,access,albumid FROM Album WHERE access='public'";
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
              echo "<tr> <td>" . $line['title'] . "</td>"
                . "<td>" . $line['access'] . "</td>"
                . "<td><a class='btn btn-primary'>Edit</a></td>"
                . "<td><a class='btn btn-danger Del' albumid='". $line['albumid'] . "'>Del</a></td>";
                /*
                . "<form action='deletealbum.php' method='post'>"
                . "<input name='op' type='hidden' value='delete'>"
                . "<input name='albumid' type='hidden' value=".$line['albumid'].">"
                . "<input class='btn btn-danger' type='submit' value='Del'></form>";
                */
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
    
    <script type="text/javascript">
      $(function () { // jQuery main()
        $(".Del").live("click", function() {     
          var albumid = $(this).attr('albumid');
          $.post("deletealbum.php", {"op": 'delete', "albumid": albumid }, function(data) {
            // refresh 
            location.reload();
            // comment: data: [data_item: {title, access albumid}, ...  ]
             // $("#table_body").empty();

             // for (i=0; i<data.length; i++) {
             //   var ttitle = data[i].title;
             //   var taccess = data[i].access;
             //   var tid = data[i].albumid;
             //   var tr = "<tr> <td>" + ttitle +  "</td><td>" + taccess + "</td><td><a class='btn btn-primary'>Edit</a></td><td><a class='btn btn-danger Del' albumid='" + tid + "'>Del</a></td>";
             //   $("#table_body").append(tr);
             // }
              
              
            });   
        });
     }); // jQuery main() end 
    </script>

</body>
</html>
