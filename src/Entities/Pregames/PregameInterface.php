<?php

namespace CardGameApp\Entities\Pregames;

use CardGameApp\Entities\Player;

interface PregameInterface
{
    public function decideLeader(Player $playerOne, Player $playerTwo): int;
}