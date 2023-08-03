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
    private string $firstPlayerRPS;
    private string $secondPlayerRPS;

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

    public function setUp(): RPS
    {
        $this->firstPlayerRPS = $this->generateRockPaperScissors();
        $this->secondPlayerRPS = $this->generateRockPaperScissors();

        return $this;
    }

    public function playRound(Player $firstPlayer, Player $secondPlayer): RPS
    {
        $this->setUp();

        if ($this->firstPlayerRPS === $this->secondPlayerRPS)
        {
            $this->playRound($firstPlayer, $secondPlayer);
        } if ($this->winningConditions[$this->firstPlayerRPS] === $this->secondPlayerRPS)
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

        return $this;
    }

    public function getWinner(): Player
    {
        return $this->winner;
    }
}