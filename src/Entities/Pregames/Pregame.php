<?php

namespace CardGameApp\Entities\Pregames;

use CardGameApp\Entities\Player;

abstract class Pregame implements PregameInterface
{
    public Player $winner;

    public function getPregameWinner(): Player
    {
        return $this->winner;
    }

    public function getPregameLoser(array $players): Player
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