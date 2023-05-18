<?php

use CardGameApp\Entities\GameController;
use CardGameApp\Entities\Player;
use CardGameApp\Entities\Table;

// MB: "vendor/autoload.php" is relative to where you run it, you want
//  __DIR__."/vendor/autoload.php" or APP_ROOT."/vendor/autoload.php"
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
/** MB: Example:A code example for point in deck.php __Construct */
$d = new \CardGameApp\Entities\Deck();
$d->getCards()[53]->changeCardValue('value',45);
foreach($d->getCards() as $i=>$c){
    echo $i.">".$c->getSuit() . " - ".$c->getFace()." = ".$c->getValue()."\n";
}
exit;

// adds random players to the table each time that app file is run
$table = new Table([$players[rand(0, 6)], $players[rand(0, 6)]]);

$gameController = new GameController($table);

echo PHP_EOL;

echo $gameController->runGame();

echo PHP_EOL;

$gameController->restartGame();