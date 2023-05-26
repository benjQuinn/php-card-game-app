<?php

use CardGameApp\Entities\GameController;
use CardGameApp\Entities\Player;
use CardGameApp\Entities\Table;

const APP_ROOT = __DIR__;
require_once APP_ROOT . '/vendor/autoload.php';

$players = [
    new Player("Ben"),
    new Player("Mani"),
    new Player("Aidan"),
    new Player("Mike"),
    new Player("Omar"),
    new Player("Anestis"),
    new Player("Annesley")
];

// starts game with two random players
function start($players): string
{
    $randIndexOne = rand(0, 6);
    $randIndexTwo = rand(0, 6);

    if ($randIndexOne !== $randIndexTwo) {
        $firstPlayer = $players[$randIndexOne];
        $secondPlayer = $players[$randIndexTwo];

        $table = new Table([$firstPlayer, $secondPlayer]);

        $gameController = new GameController($table);
        $table->deck->shuffle();

        return $gameController->runGame();
    } else {
        return start($players);
    }
}

echo start($players);