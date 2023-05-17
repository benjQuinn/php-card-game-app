<?php

use CardGameApp\Entities\GameController;
use CardGameApp\Entities\Player;
use CardGameApp\Entities\Table;

require_once realpath("vendor/autoload.php");

$players = [
    new Player("Ben"),
    new Player("Mani"),
    new Player("Aidan"),
    new Player("Mike"),
    new Player("Omar"),
    new Player("Anestis"),
    new Player("Annesley")
];

// adds random players to the table each time that app file is run
$table = new Table([$players[rand(0, 6)], $players[rand(0, 6)]]);

$gameController = new GameController($table);

echo PHP_EOL;

echo $gameController->runGame();

echo PHP_EOL;

$gameController->restartGame();