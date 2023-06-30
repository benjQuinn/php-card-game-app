<?php

namespace App\Entities\Players;

interface Player
{
    public function getName();

    public function getPlayerNumber();

    public function changePlayerNumber(int $number);
}