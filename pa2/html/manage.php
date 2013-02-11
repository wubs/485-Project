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
            <button id="cur_public" type="button" value="public" class="btn active edit_album_group">Public</button>
            <button id="cur_private" type="button" value="private" class="btn edit_album_group">Private</button>
          </div>
          <input type="hidden" id="cur_id">
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
          <button class="btn btn-primary Edit">Save changes</button>
        </div>
      </div>
      
      <ul class="breadcrumb">
        <li><a href="manage.php">Manage Site -- Edit Albums</a><span class="divider">/</span></li>
      </ul>
      
      <h2> This is admin page, don't do evil. </h2>

      <!--form class="form-inline" action="#" method="post">
        <input id="title" type="text" placeholder="new album title" name="title">
        <div class="btn-group" data-toggle="buttons-radio">
          <button type="button" value="public" class="btn active add_album_group">Public</button>
          <button type="button" value="private" class="btn add_album_group">Private</button>
        </div>
        <input name="op" type="hidden" value="add">
        <a class='btn btn-success Add' > Add</a>
      </form-->
      <br>
      <h3>(A) Edit any album</h3>

      <table class="table table-large table-hover">
        <thead>
          <tr>
            <td class="span3">Album</td> 
            <td class="span3">Access</td> 
            <td class="span1">Actions</td> 
            <td class="span1"></td>
            <td class="span1"></td>
          </tr>
        </thead>

        <tbody id="table_body" >
          <?php 
            $conn = mysql_connect($db_host, $db_user, $db_passwd)
            or die("Connect Error: " . mysql_error());
            
            mysql_select_db($db_name) or die("Could not select:" . $db_name);
            
            $query = "SELECT title, access, albumid FROM Album order by albumid";
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                // always
              	echo "<tr> <td>" . $line['title'] . "</td>"
                	. "<td>" . $line['access'] . "</td>";

               	echo "<td><a href='#myModal' role='button' class='btn btn-primary click_edit' data-toggle='modal' albumid=" . $line['albumid'] . " album_title='" . $line['title'] . "' album_access='" . $line['access'] . "'>Edit</a></td>"
                	. "<td><a class='btn btn-danger Del' albumid='". $line['albumid'] . "'>Del</a></td></tr>";
            }
            
            mysql_free_result($result);
          ?>
        </tbody>
      </table>

    <h3>(B) Grant/Remove admin privilege </h3>

    <table class="table table-large span5">
      <tbody>
      <?php
        $query = "SELECT User.username as username, Admin.username as admin_name FROM User LEFT JOIN Admin on (User.username=Admin.username);";
        $result = mysql_query($query) or die("Query failed: " . mysql_error());

        while ($user = mysql_fetch_array($result, MYSQL_ASSOC)) {
          if ($user['admin_name'] == $_SESSION['username']) {
            continue;
          }
          echo "<tr><td>" . $user['username'] . "</td>";
          if ($user['admin_name'] == Null) {
            echo "<td><a class='btn grant' value='" . $user['username'] . "' >Grant</a></td></tr>";
          } else {
            echo "<td><a class='btn remove' value='".$user['username'] . "'>Remove</a></td></tr>";
          }
        }
        mysql_close($conn);
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
					var confirmbox=confirm("Do you really want to delete this album?");    
          var albumid = $(this).attr('albumid');
					if(confirmbox="true"){
          	$.post("deletealbum.php", {"op": 'delete', "albumid": albumid }, function(data) {
            	// refresh 
            	location.reload();
            	});  
					}
					else{ 
							location.reload();
					}
        });

        $(".Add").live("click", function() {     
          var username = $("#username").val();
          var title = $("#title").val();
          var access = $(".btn.active.add_album_group").val();

          $.post("addalbum.php", 
            {"op": 'add', "username": username, "title": title, "access": access},
            function(data) {
              location.reload();
            }
          );   
        });

        $(".click_edit").live("click", function() {     
          var access = $(this).attr("album_access");
          var title = $(this).attr("album_title");
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

          $.get("editalbum.php", 
            {"albumid": id, "title": title, "access": access},
            function(data) {
              location.reload();
            }
          );   
        });

        $(".click_share").live("click", function() {     
          var cur_id = $(this).attr("albumid");
          $("#sharing_albumid").val(cur_id);
        });

        $(".grant").live("click", function() {     
          $.post("grant_remove_admin.php", 
            {"op": "grant", "username": $(this).attr('value')},
            function(data) {
              alert(data);
              location.reload();
            }
          );   
        });

        $(".remove").live("click", function() {     
          $.post("grant_remove_admin.php", 
            {"op": "remove", "username": $(this).attr('value')},
            function(data) {
              alert(data);
              location.reload();
            }
          );   
        });

     }); // jQuery main() end 
    </script>

</body>
</html>
