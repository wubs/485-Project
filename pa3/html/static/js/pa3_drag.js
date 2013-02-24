
// main 
document.onmousedown = mouse_down;
document.onmouseup = mouse_up;

document.onmouseover = function(e) {
  if (e.target.className == 'dest') {
    dest = e.target;
    //console.log('over');
    dest.style.backgroundColor = "yellow";
  }
};

dest = null;
drag = null;

register_dests();

// end of main

// ajax 
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
// end of ajax 

document.onmouseout = function(e) {
  if (e.target.className == 'dest') {
    dest.style.backgroundColor = null;
    dest = null;
    //console.log('out');
  }
};

// helper function
function position_to_int(pos) {
  // use this to strip px out
  var i = parseInt(pos.replace('px', ''));
  if (i) {
    return i;
  } else {
    return 0;
  }
}

//
// drag is the element that is being dragged.
//

function register_dests() {
  var dests = document.getElementsByClassName('dest');

  for (var i = 0; i < dests.length; i++) {
    var el = dests[i];
    //el.onmouseover = function(e) {dest = e.target; console.log('over');};
    //el.onmouseout = function(e) {dest = null; console.log('out');};
    el.style.zIndex = 500;
  }
}

function mouse_down(e) {
  e = e || window.event;

  down_x = e.clientX;
  down_y = e.clientY;

  drag_title = e.target;

  if (drag_title.className != "drag_title") {
    // we are testing against drag_title because drag is hidden
    drag_title = null;
    return true;
  }

  offset_width = drag_title.offsetWidth;

  if (drag_title.nextSibling.className == "drag") {
    drag = drag_title.nextSibling;
    console.log(drag_title.nextSibling);
  } else if (drag_title.nextSibling.nextSibling.className == "drag") {
    drag = drag_title.nextSibling.nextSibling;
    console.log(drag_title.nextSibling.nextSibling);
  } else {
    alert('something wrong');
    return;
  }

  offset_x = position_to_int(drag_title.style.left) - offset_width;
  offset_y = position_to_int(drag_title.style.top);

  drag.style.opacity = "0.8";
  drag.style.left = offset_x;
  drag.style.top = offset_y;

  drag_title.style.opacity = "0.2";

  old_zIndex = drag.style.zIndex;

  drag.style.pointerEvents = "none";
  // since drag element is always below the cursor
  // we have to disable pointerEvents for drag
  // otherwise the event will not happen on the destination
  drag.style.zIndex = 1;

  
  setTimeout(function() {if (drag) drag.style.display = "inline";}, 100);

  document.onmousemove = mouse_move;
  document.body.focus();
  return false;
}

function mouse_move(e) {
  e = e || window.event;
  drag.style.left = (offset_x + e.clientX - down_x) + 'px';
  drag.style.top = (offset_y + e.clientY - down_y) + 'px';
}

function refresh_ui(data, albumid) {
  // data is already json  
  var shared_users = data.shared_users;
  var other_users = data.other_users;

  var other_txt = "";
  var other_users_block = document.getElementById('other_users') 
    // other_users

    for (var i=0; i< other_users.length; i++) {
      other_txt += "<div class='dest' username='" + other_users[i] + "'>" + other_users[i] + "</div>";

    }
  other_users_block.innerHTML = other_txt;

  var shared_users_block = document.getElementById(albumid); 

  // if there are guys here
  if (shared_users.length !=0 ) {
    var shared_txt = "<div><span><div style='position:relative;left:30px;'>Shared with:</div></span><span></span><span></span><span></span><span></span></div>";

    // shared users
    for (var i=0 ;i < shared_users.length ;i++) {
      shared_txt += "<div><span><div style='position:relative;left:50px;' class='drag_title'>" + shared_users[i] + "</div><div class='drag' style='display: none;' username='" + shared_users[i] + "' albumid='" + albumid + "' > Wanna move to trash? </div></span><span></span><span></span><span></span><span></span></div>";
    }

    shared_users_block.innerHTML = shared_txt;
  } else {
  // else no guy
    shared_users_block.innerHTML = ""; 
  }
}


function mouse_up(e) {
  e = e || window.event;
  if (drag != null) {
    if (dest && dest.hasAttribute('trash') && drag.getAttribute('username') !=null && drag.getAttribute('albumid') !=null ) {  // test if a dest is under mouse
      var username = drag.getAttribute('username');
      var albumid = drag.getAttribute('albumid');
      console.log("remove " + username + " from " + albumid);
      
      // withdraw access
      ajax_post('delshare.php', {'username': username, 'albumid': albumid} , function(data) {
        refresh_ui(data, albumid);
      });

      // done, restore drag attributes
      drag.style.zIndex = old_zIndex;
      drag.style.pointerEvents = null;
      drag.style.left = offset_x + 'px';
      drag.style.top = offset_y + 'px';
      document.onmousemove = null;
      drag.style.display = "none";
      drag.style.opacity = "0.0";
      drag_title.style.opacity = "1";
      drag = null;
      // stop the interval loop
    } else if (dest && !drag.hasAttribute('username') && dest.getAttribute('username') != null && drag.getAttribute('albumid') ) {
      console.log(drag.getAttribute('albumid'));
      var username = dest.getAttribute('username');
      var albumid = drag.getAttribute('albumid');
      console.log("grant " + username + " to " + albumid);
        
      // grant access,   share

      ajax_post('share.php', {'to_username': username, 'albumid': albumid} , function(data) {
        refresh_ui(data, albumid);
      });


      // done, restore drag attributes
      drag.style.zIndex = old_zIndex;
      drag.style.pointerEvents = null;
      drag.style.left = offset_x + 'px';
      drag.style.top = offset_y + 'px';
      document.onmousemove = null;
      drag.style.display = "none";
      drag.style.opacity = "0.0";
      drag_title.style.opacity = "1";
      drag = null;
      // stop the interval loop

    } else { 

      // created a closure
      if (Math.abs(e.clientX - down_x) < 5 ) {
        // quick and DIRTY!
        drag.style.zIndex = old_zIndex;
        drag.style.pointerEvents = null;
        drag.style.left = offset_x + 'px';
        drag.style.top = offset_y + 'px';
        document.onmousemove = null;
        drag.style.display = "none";
        drag.style.opacity = "0.0";
        drag_title.style.opacity = "1";
        drag = null;
      } else {
        step = restore(e.clientX + offset_x - down_x, e.clientY + offset_y - down_y);

        // this will move the block back to origin pos
        // setInterval will stop by step() if it is done
        int_step = setInterval(step, 0.5);
      }
    }
  }
}

// restore is more like a class
function restore(x, y) {

  this.period = 30.0; // how many steps
  this.cur_x = x;
  this.cur_y = y;
  this.unit_x = (x - offset_x) / this.period; 
  this.unit_y = (y - offset_y) / this.period; 
  this.count = 0

  var move_back = function () {
    if (count < this.period) {
      this.cur_x -= this.unit_x; 
      this.cur_y -= this.unit_y; 
      drag.style.left = this.cur_x + 'px';
      drag.style.top = this.cur_y + 'px';
      this.count++;
    } else {
      // done, restore drag attributes
      if (drag == null) {
        alert('haha');
        clearInterval(int_step);
        document.onmousemove = null;
        return;
      }
      drag.style.zIndex = old_zIndex;
      drag.style.pointerEvents = null;
      drag.style.left = offset_x + 'px';
      drag.style.top = offset_y + 'px';
      document.onmousemove = null;
      drag.style.display = "none";
      drag.style.opacity = "0.0";
      drag_title.style.opacity = "1";
      drag = null;
      // stop the interval loop
      clearInterval(int_step);
    }
  }

  // return the handle
  return move_back;
}
