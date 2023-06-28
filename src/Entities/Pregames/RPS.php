<?php

namespace CardGameApp\Entities\Pregames;

use CardGameApp\Entities\Players\PPCardGamePlayer;

class RPS extends Pregame
{
    public string $name = "Rock-paper-scissors";
    private array $winningConditions = [
        "rock" => "scissors",
        "paper" => "rock",
        "scissors" => "paper"
    ];

    private function play(PPCardGamePlayer $firstPlayer, PPCardGamePlayer $secondPlayer) {

        $firstPlayerRPS = $firstPlayer->generateRockPaperScissors();
        $secondPlayerRPS = $secondPlayer->generateRockPaperScissors();

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

    public function decideWinner(PPCardGamePlayer $playerOne, PPCardGamePlayer $playerTwo): int
    {
        $this->play($playerOne, $playerTwo);
        return $this->winner->getPlayerNumber();
    }
}