<?php

namespace App\Entities\Collections;

use App\Entities\Cards\Card;

abstract class CardsCollection
{
    protected array $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    public function add(Card $card, string|int $index = null): void
    {
        if ($index === null)
        {
            $this->cards[] = $card;
        } else {
            $this->cards[$index] = $card;
        }
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

    public function filter($suit): Hand
    {
        $filteredCards = new Hand();

        foreach ($this->cards as $card)
        {
            if ($card->getSuit() === $suit)
            {
                $filteredCards->add($card);
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