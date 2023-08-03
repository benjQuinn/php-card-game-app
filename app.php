<?php

use App\Entities\Collections\Deck;
use App\Entities\Collections\Hand;
use App\Entities\Collections\Pile;
use App\Entities\Games\Blackjack;
use App\Entities\Games\PPCardGame;
use App\Entities\Players\BlackjackPlayer;
use App\Entities\Players\PPCardGamePlayer;
use App\Entities\Games\CoinToss;
use App\Entities\Games\RPS;
use App\Entities\Printers\BlackjackCLIPrinter;
use App\Entities\Printers\PPCardGameCLIPrinter;

const APP_ROOT = __DIR__;
require_once APP_ROOT . '/vendor/autoload.php';

// PP Card Game Setup
$PPCardGamePrinter = new PPCardGameCLIPrinter();

$PPCardGamePlayers = [
    new PPCardGamePlayer("Ben", new Hand(), new Pile()),
    new PPCardGamePlayer("Mani", new Hand(), new Pile()),
    new PPCardGamePlayer("Aidan", new Hand(), new Pile()),
    new PPCardGamePlayer("Mike", new Hand(), new Pile()),
    new PPCardGamePlayer("Omar", new Hand(), new Pile()),
    new PPCardGamePlayer("Anestis", new Hand(), new Pile()),
    new PPCardGamePlayer("Annesley", new Hand(), new Pile())
];

$randIndexOne = rand(0, count($PPCardGamePlayers) - 1);
do {
    $randIndexTwo = rand(0, count($PPCardGamePlayers) - 1);
} while ($randIndexOne === $randIndexTwo);

// Blackjack Setup
$blackjackPrinter = new BlackjackCLIPrinter();

$blackjackPlayers = [
    new BlackjackPlayer("Ben", new Hand()),
    new BlackjackPlayer("Mani", new Hand()),
];

$pregames = [
    new RPS(),
    new CoinToss()
];

$pregame = $pregames[rand(0, count($pregames) - 1)];

$PPCardGame = new PPCardGame([$PPCardGamePlayers[$randIndexOne], $PPCardGamePlayers[$randIndexTwo]], "Procure Plus Card Game", new Deck(14, true, 2, 11), new Pile(), $pregame);
$blackjack = new Blackjack($blackjackPlayers, "Blackjack", new Deck(11), new Pile(), $pregame);

// Start PP Card Game
startCardGame_CLI($PPCardGame, "runPPCardGame", 27, $PPCardGamePrinter);

// Start Blackjack
startCardGame_CLI($blackjack, "runBlackjack", 5, $blackjackPrinter);