<?php

namespace CardGameApp\Entities\Pregames;

use CardGameApp\Entities\Player;

class CoinToss implements PregameInterface
{
    public string $name = "Heads or Tails?";
    public Player $winner;
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

    public function decideLeader(Player $playerOne, Player $playerTwo): int
    {
        $this->play($playerOne, $playerTwo);
        return $this->winner->getPlayerNumber();
    }

    public function getPregameLoser($players): Player
    {
        foreach ($players as $player)
        {
            if ($player !== $this->winner)
            {
                $loser = $player;
            }
        }
        return $loser;
    }
}