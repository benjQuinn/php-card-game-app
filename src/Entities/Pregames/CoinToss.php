<?php

namespace CardGameApp\Entities\Pregames;

use CardGameApp\Entities\Player;

class CoinToss implements PregameInterface
{
    public string $name = "Heads or Tails?";
    public Player $winner;

    private function play(Player $firstPlayer, Player $secondPlayer) {
        $coin = ["heads", "tails"];

        $firstPlayerChoice = $coin[rand(0, 1)];
        $secondPlayerChoice = $coin[rand(0, 1)];

        $toss = $coin[rand(0, 1)];

        if ($firstPlayerChoice === $toss && $secondPlayerChoice !== $toss)
        {
            $this->winner = $firstPlayer;
        }
        if ($firstPlayerChoice !== $toss && $secondPlayerChoice === $toss)
        {
            $this->winner = $secondPlayer;
        }
        if ($firstPlayerChoice === $toss && $secondPlayerChoice === $toss || $firstPlayerChoice !== $toss && $secondPlayerChoice !== $toss)
        {
            $this->play($firstPlayer, $secondPlayer);
        }
    }

    public function decideLeader(Player $playerOne, Player $playerTwo): int
    {
        $this->play($playerOne, $playerTwo);
        return $this->winner->getPlayerNumber();
    }
}