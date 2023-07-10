<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;

class RPS extends TwoPlayerGame
{
    public string $name = "Rock-paper-scissors";
    private array $winningConditions = [
        "rock" => "scissors",
        "paper" => "rock",
        "scissors" => "paper"
    ];

    /**
     * Generates rock, paper or scissors string randomly every time it is called
     * @return string
     */
    private function generateRockPaperScissors(): string
    {
        $rps = ["rock", "paper", "scissors"];
        $randomIndex = rand(0, 2);

        return $rps[$randomIndex];
    }

    public function play(Player $firstPlayer, Player $secondPlayer): void
    {

        $firstPlayerRPS = $this->generateRockPaperScissors();
        $secondPlayerRPS = $this->generateRockPaperScissors();

        if ($firstPlayerRPS === $secondPlayerRPS)
        {
            $this->play($firstPlayer, $secondPlayer);
        } if ($this->winningConditions[$firstPlayerRPS] === $secondPlayerRPS)
        {
            $this->winner = $firstPlayer;
            $firstPlayer->changePlayerNumber(1);
            $secondPlayer->changePlayerNumber(2);
        } else
        {
            $this->winner = $secondPlayer;
            $secondPlayer->changePlayerNumber(1);
            $firstPlayer->changePlayerNumber(2);
        }
    }

    public function getWinner(): Player
    {
        return $this->winner;
    }
}