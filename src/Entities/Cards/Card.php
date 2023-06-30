<?php

namespace App\Entities\Cards;

abstract class Card
{
    private string $suit;
    private string $face;
    private string $value;

    public function __construct(array $cardData)
    {
        $this->suit = $cardData["suit"];
        $this->face = $cardData["face"];
        $this->value = $cardData["value"];
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getFace(): string
    {
        return $this->face;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}