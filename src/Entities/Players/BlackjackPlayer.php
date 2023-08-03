<?php

namespace App\Entities\Players;

use App\Entities\Collections\Hand;
use App\Entities\Games\CardGame;

class BlackjackPlayer extends CardGamePlayer
{
    protected int $handValue = 0;
    protected bool $isBlackjack = false;
    protected bool $isBust = false;
    protected bool $isStood = false;
    protected int $roundsWon = 0;
    public Hand $hand;

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

    // Note to self: Should move this into game controller to decouple
    public function drawCards(CardGame $game, int $cards): BlackjackPlayer
    {
        parent::drawCards($game, $cards);
        $this->calculateHandValue()->check();

        return $this;
    }

    // Note to self: Should move this into game controller to decouple
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

    public function getRoundsWon(): int
    {
        return $this->roundsWon;
    }

    public function wonRound(): BlackjackPlayer
    {
        $this->roundsWon++;

        return $this;
    }

    public function getHand(): array
    {
        return $this->hand->getCards();
    }

    public function initialisePlayer(): BlackjackPlayer
    {
        $this->handValue = 0;
        $this->isBlackjack = false;
        $this->isBust = false;
        $this->isStood = false;

        return $this;
    }
}