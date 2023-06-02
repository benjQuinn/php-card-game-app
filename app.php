<?php

use CardGameApp\Entities\GameController;
use CardGameApp\Entities\Player;
use CardGameApp\Entities\Pregames\CoinToss;
use CardGameApp\Entities\Printer;
use CardGameApp\Entities\Pregames\RPS;

const APP_ROOT = __DIR__;
require_once APP_ROOT . '/vendor/autoload.php';

$pregames = [
    new RPS(),
    new CoinToss()
];

$players = [
    new Player("Ben"),
    new Player("Mani"),
    new Player("Aidan"),
    new Player("Mike"),
    new Player("Omar"),
    new Player("Anestis"),
    new Player("Annesley")
];

// starts game with two random players and one random game to decide leader
function start($players, $pregames): string
{
    $printer = new Printer();
    $pregame = $pregames[rand(0, 1)];

    $randIndexOne = rand(0, 6);
    $randIndexTwo = rand(0, 6);

    if ($randIndexOne !== $randIndexTwo) {
        $firstPlayer = $players[$randIndexOne];
        $secondPlayer = $players[$randIndexTwo];

        $gameController = new GameController([$firstPlayer, $secondPlayer], $pregame);

        $gameController->setUp();

        echo $printer->printPregameWinner($gameController->currentLeader, $pregame->name);
        echo PHP_EOL.PHP_EOL;

        $gameController->deck->shuffle();

        echo $printer->printStartGame();
        echo PHP_EOL;

        return $gameController->runGame();
    } else {
        return start($players, $pregames);
    }
}

echo PHP_EOL;

echo start($players, $pregames);

echo PHP_EOL;