<?php

namespace App\Entities\Players;

interface PlayerInterface
{
    public function getName();

    public function getPlayerNumber();

    public function changePlayerNumber(int $number);
}