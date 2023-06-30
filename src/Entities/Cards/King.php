<?php

namespace CardGameApp\Entities\Cards;

class King extends Card
{
    public function __construct(string $suit)
    {
        $cardData["suit"] = $suit;
        $cardData["face"] = "King";
        $cardData["value"] = 13;
        parent::__construct($cardData);
    }
}