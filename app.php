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

use App\Entities\Players\PPCardGamePlayer;
use App\Entities\Pregames\CoinToss;
use App\Entities\Pregames\RPS;
use App\Entities\Printers\PPCardGameCLIPrinter;

const APP_ROOT = __DIR__;
require_once APP_ROOT . '/vendor/autoload.php';

$pregames = [
    new RPS(),
    new CoinToss()
];

$players = [
    new PPCardGamePlayer("Ben"),
    new PPCardGamePlayer("Mani"),
    new PPCardGamePlayer("Aidan"),
    new PPCardGamePlayer("Mike"),
    new PPCardGamePlayer("Omar"),
    new PPCardGamePlayer("Anestis"),
    new PPCardGamePlayer("Annesley")
];

$printer = new PPCardGameCLIPrinter();

$printer->printLineBr();

startCLI($players, $pregames, $printer);

$printer->printLineBr();