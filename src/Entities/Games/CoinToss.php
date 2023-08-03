<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;

class CoinToss extends TwoPlayerGame
{
    public string $name = "Heads or Tails?";
    private array $coin = ["heads", "tails"];
    private string $toss;

    public function setUp(): CoinToss
    {
        $this->toss = $this->coin[rand(0, 1)];

        return $this;
    }

    public function playRound(Player $firstPlayer, Player $secondPlayer): CoinToss
    {
        $this->setUp();

        $firstPlayerChoice = $this->coin[rand(0, 1)];
        $secondPlayerChoice = $this->coin[rand(0, 1)];

        if ($firstPlayerChoice === $this->toss && $secondPlayerChoice !== $this->toss)
        {
            $this->winner = $firstPlayer;
            $firstPlayer->changePlayerNumber(1);
            $secondPlayer->changePlayerNumber(2);
        }
        if ($firstPlayerChoice !== $this->toss && $secondPlayerChoice === $this->toss)
        {
            $this->winner = $secondPlayer;
            $secondPlayer->changePlayerNumber(1);
            $firstPlayer->changePlayerNumber(2);
        }
        if ($firstPlayerChoice === $this->toss && $secondPlayerChoice === $this->toss || $firstPlayerChoice !== $this->toss && $secondPlayerChoice !== $this->toss)
        {
            $this->playRound($firstPlayer, $secondPlayer);
        }

        return $this;
    }

    public function getWinner(): Player
    {
        return $this->winner;
    }
}