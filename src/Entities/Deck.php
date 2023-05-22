<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Cards\Ace;
use CardGameApp\Entities\Cards\Jack;
use CardGameApp\Entities\Cards\Joker;
use CardGameApp\Entities\Cards\King;
use CardGameApp\Entities\Cards\Numeral;
use CardGameApp\Entities\Cards\Queen;

/**
 * MB: This class is good. I like how you encapsulated the cards in an object. Long term, you could put any
 * array sort, search, shuffle, features in here. So you could do $deck->shuffle().
 * One thing for you to look into, is that an object can be made iterable then it then behaves like an array.
 * You will see this when you learn our framework, PPCore. We have a Collection object which you can do things like :
 * $orders->sum('gross') , $orders->sortBy('customer')
 *
 * WORK TO DO --> CONVERT THIS TO A COLLECTION OF CARDS THAT CAN BE SHUFFLED ETC
 */
class Deck extends CardsCollection
{
    public function __construct()
    {
        $this->createDeck();
    }
    /**
     * Creates a 54 deck of cards. 2-10, Ace, Jack, King Queen of each suit and two Joker cards
     * @return void
     */
    public function createDeck() {
        $suits = ["Spades", "Clubs", "Hearts", "Diamonds"];
        $faces = ["2", "3", "4", "5", "6", "7", "8", "9", "10"];

        // Add numeral cards for each suit to the deck
        for ($f = 0; $f < count($faces); $f++) {
            for ($s = 0; $s < count($suits); $s++) {
                $this->cards[] = new Numeral(
                    [
                        "suit" => $suits[$s],
                        "face" => $faces[$f],
                        "value" => intval($faces[$f]),
                    ]);
            }
        }

        // Add ace and court cards for each suit to the deck
        for ($s = 0; $s < count($suits); $s++) {
            array_push(
                $this->cards,
                new Ace($suits[$s]),
                new Jack($suits[$s]),
                new Queen($suits[$s]),
                new King($suits[$s])
            );
        }

        // Add two jokers to the deck
        for ($i = 0; $i < 2; $i++) {
            $this->cards[] = new Joker();
        }

    }

    /**
     * Returns the cards in the deck
     * @return array
     */
    public function getCards(): array {
        return $this->cards;
    }
}
