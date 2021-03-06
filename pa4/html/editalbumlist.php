<!DOCTYPE html>
<html lang="en">
<style>
   .drag {
      width: 100px;
      position: relative;
      top: 0;
      left: 0;
      opacity: 0;
      display: none;
      border:1px solid black;
    }

    .drag_title {
      position: relative;
      top: 0;
      left: 0;
      display: inline;
    }

    .contains {
      position: relative;
      height: 50px;
    }

    .old_row {
      margin-top: 25px;
      height: 30px;
      margin-right: 150px;
    }

    #old_table span {
    }
</style>
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
      
      <!-- second modal -->
      <div id="shareModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="shareModalLabel">Sharing album access</h3>
        </div>
        <div class="modal-body">
          <input style="margin-bottom:0px" id="to_username" type="text" placeholder="" >
          <input type="hidden" id="sharing_albumid">
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
          <button class="btn btn-primary Share">Apply</button>
        </div>
      </div>

      <ul class="breadcrumb">
        <li><a href="myalbumlist.php">My Albums</a><span class="divider">/</span></li>
        <li class="active"><a href="#">Edit My Albums </a><span class="divider">/</span></li>
      </ul>

      <form class="form-inline" action="#" method="post">
        <input id="title" type="text" placeholder="new album title" name="title">
        <div class="btn-group" data-toggle="buttons-radio">
          <button type="button" value="public" class="btn active add_album_group">Public</button>
          <button type="button" value="private" class="btn add_album_group">Private</button>
        </div>
        <input name="op" type="hidden" value="add">
        <a class='btn btn-success Add' > Add</a>
      </form>

      <div class="row">
      <div id="old_table" class="span8">
            <?php 
              $conn = mysql_connect($db_host, $db_user, $db_passwd)
                or die("Connect Error: " . mysql_error());
              
              mysql_select_db($db_name) or die("Could not select:" . $db_name);
              
              $query = "SELECT title, access, albumid FROM Album where username='$username' order by albumid";
              $result = mysql_query($query) or die("Query failed: " . mysql_error());
              
              while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                // always
                $cur_albumid = $line['albumid'];
                echo "<div class='old_row'> <span class='contains' ><div class='drag_title' style='font-weight:bold' >" . $line['title'] . "</div> <div class='drag' style='display: none;' albumid='$cur_albumid'> Give access to </div> </span>"
                  . "<span class='pull-right' style='margin-left: 30px; width:60px' >" . $line['access'] . "</span>";

                echo "<span class='pull-right'><a href='#myModal' role='button' class='btn btn-small click_edit' data-toggle='modal' albumid=" 
                  . $line['albumid'] . " album_title='" . $line['title'] . "' album_access='" . $line['access'] . "'>Edit</a>&nbsp&nbsp"
                  . "<a class='btn btn-small Del' albumid='". $line['albumid'] . "'>Del</a></span></div>";

                //
                $cur_albumid = $line['albumid'];
                $query2 = "SELECT username FROM AlbumAccess where albumid=$cur_albumid and username!='$username'";
                $result2 = mysql_query($query2) or die("Query failed: " . mysql_error());

                echo "<div id='$cur_albumid'>";
                $print_shared_with = true;
                if($line['access']=='public'){
                  $print_shared_with = false;
                }
                while ($user_row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                  $cur_username = $user_row['username'];

                  if ( $print_shared_with ) {
                    echo "<div><span><div style='position:relative;left:30px;'>Shared with:</div></span><span></span><span></span><span></span><span></span></div>";
                    $print_shared_with = false;
                  }

                  echo "<div><span><div style='position:relative;left:50px;' class='drag_title'>$cur_username</div><div class='drag' style='display: none;' username='$cur_username' albumid='$cur_albumid' > Wanna move to trash? </div></span><span></span><span></span><span></span><span></span></div>";
                }
                echo "</div>";
                
              }
              
              mysql_free_result($result);
              mysql_close($conn);
            ?>

      </div>



      <div class="span4" >
        <div><h3 class="dest" trash="true" >Trash</h3></div>
        <div><h5>Other Users</h5></div>
        <div id="other_users">
        <?php
          $conn = mysql_connect($db_host, $db_user, $db_passwd)
                or die("Connect Error: " . mysql_error());
              
          mysql_select_db($db_name) or die("Could not select:" . $db_name);
              
          $query = "SELECT username FROM User WHERE username != '$username'";
          $result = mysql_query($query) or die("Query failed: " . mysql_error()); 
            
          while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $cur_username = $line['username'];
            echo "<div class='dest' username='$cur_username'>$cur_username</div>";
          }       
        ?>
        </div>
        
      </div>

      </div> <!-- end of row -->



    <!-- edit above -->
    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
    
    <!--  Our pure javascript code for PA3 starts here, jQuery is not used for PA3 -->
    <script src="static/js/pa3_drag.js" type="text/javascript"></script>

    <script type="text/javascript">
      $(function () { // jQuery main()
        $(".Del").live("click", function() { 
          var confirmbox = confirm("Do you really want to delete this album?");    
          var albumid = $(this).attr('albumid');
          if(confirmbox==true){
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
          
          if(title){
            $.post("addalbum.php", 
              {"op": 'add', "username": username, "title": title, "access": access},
              function(data) {
                location.reload();
              }
            );
          }  
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

        $(".Share").live("click", function() {     
          var to_username = $("#to_username").val();
          var id = $("#sharing_albumid").val();
          var access = $("#album_access").val();
          $.post("share.php", 
            {"albumid": id, "to_username": to_username},
            function(data) {
              location.reload();
            }
          );  
        });

        $(".del_share").live("click", function() {     
          var username = $(this).attr("usrname");   
          var id = $(this).attr("albumid");
          
          $.post("delshare.php", 
            {"albumid": id, "username": username},
            function(data) {
              location.reload();
            }
          );   
        });

     }); // jQuery main() end 
    </script>

</body>
</html>
