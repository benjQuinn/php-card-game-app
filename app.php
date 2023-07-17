<?php

use App\Entities\Collections\Deck;
use App\Entities\Collections\Hand;
use App\Entities\Collections\Pile;
use App\Entities\Games\PPCardGame;
use App\Entities\Players\PPCardGamePlayer;
use App\Entities\Games\CoinToss;
use App\Entities\Games\RPS;
use App\Entities\Printers\PPCardGameCLIPrinter;

const APP_ROOT = __DIR__;
require_once APP_ROOT . '/vendor/autoload.php';

// PP Card Game Setup
$printer = new PPCardGameCLIPrinter();

$players = [
    new PPCardGamePlayer("Ben", new Hand(), new Pile()),
    new PPCardGamePlayer("Mani", new Hand(), new Pile()),
    new PPCardGamePlayer("Aidan", new Hand(), new Pile()),
    new PPCardGamePlayer("Mike", new Hand(), new Pile()),
    new PPCardGamePlayer("Omar", new Hand(), new Pile()),
    new PPCardGamePlayer("Anestis", new Hand(), new Pile()),
    new PPCardGamePlayer("Annesley", new Hand(), new Pile())
];

$randIndexOne = rand(0, count($players) - 1);
do {
    $randIndexTwo = rand(0, count($players) - 1);
} while ($randIndexOne === $randIndexTwo);

$pregames = [
    new RPS(),
    new CoinToss()
];

$pregame = $pregames[rand(0, count($pregames) - 1)];

$game = new PPCardGame([$players[$randIndexOne], $players[$randIndexTwo]], $printer, "Procure Plus Card Game", new Deck(14, true, 2, 11), new Pile(), $pregame);

// Blackjack Setup
    ///


startPPCardGame_CLI($game);