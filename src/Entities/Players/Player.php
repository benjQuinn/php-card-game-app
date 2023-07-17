<?php

namespace App\Entities\Players;

interface Player
{
    public function getName(): string;

    public function getPlayerNumber(): int;

    public function changePlayerNumber(int $number): Player;
}