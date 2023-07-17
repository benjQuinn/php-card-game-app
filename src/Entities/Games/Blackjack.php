<?php

namespace App\Entities\Games;

use App\Entities\Collections\Deck;
use App\Entities\Collections\Pile;
use App\Entities\Players\BlackjackPlayer;
use App\Entities\Players\Player;
use App\Entities\Printers\Printer;

class Blackjack extends CardGame
{
    public function setUp(): Blackjack
    {
        if (!$this->playedCards->isEmpty())
        {
            $this->playedCards->removeAllCards();
        }
        // Remove all cards from Deck and instantiate
        $this->deck->removeAllCards()->createDeck(11)->shuffle();
        parent::setUp();

        return $this;
    }

    public function playRound(array $players): Blackjack
    {
        // Pregame is played before every round to determine leader (leader is able to play first each round)
        parent::setUp();

        foreach ($players as $player)
        {
            $player->drawCards($this, 2);
        }

        $this->currentRound++;
        return $this;
    }

    public function decideRoundWinner(BlackjackPlayer $leader, BlackjackPlayer $opponent): int
    {
        ////
        return 0;
    }
}