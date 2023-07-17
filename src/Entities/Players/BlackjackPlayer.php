<?php

namespace App\Entities\Players;

use App\Entities\Games\Blackjack;
use App\Entities\Games\CardGame;

class BlackjackPlayer extends CardGamePlayer
{
    private int $handValue = 0;
    private bool $isBlackjack = false;
    private bool $isBust = false;
    private bool $isStood = false;

    private function calculateHandValue(): BlackjackPlayer
    {
        // Reset the hand value and calculate the value of all the cards in the hand. Needs to be reset so the function can be used when there is already cards in the deck
        $counter = 0;
        foreach ($this->hand->getCards() as $card)
        {
            $counter += $card->getValue();
        }
        $this->handValue = $counter;

        return $this;
    }

    public function check(): BlackjackPlayer
    {
        if ($this->handValue > 21)
        {
            $this->isBust = true;
        }
        if ($this->handValue === 21)
        {
            $this->isBlackjack = true;
        }

        return $this;
    }

    public function getHandValue(): int
    {
        return $this->handValue;
    }

    public function drawCards(CardGame $game, int $cards): void
    {
        parent::drawCards($game, $cards);
        $this->calculateHandValue()->check();
    }

    public function hit(CardGame $game): BlackjackPlayer
    {
        parent::drawCards($game, 1);
        $this->calculateHandValue()->check();

        return $this;
    }

    public function stand(): BlackjackPlayer
    {
        $this->isStood = true;
        $this->calculateHandValue()->check();

        return $this;
    }

    public function isStood(): bool
    {
        return $this->isStood;
    }

    public function isBust(): bool
    {
        return $this->isBust;
    }

    public function isBlackjack(): bool
    {
        return $this->isBlackjack;
    }
}