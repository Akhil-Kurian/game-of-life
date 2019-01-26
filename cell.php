<?php

class Cell {

  var $x, $y, $alive, $next_state, $neighbours;

  function __construct($x, $y, $alive = false) {
    $this->x          = $x;
    $this->y          = $y;
    $this->alive      = $alive;
    $this->next_state = null;
    $this->neighbours = null;
  }

  public function display() {
    return ($this->alive ? '0' : ' ');
  }

}
