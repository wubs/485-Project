
// main 
document.onmousedown = mouse_down;
document.onmouseup = mouse_up;

document.onmouseover = function(e) {
  if (e.target.className == 'dest') {
    over_dest = e.target;
    //console.log('over');
  }
};

over_dest = null;

register_dests();

// end of main

document.onmouseout = function(e) {
  if (e.target.className == 'dest') {
    over_dest = null;
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
// fly is the element that is being dragged.
//

function register_dests() {
  var dests = document.getElementsByClassName('dest');

  for (var i = 0; i < dests.length; i++) {
    var el = dests[i];
    //el.onmouseover = function(e) {over_dest = e.target; console.log('over');};
    //el.onmouseout = function(e) {over_dest = null; console.log('out');};
    el.style.zIndex = 500;
  }
}

function mouse_down(e) {
  e = e || window.event;

  down_x = e.clientX;
  down_y = e.clientY;

  fly = e.target;

  if (fly.className != "drag") {
    fly = null;
    return false;
  }

  semi = fly.nextSibling.nextSibling;

  offset_x = position_to_int(fly.style.left);
  offset_y = position_to_int(fly.style.top);

  semi.style.display = "inline";
  semi.style.opacity = "0.8";
  fly.style.opacity = "0.2";

  old_zIndex = fly.style.zIndex;

  old_fly = fly;
  fly = semi;

  fly.style.pointerEvents = "none";
  // since fly element is always below the cursor
  // we have to disable pointerEvents for fly
  // otherwise the event will not happen on the destination
  fly.style.zIndex = 1;

  document.onmousemove = mouse_move;
  document.body.focus();

  return false;
}

function mouse_move(e) {
  e = e || window.event;
  fly.style.left = (offset_x + e.clientX - down_x) + 'px';
  fly.style.top = (offset_y + e.clientY - down_y) + 'px';
}

function mouse_up(e) {
  e = e || window.event;
  if (fly != null) {
    if (over_dest) {
      // user dropped the element at
      // right location
      console.log(e.target);
      console.log(over_dest);


      // trigger ajax to grand user access to album
      //
      // username = over_dest.value()
      // albumid = fly.value()

    } 

    // created a closure
    step = restore(e.clientX + offset_x - down_x, e.clientY + offset_y - down_y);

    // this will move the block back to origin pos
    // setInterval will stop by step() if it is done
    int_step = setInterval(step, 1);
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
      fly.style.left = this.cur_x + 'px';
      fly.style.top = this.cur_y + 'px';
      this.count++;
    } else {
      // done, restore fly attributes
      fly.style.zIndex = old_zIndex;
      fly.style.pointerEvents = null;
      fly.style.left = offset_x + 'px';
      fly.style.top = offset_y + 'px';
      document.onmousemove = null;
      semi.style.display = "none";
      semi.style.opacity = "0.0";
      old_fly.style.opacity = "1";
      fly = null;
      // stop the interval loop
      clearInterval(int_step);
    }
  }

  // return the handle
  return move_back;
}
