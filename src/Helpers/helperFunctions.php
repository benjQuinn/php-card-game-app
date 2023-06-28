<?php

use CardGameApp\Entities\GameController;

function startCLI($players, $pregames, $printer): string
{
    $pregame = $pregames[rand(0, 1)];

    $randIndexOne = rand(0, 6);
    $randIndexTwo = rand(0, 6);

    // Selects players from the array passed at random to automate game. Function is called recursively if the random selections are the same
    if ($randIndexOne !== $randIndexTwo) {
        $firstPlayer = $players[$randIndexOne];
        $secondPlayer = $players[$randIndexTwo];

        $gameController = new GameController([$firstPlayer, $secondPlayer], $pregame, $printer);

        $gameController->setUp();

        $printer->printPlayers($firstPlayer->getName(), $secondPlayer->getName());

        $printer->printLineBr();

        $printer->printPregameWinner($pregame->getPreGameWinner()->getName(), $pregame->getPregameLoser($gameController->players)->getName(), $pregame->name);

        $printer->printLineBr();
        $printer->printLineBr();

        $printer->printStartGame();

        $printer->printLineBr();

        return $gameController->runGame();
    } else {
        return startCLI($players, $pregames, $printer);
    }
}