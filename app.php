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
 * however you still have some bits of display code spread across the code. Your PPCardGame is creating its own
 * version of the Printer. Maybe you could use dependency injection instead, so you have 1 printer in the code.
 *
 */

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

$game = new PPCardGame([$players[$randIndexOne], $players[$randIndexTwo]], $printer, "Procure Plus Card Game", new Deck(), new Pile(), $pregame);

startPPCardGame_CLI($game);