<?php

use App\Entities\Games\Blackjack;
use App\Entities\Games\CardGame;
use App\Entities\Games\PPCardGame;
use App\Entities\Printers\BlackjackCLIPrinter;
use App\Entities\Printers\CLIPrinter;
use App\Entities\Printers\PPCardGameCLIPrinter;

// PP Card Game Helper

// Note to self: I need to change the hierarchy of the printer classes - should be Printer -> PPCardGamePrinter -> PPCardGameCLIPrinter
// PPCardGamePrinter should be passed into the function below rather than the specific CLI printer (and the same with the blackjack function)
function runPPCardGame(PPCardGame $game, PPCardGameCLIPrinter $printer, int $rounds = 27): void
{
    foreach ($game->players as $player)
    {
        $player->drawCards($game, 13);
    }

    for ($round = 1; $round < $rounds; $round++)
    {
        // Determine who the current leader is
        $leader = $game->players[$game->whoIsLeader()];
        $opponent = $game->players[$game->whoIsNotLeader()];

        // Play the round
        $game->playRound($leader, $opponent);

        // Display the card played by each player
        $playedCards = $game->getPlayedCards();

        foreach ($playedCards as $index => $card) {
            if ($index === "leader") {
                $printer->printPlayedCard($leader->getPlayerNumber(), $card->getFace(), $card->getSuit());
            } else {
                $printer->printPlayedCard($opponent->getPlayerNumber(), $card->getFace(), $card->getSuit());
            }
        }

        // Determine the round winner and print
        $roundWinner = $game->decideRoundWinner($leader, $opponent, $playedCards);
        $printer->printRoundWinner($game->getCurrentRound(), $roundWinner, "cyan");

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
        $printer->printScore($player->getPlayerNumber(), $player->scorePile->count(), "magenta");
    }

    // Game controller decides the overall winner and prints
    $game->decideWinner();

    if ($game->winner) {
        $printer->printGameWinner($game->winner->getPlayerNumber(), $game->winner->getName(), "green");
    } else {
        $printer->printDraw("yellow");
    }
}

// Blackjack Helper
function runBlackjack(Blackjack $game, BlackjackCLIPrinter $printer, int $rounds = 10): void
{
    $leader = $game->players[$game->whoIsLeader()];
    $opponent = $game->players[$game->whoIsNotLeader()];

    // Play the given number of rounds
    for ($round = 1; $round <= $rounds; $round++)
    {
        $game->startRound();

        foreach ($game->players as $player)
        {
            // While player is not bust, doesn't have blackjack and hand value is less than 16 then have to hit
            // (having to hit when the hand value is < 17 is the rule the dealer would usually play by in blackjack irl - I've used it here so that I can automate a game)
            while (!$player->isBust() && !$player->isBlackjack() && $player->getHandValue() < 17)
            {
                $player->hit($game);
            }
            $player->stand();

            if ($player->isBlackjack())
            {
                $printer->printHand($player->getPlayerNumber(), $player->hand->getCards(), $player->getHandValue(), true);
            } else if ($player->isBust()) {
                $printer->printHand($player->getPlayerNumber(), $player->hand->getCards(), $player->getHandValue(), false, true);
            } else {
                $printer->printHand($player->getPlayerNumber(), $player->hand->getCards(), $player->getHandValue());
            }
        }

        // Print round winner
        $roundWinner = $game->decideRoundWinner();
        if (!$roundWinner)
        {
            $printer->printDraw("cyan");
        } else {
            $printer->printRoundWinner($game->getCurrentRound(), $roundWinner, "cyan");
        }

        // Players put cards in the played cards pile
        foreach ($game->players as $player)
        {
            foreach ($player->hand->getCards() as $card)
            {
                $game->playedCards->add($card);
            }
            $player->hand->removeAllCards();
        }

        $printer->printLineBr();
    }

    // After all the rounds are played print each player's final score
    foreach ($game->players as $player) {
        $printer->printScore($player->getPlayerNumber(), $player->getRoundsWon(), "magenta");
    }

    // Game controller decides the overall winner and prints
    $game->decideWinner();

    if ($game->winner) {
        $printer->printGameWinner($game->winner->getPlayerNumber(), $game->winner->getName(), "green");
    } else {
        $printer->printDraw("yellow");
    }
}




function startCardGame_CLI(CardGame $game, string $function, int $rounds, CLIPrinter $printer): void
{
    $printer->printLineBr();

    // changes the colour of the title every time the function runs (just so I know it's working as expected)
    $randomColour = array_rand($printer->getColours());
    $printer->print($game->getName(), $randomColour);

    $printer->printLineBr()->printLineBr();

    $game->setUp();

    $playerNames = [];
    foreach ($game->players as $player)
    {
        $playerNames[] = $player->getName();
    }

    $printer->printPlayers(...$playerNames);

    $printer->printLineBr();

    $printer->printPregameWinner($game->pregame->getWinner()->getName(), $game->pregame->getLoser($game->players)->getName(), $game->pregame->name);

    $printer->printLineBr()->printLineBr();

    $printer->printStartGame("green");

    $printer->printLineBr();

    // Note to self: Need to limit the amount of rounds or create new deck when the cards run out
    call_user_func($function, $game, $printer, $rounds);

    $printer->printLineBr();
}