<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;

abstract class TwoPlayerGame implements Game
{
    protected string $name;
    public Player|false $winner = false;

    public function getName(): string
    {
        return $this->name;
    }

    public function getWinner(): Player
    {
        return $this->winner;
    }

    public function getLoser(array $players): Player|false
    {
        $loser = false;

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