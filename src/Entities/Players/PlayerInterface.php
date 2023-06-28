<?php

namespace CardGameApp\Entities\Players;

interface PlayerInterface
{
    public function getName();

    public function getPlayerNumber();

    public function changePlayerNumber(int $number);
}