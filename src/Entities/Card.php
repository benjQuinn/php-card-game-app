<?php

namespace CardGameApp\Entities;

class Card {
    private string $suit;
    private string $face;
    private int $value;
    private bool $isAce;
    private bool $isJoker;

    public function __construct(array $cardData) {
        $this->suit = $cardData["suit"];
        $this->face = $cardData["face"];
        $this->value = $cardData["value"];
        $this->isAce = $cardData["isAce"];
        $this->isJoker = $cardData["isJoker"];
    }

    public function getSuit(): string {
        return $this->suit;
    }

    public function getValue(): int {
        return $this->value;
    }

    public function getFace(): string {
        return $this->face;
    }

    public function isAce(): string {
        return $this->isAce;
    }

    public function isJoker(): string {
        return $this->isJoker;
    }

    public function changeCardValue(string $key, $value) {
        $this->$key = $value;
    }
}