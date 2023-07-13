<?php

namespace App\Entities\Collections;

use App\Entities\Cards\Card;

abstract class CardsCollection
{
    protected array $cards;

    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function remove(Card $card): void
    {
            $index = array_search($card, $this->cards);
            unset($this->cards[$index]);
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function sort(): void
    {
        usort($this->cards, function ($a, $b)
        {
            return $a->getValue() - $b->getValue();
        });
    }

    public function filter($suit): array
    {
        $filteredCards = [];

        foreach ($this->cards as $card)
        {
            if ($card->getSuit() === $suit)
            {
                $filteredCards[] = $card;
            }
        }
        return $filteredCards;
    }

    public function count(): int
    {
        return count($this->cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function isEmpty(): bool
    {
        return empty($this->cards);
    }

}