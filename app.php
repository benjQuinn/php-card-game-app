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
    /** MB
     * I'm curious why you need this step for $randIndexOne and $randIndexTwo. Can we not just use the $player array ?
     * Are you trying to set $firstPlayer before the pregame has run?
     */
    if ($randIndexOne !== $randIndexTwo) {
        $firstPlayer = $players[$randIndexOne];
        $secondPlayer = $players[$randIndexTwo];

        $gameController = new GameController([$firstPlayer, $secondPlayer], $pregame);

        $gameController->setUp();

        echo $printer->printPlayers($firstPlayer->getName(), $secondPlayer->getName());
        /** MB:
         * ideally all your echo statements should be in the $printer. This way, we could cleanly switch to a different
         * GUI type.
         */
        echo PHP_EOL;

        foreach ([$firstPlayer, $secondPlayer] as $player)
        {
            if ($player !== $pregame->winner)
            {
                $loser = $player;
            }
        }
        /** MB
         * If the pregame stores the winner reference, why not also store the loser ref, so you don't need to externally
         * calculate it above.
         * Also, we have a pregame interface, so we should not be using attributes directly from the object. If we only
         * ever use functions defined on the interface, then we are leaning into loose coupling and also protecting our code from
         * changes that might be made to the pregame code later. If another dev comes along, they should be able to know they
         * can safely change the Pregames, as long as they honour the interface.
         */
        echo $printer->printPregameWinner($pregame->winner->getName(), $loser->getName(), $pregame->name);
        echo PHP_EOL.PHP_EOL;

        /** MB : maybe the deck should be shuffled as part of the setup() ? This feels like a gameController thing */
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