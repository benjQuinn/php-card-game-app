<?php

// PP Card Game Helpers
function runPPCardGame($game): void
{
    foreach ($game->players as $player) {
        $player->drawCards($game, 13);
    }

    for ($round = 1; $round < 27; $round++) {
        // Determine who the current leader is
        $leader = $game->players[$game->whoIsLeader()];
        $opponent = $game->players[$game->whoIsNotLeader()];

        // Play the round
        $game->playRound($leader, $opponent);

        // Display the card played by each player
        $playedCards = $game->getPlayedCards();

        foreach ($playedCards as $index => $card) {
            if ($index === "leader") {
                $game->printer->printPlayedCard($leader->getPlayerNumber(), $card->getFace(), $card->getSuit());
            } else {
                $game->printer->printPlayedCard($opponent->getPlayerNumber(), $card->getFace(), $card->getSuit());
            }
        }

        // Determine the round winner and print
        $roundWinner = $game->decideRoundWinner($leader, $opponent, $playedCards);
        $game->printer->printRoundWinner($game->getCurrentRound(), $roundWinner, "cyan");

        // The round winner picks up the two played cards and adds them to their score pile
        foreach ($playedCards as $card) {
            $game->players[$roundWinner]->scorePile->add($card);
            // Both cards are removed from the played cards score pile
            $game->playedCards->remove($card);
        }

        // After the winner picks up the played cards from this round, each player gets a new card from the deck IF the deck is not empty
        if (!empty($game->deck->getCards())) {
            foreach ($game->players as $player) {
                $player->drawCards($game, 1);
            }
        }
    }

    // Print each player's final score
    foreach ($game->players as $player) {
        $game->printer->printScore($player->getPlayerNumber(), $player->scorePile->count(), "magenta");
    }

    // Game controller decides the overall winner and prints
    $game->decideWinner(...$game->players);

    if ($game->winner) {
        $game->printer->printGameWinner($game->winner->getPlayerNumber(), $game->winner->getName(), "green");
    } else {
        $game->printer->printDraw("yellow");
    }
}

function startPPCardGame_CLI($game): void
{
    $game->printer->printLineBr();

    // changes the colour of the title every time the function runs (just so I know it's working as expected)
    $randomColour = array_rand($game->printer->getColours());
    $game->printer->print($game->getName(), $randomColour);

    $game->printer->printLineBr()->printLineBr();

    $game->setUp();

    $playerNames = [];
    foreach ($game->players as $player)
    {
        $playerNames[] = $player->getName();
    }

    $game->printer->printPlayers(...$playerNames);

    $game->printer->printLineBr();

    $game->printer->printPregameWinner($game->pregame->getWinner()->getName(), $game->pregame->getLoser($game->players)->getName(), $game->pregame->name);

    $game->printer->printLineBr()->printLineBr();

    $game->printer->printStartGame("green");

    $game->printer->printLineBr();

    runPPCardGame($game);

    $game->printer->printLineBr();
}