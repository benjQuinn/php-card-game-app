<?php
namespace App\Entities\Cards;

class Queen extends Card
{
    public function __construct(string $suit)
    {
        $cardData["suit"] = $suit;
        $cardData["face"] = "Queen";
        $cardData["value"] = 12;
        parent::__construct($cardData);
    }
}