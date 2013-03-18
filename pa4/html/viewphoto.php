<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
<?php 
  $new_data = $_GET['data'];
  $new_seq = substr($new_data,1);
  $albumid = substr($new_data,0,1);
?>
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

  .myWell {
    padding: 20px;
    height: auto;
    width: 60%;
    margin: 0px auto;
    margin-top: 10px;
    margin-bottom: 40px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
  }
  .commentWell {
    padding: 20px;
    height: auto;
    margin-top: 10px;
    margin-left: 0px;
    background-color: #f5f5f5;
    border: 4px solid #e3e3e3;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
  }
  .newcommentWell {
    padding: 20px;
    height: auto;
    margin-top: 10px;
    margin-left: 0px;
    background-color: #f5f5f5;
    border: 4px solid #e3e3e3;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
  }

  .cap {
    position: relative;
    bottom: 0px;
    height: 10%;
    color: white;
  }
  .center {
    height:200px;
    background-color:#b0e0e6;
  }

  #single {
    height: 700px; 
  }
  #single_block {
    position: relative;
    overflow-x: hidden;
    overflow-y: hidden;
    height: 520px; 
  }

  #new_viewer {
    display: inline-block;
    height: 520px; 
  }

  .round_border {  /* this is for the td */
    display: inline-block;
    height: 520px;
    /*line-height: 520px;*/
    text-align: center;
    vertical-align: middle;
    margin-right:auto;
    z-index: 200;
  }
  .new_image {    /* inside round_border */
    display: inline-block;
    max-height: 80%;
    max-width: 90%; 
    margin-left:auto;
    margin-right:auto;
    padding: 5%;
  }
  #blocker-left, #blocker-right {
    z-index: 100;
    position: relative;
    height: 520px;
  }
  #blocker-right {
  }
  #blocker-left {
  }
  .opt {
    width: 33%;  
    padding: 0px;
    padding-top: 5px;
    padding-bottom: 5px;
  }
  </style>
  <body>
    <?php include('include/navbar.php'); ?>
    <div class="container">
    <?php 
      //$albumid = 5; 
      $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
      mysql_select_db($db_name) or die("Could not select:" . $db_name);
      $query = "SELECT title, username FROM Album WHERE albumid=$albumid";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      $temp = mysql_fetch_array($result, MYSQL_ASSOC); 
      $album_title = $temp['title']; 
      $album_owner = $temp['username']; 

      $query = "SELECT url FROM Contain WHERE albumid = $albumid AND sequencenum = $new_seq";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      $temp = mysql_fetch_array($result, MYSQL_ASSOC); 
      $new_url = $temp['url'];
    ?>
    <ul class="breadcrumb">
      <li><a href="viewalbumlist.php">Album List</a><span class="divider">/</span></li>
      <li class="active">
        <a class="click_back" ref="#">Album: <?php echo "'$album_title', Owner: '$album_owner'"; ?></a><span class="divider">/</span>
      </li>
    </ul>

      <div id="single" style="z-index:10;">
        <table width="100%" algin="center">
          <tr>
          <td align='center'>
            <img class="img-rounded center" style="height:100%;margin-left:auto;margin-right:auto;" src= "<?php echo $new_url ?>">
            <?php
               $query = 'SELECT caption FROM Contain WHERE url= "'. $new_url.'"';
               //echo "<p>".$query."</p>";
               $result = mysql_query($query) or die("Query failed: " . mysql_error());
               $photo = mysql_fetch_array($result, MYSQL_ASSOC); 
               echo "<div>" . $photo['caption'] . "</div>";
            ?>
        </table>
      
        <div  class="myWell" > <!-- Buttons and comments -->
          <div class="row-fluid btn-group">
              <a role="button" class="btn click_back opt">Back</a>

              <a id="click_edit" value=false class="btn opt" rel="popover" data-html=true 
                data-trigger="click" data-placement="top"
                data-content="<input id='new_caption' type='caption' style='width:194px' placeholder= '<?php echo $photo['caption'] ?>'>
                  <a url='<?php echo $new_url ?>' id='click_caption' class='btn'>Edit</a>" >
                Edit Caption
              </a>

              <a id="click_similar" value=false class="btn click_similar opt" albumid="5" 
                keyword="<?php echo $photo['caption'];?>">
                Similar Photo</a>
          </div>
      </div> <!-- end of div single -->


      <div id="list">
      <!-- start edit from here -->    
      </div> <!-- end of div list -->
    <?php mysql_close($conn); ?>  
    <!-- edit above -->
    </div> <!-- /container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    function ajax_post(url, data, callback) {
        var httpRequest = new XMLHttpRequest();
        var url = url;
        var data = JSON.stringify(data);
        var callback = callback;

        var handler = function() {
          if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
              // action
              returned_obj = JSON.parse(httpRequest.responseText);
              callback(returned_obj);
              // action
            } else {
              alert('There was a problem with the request.');
            }
          }
        };

        httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = handler;
        httpRequest.open('POST', url);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send('data=' + encodeURIComponent(data));
    }

    $(function () {

      $(".click_back").live("click", function() { 
        history.back();
      });

      $(".click_collapse").live("click", function() { 
        $("#comments").collapse('toggle');
      });


      $("#click_edit").live("click", function() { 
        if ( $(this).val() == 0 ) {
          $(this).popover("show");
          $(this).val(1);
        } else {
          $(this).popover("hide");
          $(this).val(0);
        }
      });


      $(".click_similar").live("click", function(){
          var id = $(this).attr('albumid');
          var keywrd = $(this).attr('keyword');
          alert("search"+id+keywrd);
          $.post('search_action.php', {'albumid':id, 'keyword':keywrd}, function(data){
            var list = document.getElementById("list");
            list.innerHTML = data;
          });
        });

      $("#click_caption").live("click", function() { 
        var url = $(this).attr("url");
        var text = $("#new_caption").val();
        alert(url+text);
        $.post("edit_caption.php", {'url': url, 'caption': text}, function(data) {
          location.reload();
        });
      });

      $(".click_photo").live("click", function() { 
          var seq = $(this).attr("seq");
          var albumid = $(this).attr("albumid");
          //$.post('viewphoto.php',{'url':url}, function(data){
          //  alert(url);
          //});
          location.href = "viewphoto.php?data="+albumid+seq;
        });

    });

    </script>
  </body>
</html>
