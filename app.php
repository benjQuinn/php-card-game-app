<?php
/** MB - General comments on the code
 * Looking a lot better. I like how you typed the card objects and collections.
 * A few things to think about and change with the pregames and your use of an interface.
 *
 * Is there any difference between the main game and a pregame, regarding the interface?
 * All games have:
 *   - players
 *   - some looping play code
 *   - then a winner and loser.
 * Maybe the interface could just be TwoPlayerGame ?
 *
 * You have started on the path of separating your presentation and mode layer, which would be the MVC design pattern,
 * however you still have some bits of display code spread across the code. Your GameController is creating its own
 * version of the Printer. Maybe you could use dependency injection instead, so you have 1 printer in the code.
 *
 */
use CardGameApp\Entities\Player;
use CardGameApp\Entities\Pregames\CoinToss;
use CardGameApp\Entities\PPCardGameCLIPrinter;
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

$printer = new PPCardGameCLIPrinter();

$printer->printLineBr();

startCLI($players, $pregames, $printer);

$printer->printLineBr();