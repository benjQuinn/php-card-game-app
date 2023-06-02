<?php

namespace CardGameApp\Entities\Pregames;

use CardGameApp\Entities\Player;

class RPS implements PregameInterface
{
    public string $name = "Rock-paper-scissors";
    public Player $winner;
    private array $winningConditions = [
        "rock" => "scissors",
        "paper" => "rock",
        "scissors" => "paper"
    ];

    private function play(Player $firstPlayer, Player $secondPlayer) {

        $firstPlayerRPS = $firstPlayer->generateRockPaperScissors();
        $secondPlayerRPS = $secondPlayer->generateRockPaperScissors();

        if ($firstPlayerRPS === $secondPlayerRPS)
        {
            $this->play($firstPlayer, $secondPlayer);
        } if ($this->winningConditions[$firstPlayerRPS] === $secondPlayerRPS)
        {
            $this->winner = $firstPlayer;
        } else
        {
            $this->winner = $secondPlayer;
        }
    }

    public function decideLeader(Player $playerOne, Player $playerTwo): int
    {
        $this->play($playerOne, $playerTwo);
        return $this->winner->getPlayerNumber();
    }
}