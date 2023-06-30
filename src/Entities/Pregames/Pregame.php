<?php

namespace App\Entities\Pregames;

use App\Entities\Players\PPCardGamePlayer;

abstract class Pregame implements PregameInterface
{
    public PPCardGamePlayer $winner;

    public function getPregameWinner(): PPCardGamePlayer
    {
        return $this->winner;
    }

    public function getPregameLoser(array $players): PPCardGamePlayer
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