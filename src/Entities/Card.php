<?php

namespace CardGameApp\Entities;

class Card {
    private string $suit;
    private string $face;
    private int $value;
    private bool $isAce;
    private bool $isJoker;

    /**
     * MB: I see that you've got 2 variables called isAce and isJoker. I presume this is so that we can do conditional
     * behaviour elsewhere. In OO coding, we normally want to encapsulate type with inheritance. This means, that any behaviour
     * differences between King and Joker, can be encapsulated in their classes. This is subjective and really depends
     * on the scale of difference of behavior. So instead of $card = new Card(); You have $card = new Joker();
     * then later code can do :
    if($card instanceof Joker){
    .. do some joker code
    }
    if($card instanceof Ace){
    .. some custom Ace behaviour
    }
     */
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