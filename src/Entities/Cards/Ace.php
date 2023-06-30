<?php

namespace App\Entities\Cards;

class Ace extends Card
{
    public function __construct(string $suit)
    {
        $data["suit"] = $suit;
        $data["face"] = "Ace";
        $data["value"] = 14;
        parent::__construct($data);
    }
}