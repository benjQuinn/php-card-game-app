<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;

class CoinToss extends TwoPlayerGame
{
    public string $name = "Heads or Tails?";
    private array $coin = ["heads", "tails"];

    private function play(Player $firstPlayer, Player $secondPlayer) {
        $firstPlayerChoice = $this->coin[rand(0, 1)];
        $secondPlayerChoice = $this->coin[rand(0, 1)];

        $toss = $this->coin[rand(0, 1)];

        if ($firstPlayerChoice === $toss && $secondPlayerChoice !== $toss)
        {
            $this->winner = $firstPlayer;
            $firstPlayer->changePlayerNumber(1);
            $secondPlayer->changePlayerNumber(2);
        }
        if ($firstPlayerChoice !== $toss && $secondPlayerChoice === $toss)
        {
            $this->winner = $secondPlayer;
            $secondPlayer->changePlayerNumber(1);
            $firstPlayer->changePlayerNumber(2);
        }
        if ($firstPlayerChoice === $toss && $secondPlayerChoice === $toss || $firstPlayerChoice !== $toss && $secondPlayerChoice !== $toss)
        {
            $this->play($firstPlayer, $secondPlayer);
        }
    }

    public function decideWinner(Player $playerOne, Player $playerTwo): Player
    {
        $this->play($playerOne, $playerTwo);
        return $this->winner;
    }
}