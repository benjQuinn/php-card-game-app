<?php

use App\Entities\Games\PPCardGame;

function startCLI($players, $pregames, $printer): void
{
    $pregame = $pregames[rand(0, 1)];

    $randIndexOne = rand(0, 6);
    $randIndexTwo = rand(0, 6);

    // Selects players from the array passed at random to automate game. Function is called recursively if the random selections are the same
    if ($randIndexOne !== $randIndexTwo) {
        $firstPlayer = $players[$randIndexOne];
        $secondPlayer = $players[$randIndexTwo];

        $gameController = new PPCardGame([$firstPlayer, $secondPlayer], $pregame, $printer);

        $gameController->setUp();

        $printer->printPlayers($firstPlayer->getName(), $secondPlayer->getName());

        $printer->printLineBr();

        $printer->printPregameWinner($pregame->getWinner()->getName(), $pregame->getLoser($gameController->players)->getName(), $pregame->name);

        $printer->printLineBr();
        $printer->printLineBr();

        $printer->printStartGame();

        $printer->printLineBr();

        $gameController->runGame();
    } else {
        startCLI($players, $pregames, $printer);
    }
}