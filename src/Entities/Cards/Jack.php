<?php

namespace CardGameApp\Entities\Cards;

class Jack extends Card
{
    public function __construct(string $suit)
    {
        $cardData["suit"] = $suit;
        $cardData["face"] = "Jack";
        $cardData["value"] = 11;
        parent::__construct($cardData);
    }
}