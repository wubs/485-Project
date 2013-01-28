<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <!-- start edit from here -->
      <!-- Modal -->
      <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="myModalLabel">Edit Album</h3>
        </div>
        <div class="modal-body">
          <input style="margin-bottom:0px" id="cur_title" type="text" placeholder="" >
          <div class="btn-group" data-toggle="buttons-radio">
            <button id="cur_public" type="button" value="public" class="btn active">Public</button>
            <button id="cur_private" type="button" value="private" class="btn ">Private</button>
          </div>
          <input type="hidden" id="cur_id">
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
          <button class="btn btn-primary Edit">Save changes</button>
        </div>
      </div>

      <ul class="breadcrumb">
        <li><a href="viewalbumlist.php">Album List</a><span class="divider">/</span></li>
        <li class="active"><a href="#">Edit Album List</a><span class="divider">/</span></li>
      </ul>

      <form class="form-inline" action="#" method="post">
        <input id="username" type="text" placeholder="username" name="username">
        <input id="title" type="text" placeholder="new album title" name="title">
        <div class="btn-group" data-toggle="buttons-radio">
          <button type="button" value="public" class="btn active">Public</button>
          <button type="button" value="private" class="btn ">Private</button>
        </div>
        <input name="op" type="hidden" value="add">
        <a class='btn btn-success Add' > Add</a>
      </form>

      <table class="table table-large table-hover">
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
                . "<td><a href='#myModal' role='button' class='btn btn-primary click_edit' data-toggle='modal' albumid=" . $line['albumid'] . " >Edit</a></td>"
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

        $(".Add").live("click", function() {     
          var username = $("#username").val();
          var title = $("#title").val();
          var access = $(".btn.active").val();

          $.post("addalbum.php", 
            {"op": 'add', "username": username, "title": title, "access": access},
            function(data) {
              location.reload();
            }
          );   
        });

        $(".click_edit").live("click", function() {     
          var access = $(this).parent().prev().text();
          var title = $(this).parent().prev().prev().text();
          var cur_id = $(this).attr("albumid");
          $("#cur_title").val(title);
          $("#cur_id").val(cur_id);
          
          if (access=="private") {
            $('#cur_private').addClass("active");
            $('#cur_public').removeClass("active");
          }
          else {
            $('#cur_public').addClass("active");
            $('#cur_private').removeClass("active");
          }
        });

        $(".Edit").live("click", function() {     
          var title = $("#cur_title").val();
          var id = $("#cur_id").val();
          if ($("#cur_private").hasClass("active")) {
            var access = "private"; 
          } else {
            var access = "public"; 
          }

          $.post("editalbum.php", 
            {"albumid": id, "title": title, "access": access},
            function(data) {
              location.reload();
            }
          );   
        });

     }); // jQuery main() end 
    </script>

</body>
</html>
