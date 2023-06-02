<?php

namespace CardGameApp\Entities;

class Printer
{
    public function printPregameWinner(int $playerNumber, string $pregame): string
    {
        return "Player $playerNumber wins $pregame and starts the game as leader!".PHP_EOL;
    }

    public function printStartGame(): string
    {
        return "The deck is shuffled...".PHP_EOL.PHP_EOL."Begin!".PHP_EOL;
    }
    public function printPlayedCard(int $playerNumber, string $cardFace, string $cardSuit): string
    {
        return "Player ".$playerNumber." card: ".$cardFace." ".$cardSuit.PHP_EOL;
    }
    public function printRoundWinner(int $round, $winner): string
    {
        return "Round ".$round." winner: Player ".$winner.PHP_EOL.PHP_EOL;
    }
    public function printScore(int $playerNumber, int $score): string
    {
        return "Player " .$playerNumber. " score: " .$score.PHP_EOL;
    }
    public function printGameWinner(int $playerNumber, $playerName): string
    {
        return PHP_EOL."Player " .$playerNumber. ": " .$playerName. " is the winner!".PHP_EOL;
    }
    public function printDraw(): string
    {
        return PHP_EOL."It's a tie!".PHP_EOL;
    }
}
