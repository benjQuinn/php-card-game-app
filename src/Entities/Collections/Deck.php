<?php

namespace App\Entities\Collections;

use App\Entities\Cards\Ace;
use App\Entities\Cards\Jack;
use App\Entities\Cards\Joker;
use App\Entities\Cards\King;
use App\Entities\Cards\Numeral;
use App\Entities\Cards\Queen;
use Traversable;

class Deck extends CardsCollection implements \IteratorAggregate
{
    public function __construct(int $aceValue, bool $jokers = false, int $jokersNumber = 0, int $jokerValue = 0)
    {
        parent::__construct();
        $this->createDeck($aceValue, $jokers, $jokersNumber, $jokerValue);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->cards);
    }

    /**
     * Creates a deck of cards. 2-10, Ace, Jack, King Queen of each suit and Jokers cards if passed as params
     * @param int $aceValue
     * @param bool $jokers
     * @param int $jokersNumber
     * @param int $jokerValue
     * @return Deck
     */
    public function createDeck(int $aceValue, bool $jokers = false, int $jokersNumber = 0, int $jokerValue = 0): Deck
    {
        $suits = ["Spades", "Clubs", "Hearts", "Diamonds"];
        $faces = ["2", "3", "4", "5", "6", "7", "8", "9", "10"];

        // Add numeral cards for each suit to the deck
        for ($f = 0; $f < count($faces); $f++)
        {
            for ($s = 0; $s < count($suits); $s++)
            {
                $this->add(new Numeral(
                    [
                        "suit" => $suits[$s],
                        "face" => $faces[$f],
                        "value" => intval($faces[$f]),
                    ]));
            }
        }

        // Add ace and court cards for each suit to the deck
        for ($s = 0; $s < count($suits); $s++)
        {
            $this
                ->add(new Ace($suits[$s], $aceValue))
                ->add(new Jack($suits[$s]))
                ->add(new Queen($suits[$s]))
                ->add(new King($suits[$s]));
        }

        // Add two jokers to the deck
        if ($jokers)
        {
            for ($i = 0; $i < $jokersNumber; $i++)
            {
                $this->add(new Joker($jokerValue));
            }
        }

        return $this;
    }

}
