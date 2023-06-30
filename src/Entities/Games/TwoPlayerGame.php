<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;

abstract class TwoPlayerGame implements Game
{
    public Player|false $winner;

    public function getWinner(): Player
    {
        return $this->winner;
    }

    public function getLoser(array $players): Player
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