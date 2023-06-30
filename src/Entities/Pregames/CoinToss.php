<?php

namespace App\Entities\Pregames;

use App\Entities\Players\PPCardGamePlayer;

class CoinToss extends Pregame
{
    public string $name = "Heads or Tails?";
    private array $coin = ["heads", "tails"];

    private function play(PPCardGamePlayer $firstPlayer, PPCardGamePlayer $secondPlayer) {
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

    public function decideWinner(PPCardGamePlayer $playerOne, PPCardGamePlayer $playerTwo): int
    {
        $this->play($playerOne, $playerTwo);
        return $this->winner->getPlayerNumber();
    }
}