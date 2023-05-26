<?php

use CardGameApp\Entities\GameController;
use CardGameApp\Entities\Player;
use CardGameApp\Entities\Pregames\CoinToss;
use CardGameApp\Entities\Table;
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
    $pregame = $pregames[rand(0, 1)];

    $randIndexOne = rand(0, 6);
    $randIndexTwo = rand(0, 6);

    if ($randIndexOne !== $randIndexTwo) {
        $firstPlayer = $players[$randIndexOne];
        $secondPlayer = $players[$randIndexTwo];

        $table = new Table([$firstPlayer, $secondPlayer]);

        $table->deck->shuffle();

        $gameController = new GameController($table, $pregame);
        $gameController->setUp();
        echo "Player $table->currentLeader wins $pregame->name and starts the game as leader!";

        echo PHP_EOL.PHP_EOL;

        $table->deck->shuffle();
        echo "The deck is shuffled...";

        echo PHP_EOL.PHP_EOL;
        
        echo "Begin!".PHP_EOL;
        return $gameController->runGame();
    } else {
        return start($players, $pregames);
    }
}

echo PHP_EOL;

echo start($players, $pregames);

echo PHP_EOL.PHP_EOL;