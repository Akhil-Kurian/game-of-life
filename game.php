<?php
  require_once('cell.php');

  class GameOfLife {

    public $play;

    function __construct($width, $height) {
      $this->width              = $width;
      $this->height             = $height;

      $this->play               = 0;
      $this->cells              = [];
      $this->cached_directions  = [
        [-1, 1], [0, 1], [1, 1], // above
        [-1, 0], [1, 0], // sides
        [-1, -1], [0, -1], [1, -1] // below
      ];

      $this->populate_cells();
      $this->prepopulate_neighbours();
    }

    public function play() {
      // First determine the action for all cells
      foreach ($this->cells as $cell) {
        $alive_neighbours = $this->alive_neighbours_around($cell);
        if (!$cell->alive && $alive_neighbours == 3) {
          $cell->next_state = 1;
        } else if ($alive_neighbours < 2 || $alive_neighbours > 3) {
          $cell->next_state = 0;
        }
      }

      foreach ($this->cells as $cell) {
        if ($cell->next_state == 1) {
          $cell->alive = true;
        } else if ($cell->next_state == 0) {
          $cell->alive = false;
        }
      }

      $this->play += 1;
    }

    private function populate_cells() {
      for ($y = 0; $y <= $this->height; $y++) {
        for ($x = 0; $x <= $this->width; $x++) {
          $alive = (rand(0, 100) <= 20);
          $this->add_cell($x, $y, $alive);
        }
      }
    }

    private function prepopulate_neighbours() {
      foreach ($this->cells as $cell) {
        $this->neighbours_around($cell);
      }
    }

    private function cell_at($x, $y) {
      if (isset($this->cells["$x-$y"])) {
        return $this->cells["$x-$y"];
      }
    }

    private function neighbours_around($cell) {
      if ($cell->neighbours == null) {
        $cell->neighbours = array();
        foreach ($this->cached_directions as $set) {
          $neighbour = $this->cell_at(
            ($cell->x + $set[0]),
            ($cell->y + $set[1])
          );
          if ($neighbour != null) {
            $cell->neighbours[] = $neighbour;
          }
        }
      }

      return $cell->neighbours;
    }

    private function alive_neighbours_around($cell) {
      $alive_neighbours = 0;
      foreach ($this->neighbours_around($cell) as $neighbour) {
        if ($neighbour->alive) {
          $alive_neighbours++;
        }
      }
      return $alive_neighbours;
    }

    private function add_cell($x, $y, $alive = false) {
      if ($this->cell_at($x, $y) != null) {
        die ('Location is not empty');
      }

      $cell = new Cell($x, $y, $alive);
      $this->cells["$x-$y"] = $cell;
      return $this->cell_at($x, $y);
    }

    public function render() {
      $rendering = '';
      for ($y = 0; $y <= $this->height; $y++) {
        for ($x = 0; $x <= $this->width; $x++) {
          $cell = $this->cell_at($x, $y);
          $rendering .= $cell->display();
        }
        $rendering .= PHP_EOL;
      }
      return $rendering;
    }

  }
