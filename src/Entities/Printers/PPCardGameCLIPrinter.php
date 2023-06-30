<?php

namespace App\Entities\Printers;

class PPCardGameCLIPrinter implements Printer
{
    private array $colours = [
        "default" => "[39m",
        "red" => "[31m",
        "green" => "[32m",
        "yellow" => "[33m",
        "blue" => "[34m",
        "magenta" => "[35m",
        "cyan" => "[36m"
    ];

    private string $closeColourStatement = "\e[0m";

    public function printPlayers(string $firstPlayer, string $secondPlayer, string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour].$firstPlayer." and ".$secondPlayer." step up to the table...".$this->closeColourStatement.PHP_EOL;
    }
    public function printPregameWinner(string $winner, string $loser, string $pregame, string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour].$winner." wins ".$pregame." and starts the game as leader and Player 1.".PHP_EOL."$loser starts the game as Player 2.".$this->closeColourStatement;
    }

    public function printStartGame(string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour]."The deck is shuffled...".PHP_EOL.PHP_EOL."Begin!".$this->closeColourStatement.PHP_EOL;
    }
    public function printPlayedCard(int $playerNumber, string $cardFace, string $cardSuit, string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour]."Player ".$playerNumber." card: ".$cardFace." ".$cardSuit.$this->closeColourStatement.PHP_EOL;
    }
    public function printRoundWinner(int $round, $winner, string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour]."Round ".$round." winner: Player ".$winner.$this->closeColourStatement.PHP_EOL.PHP_EOL;
    }
    public function printScore(int $playerNumber, int $score, string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour]."Player " .$playerNumber. " score: " .$score.$this->closeColourStatement.PHP_EOL;
    }
    public function printGameWinner(int $playerNumber, $playerName, $colour = "default"): void
    {
        echo PHP_EOL."\e".$this->colours[$colour]."Player " .$playerNumber. ": " .$playerName. " is the winner!".$this->closeColourStatement.PHP_EOL;
    }
    public function printDraw($colour = "default"): void
    {
        echo PHP_EOL."\e".$this->colours[$colour]."It's a tie!".$this->closeColourStatement.PHP_EOL;
    }

    public function printLineBr(): void
    {
        echo PHP_EOL;
    }
    public function print(string $text, string $colour = "default"): void
    {
        echo "\e".$this->colours[$colour].$text.$this->closeColourStatement;
    }

    public function getColours(): array
    {
        return $this->colours;
    }
}
