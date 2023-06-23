<?php

use CardGameApp\Entities\GameController;

function startCLI($players, $pregames, $printer): string
{
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

        $printer->printPlayers($firstPlayer->getName(), $secondPlayer->getName());
        /** MB:
         * ideally all your echo statements should be in the $printer. This way, we could cleanly switch to a different
         * GUI type.
         */
        $printer->printLineBr();

        /** MB
         * If the pregame stores the winner reference, why not also store the loser ref, so you don't need to externally
         * calculate it above.
         * Also, we have a pregame interface, so we should not be using attributes directly from the object. If we only
         * ever use functions defined on the interface, then we are leaning into loose coupling and also protecting our code from
         * changes that might be made to the pregame code later. If another dev comes along, they should be able to know they
         * can safely change the Pregames, as long as they honour the interface.
         */
        $printer->printPregameWinner($pregame->getPreGameWinner()->getName(), $pregame->getPregameLoser($players)->getName(), $pregame->name);

        $printer->printLineBr();
        $printer->printLineBr();

        /** MB : maybe the deck should be shuffled as part of the setup() ? This feels like a gameController thing */
        $gameController->deck->shuffle();

        $printer->printStartGame();

        $printer->printLineBr();

        return $gameController->runGame();
    } else {
        return startCLI($players, $pregames, $printer);
    }
}