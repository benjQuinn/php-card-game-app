<?php

function startCLI($game): void
{
    $game->printer->printLineBr();

    // changes the colour of the title every time the function runs (just so I know it's working as expected)
    $randomColour = array_rand($game->printer->getColours());
    $game->printer->print($game->getName(), $randomColour);

    $game->printer->printLineBr();
    $game->printer->printLineBr();

    $game->setUp();

    $playerNames = [];
    foreach ($game->players as $player)
    {
        $playerNames[] = $player->getName();
    }

    $game->printer->printPlayers(...$playerNames);

    $game->printer->printLineBr();

    $game->printer->printPregameWinner($game->pregame->getWinner()->getName(), $game->pregame->getLoser($game->players)->getName(), $game->pregame->name);

    $game->printer->printLineBr();
    $game->printer->printLineBr();

    $game->printer->printStartGame("green");

    $game->printer->printLineBr();

    $game->runGame();

    $game->printer->printLineBr();
}