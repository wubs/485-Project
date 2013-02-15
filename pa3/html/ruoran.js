document.onmousedown = mouse_down;
document.onmouseup = mouse_up;

function position_to_int(pos) {
  var i = parseInt(pos.replace('px', ''));
  if (i) {
    return i;
  } else {
    return 0;
  }
}

function mouse_down(e) {
  e = e || window.event;

  down_x = e.clientX;
  down_y = e.clientY;

  fly = e.target;

  offset_x = position_to_int(fly.style.left);
  offset_y = position_to_int(fly.style.top);

  old_zIndex = fly.style.zIndex;

  fly.style.zIndex = 100000;

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
  if (fly != null) {
    step = restore(e.clientX + offset_x - down_x, e.clientY + offset_y - down_y);
    int_step = setInterval(step, 0.5);
  }
}

function restore(x, y) {

  this.period = 30.0;
  this.cur_x = x;
  this.cur_y = y;
  this.unit_x = (x - offset_x) / this.period; 
  this.unit_y = (y - offset_y) / this.period; 
  this.count = 0

  var move_back = function () {
    console.log('step');
    if (count < this.period) {
      this.cur_x -= this.unit_x; 
      this.cur_y -= this.unit_y; 
      fly.style.left = this.cur_x + 'px';
      fly.style.top = this.cur_y + 'px';
      this.count++;
    } else {
      fly.style.zIndex = old_zIndex;
      fly.style.left = offset_x + 'px';
      fly.style.top = offset_y + 'px';
      document.onmousemove = null;
      fly = null;
      clearInterval(int_step);
    }
  }

  return move_back;
}


