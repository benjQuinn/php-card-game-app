<?php

namespace App\Entities\Games;

use App\Entities\Collections\Deck;
use App\Entities\Collections\Pile;
use App\Entities\Players\Player;
use App\Entities\Printers\Printer;

class Blackjack extends CardGame
{
    public function setUp(): Blackjack
    {
        // Remove cards from playedCards pile
        $this->playedCards->removeAllCards();
        // Remove all cards from Deck and instantiate
        $this->deck->removeAllCards()->createDeck()->shuffle();

        return $this;
    }
}