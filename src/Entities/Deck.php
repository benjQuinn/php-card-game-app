<?php

namespace CardGameApp\Entities;
/**
 * MB: This class is good. I like how you encapsulated the cards in an object. Long term, you could put any
 * array sort, search, shuffle, features in here. So you could do $deck->shuffle().
 * One thing for you to look into, is that an object can be made iterable then it then behaves like an array.
 * You will see this when you learn our framework, PPCore. We have a Collection object which you can do things like :
 * $orders->sum('gross') , $orders->sortBy('customer')
 */
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
                /**
                 * MB: you don't need these IFs. There is another way to solve this, that leverages PHPs string keys.
                 * your above array could have been: ['Ace'=>14,'King'=>13...'Ten'=>10,'Nine'=>9]
                 * you don't need string Nine, Ten, but it could be useful for rendering and creates the concept that
                 * every card has an int value and a string value. It could ['10'=>10]
                 */
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
                /**
                 * MB: i'm guessing this code is old code to be removed? because you're doing this further down
                 */
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

        /**
         * MB: you don't want to 're-use' $joker here, because objects are stored as references, which is opposite to how
         * literals are stored like string, bool, integer, float. So when you pass $joker into array_push, you didn't add
         * 2 unique copies of the joker to the array. You added 2 pointers to the same object. If you see Example:A,
         * there is some runnable code to show how references work. Now realistically, in this game, cards will only
         * ever be read-only, but its important you know how this would lead to weird bugs in a production application
         */
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
