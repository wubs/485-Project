<!DOCTYPE html>
<html lang="en">
<?php include('lib.php'); ?>
<?php include('include/head.php'); ?>
<?php 
  $new_seq = $_GET['seq'];
  $new_url = 'static/images/'.$new_seq.'.jpg'; 
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
  .round_border {
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
  }
  .btn-file {
    position:relative;
    overflow:hidden;
  }
  .btn-file > input {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    font-size: 23px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
    transform: translate(-300px, 0) scale(4);
    direction: ltr;
  }
  input[type="file"] {
    height: 30px;
    line-height: 30px;
    width: auto;
  }
  .fileupload-preview {
    display: inline-block;
    margin-bottom: 5px;
    overflow: hidden;
    text-align: center;
    vertical-align: middle;
  }
  .fileupload .thumbnail > img {
    display: inline-block;
    max-height: 100%;
    vertical-align: middle;
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
      $albumid = 5; 
      $conn = mysql_connect($db_host, $db_user, $db_passwd) or die("Connect Error: " . mysql_error());
      mysql_select_db($db_name) or die("Could not select:" . $db_name);
      $query = "SELECT title, username FROM Album WHERE albumid=$albumid";
      $result = mysql_query($query) or die("Query failed: " . mysql_error());
      $temp = mysql_fetch_array($result, MYSQL_ASSOC); 
      $album_title = $temp['title']; 
      $album_owner = $temp['username']; 
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
              <a href="#" role="button" class="btn click_back opt" >Back</a>

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
        <table width="100%" height="100%" algin="center" valign="center">
          <?php 
            //$query = 'SELECT * FROM Contain WHERE albumid=' 
            //  . $albumid . ' ORDER BY sequencenum';
            //Change the query to include the column Photo.code;
						$query = 'SELECT albumid, caption, sequencenum FROM Contain WHERE Contain.albumid='
							.$albumid
							.' and Contain.url= "'
              . $url
              .'"';
            $result = mysql_query($query) or die("Query failed: " . mysql_error());
            $counter = 0;
            $num = 2; // how many pics per row
            $photos = array();
            while ($photo = mysql_fetch_array($result, MYSQL_ASSOC) ) {
              array_push($photos, $photo);
							$base64 = '"data:image/'.$photo['format'].';base64,' . $photo['code'].'"'; //Fetch the 64Base code for current img
              if ($counter % $num == 0) {
                echo "<tr>"
                  . "<td height='400px' align='center'>" 
                  . "<img class='img-rounded center click_photo' value=" 
                  . ($counter+1) . " src=" . $base64 . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>";
              } else {
                echo "<td height='400px' align='center'>"
                  . "<img class='img-rounded center click_photo' value="
                  . ($counter+1) . " src=" . $base64 . ">"
                  . "<div>" . $photo['caption'] . "</div>"
                  . "<div>" . $photo['date'] . "</div>"
                  . "</td>"
                  . "</tr>";
              }
              $counter = $counter + 1;
            }
          ?>
        </table>

      </div> <!-- end of div list -->

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
        location.href="search.php";
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

    });

    
      
    </script>
  </body>
</html>
