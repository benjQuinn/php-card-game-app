<?php

namespace App\Entities\Printers;

abstract class CLIPrinter implements Printer
{
    protected array $colours = [
        "default" => "[39m",
        "red" => "[31m",
        "green" => "[32m",
        "yellow" => "[33m",
        "blue" => "[34m",
        "magenta" => "[35m",
        "cyan" => "[36m"
    ];

    protected string $closeColourStatement = "\e[0m";

    public function getColours(): array
    {
        return $this->colours;
    }

    public function print(string $text, string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour].$text.$this->closeColourStatement;

        return $this;
    }

    public function printLineBr(): CLIPrinter
    {
        echo PHP_EOL;

        return $this;
    }

    // Note to self: This needs a refactor to take an array of any amount of players as CLI printer may not always be used for 2 player games
    public function printPlayers(string $firstPlayer, string $secondPlayer, string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour].$firstPlayer." and ".$secondPlayer." step up to the table...".$this->closeColourStatement.PHP_EOL;

        return $this;
    }

    public function printPregameWinner(string $winner, string $loser, string $pregame, string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour].$winner." wins ".$pregame." and starts the game as leader and Player 1.".PHP_EOL."$loser starts the game as Player 2.".$this->closeColourStatement;

        return $this;
    }

    public function printStartGame(string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour]."The deck is shuffled...".PHP_EOL.PHP_EOL."Begin!".$this->closeColourStatement.PHP_EOL;

        return $this;
    }

    public function printRoundWinner(int $round, $winner, string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour]."Round ".$round." winner: Player ".$winner.$this->closeColourStatement.PHP_EOL.PHP_EOL;

        return $this;
    }
    public function printScore(int $playerNumber, int $score, string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour]."Player " .$playerNumber. " score: " .$score.$this->closeColourStatement.PHP_EOL;

        return $this;
    }
    public function printGameWinner(int $playerNumber, $playerName, $colour = "default"): CLIPrinter
    {
        echo PHP_EOL."\e".$this->colours[$colour]."Player " .$playerNumber. ": " .$playerName. " is the winner!".$this->closeColourStatement.PHP_EOL;

        return $this;
    }
    public function printDraw($colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour]."It's a tie!".$this->closeColourStatement.PHP_EOL;

        return $this;
    }





}