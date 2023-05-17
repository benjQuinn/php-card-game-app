<?php

namespace CardGameApp\Entities;

class Deck {
    private array $cards = [];

    public function __construct() {
        $this->createDeck();
    }

    /**
     * Creates a 54 deck of cards. 2-10, Ace, Jack, King Queen of each suit and two Joker cards and adds them to $cards property
     * @return void
     */
    public function createDeck() {
        $suits = ["Spades", "Clubs", "Hearts", "Diamonds"];
        $faces = ["Ace", "2", "3", "4", "5", "6", "7", "8", "9", "10", "Jack", "King", "Queen"];

        for ($f = 0; $f < count($faces); $f++) {
            for ($s = 0; $s < count($suits); $s++) {

                $value = intval($faces[$f]);
                $isAce = false;
                $isJoker = false;

                if ($faces[$f] === "Jack") {
                    $value = 11;
                }
                if ($faces[$f] === "Queen") {
                    $value = 12;
                }
                if ($faces[$f] === "King") {
                    $value = 13;
                }
                if ($faces[$f] === "Ace") {
                    $value = 14;
                    $isAce = true;
                }
                if ($faces[$f] === "Joker") {
                    $value = 11;
                    $isJoker = true;
                }

                $this->cards[] = new Card(
                    [
                        "suit" => $suits[$s],
                        "face" => $faces[$f],
                        "value" => $value,
                        "isAce" => $isAce,
                        "isJoker" => $isJoker,
                ]);
            }
        }

        // Adds two jokers to the deck
        $joker = new Card(
            [
                "suit" => "N/A",
                "face" => "Joker",
                "value" => 11,
                "isAce" => false,
                "isJoker" => true
            ]);

        array_push($this->cards, $joker, $joker);
    }

    /**
     * Returns the cards in the deck
     * @return array
     */
    public function getCards(): array {
        return $this->cards;
    }
}
