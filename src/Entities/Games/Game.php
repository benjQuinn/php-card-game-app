<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;

interface Game
{
    public function getName(): string;

    public function getWinner(): Player;

    public function getLoser(array $players): Player|false;

    public function setUp(): void;
}