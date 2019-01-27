<?php
  require_once('game.php');

  class Play {

    public static function start() {
      $game = new GameOfLife(150, 40);
      echo $game->render();

      while (true) {
        $game->play();
        $rendered = $game->render();

        $output = "Play count: $game->play";
        $output .= "\n".$rendered;

        // Clear the screen
        system('clear');
        // Print to the screen
        echo $output;
      }
    }

  }

  Play::start();
