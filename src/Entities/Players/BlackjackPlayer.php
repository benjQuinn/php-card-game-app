<?php

namespace App\Entities\Players;

use App\Entities\Games\CardGame;

class BlackjackPlayer extends CardGamePlayer
{
    private int $handValue = 0;

    public function getHandValue(): int
    {
        return $this->getHandValue();
    }

    public function drawCards(CardGame $game, int $cards): void
    {
        parent::drawCards($game, $cards);
        foreach ($this->hand->getCards() as $card)
        {
            $this->handValue += $card->getValue();
        }
    }

    public function hit() {}

    public function stand() {}
}