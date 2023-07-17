<?php

namespace App\Entities\Cards;

class Ace extends Card
{
    public function __construct(string $suit, int $value)
    {
        $data["suit"] = $suit;
        $data["face"] = "Ace";
        $data["value"] = $value;
        parent::__construct($data);
    }
}