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

    public function add(Card $card, string|int $index = null): CardsCollection
    {
        if ($index === null) {
            $this->cards[] = $card;
        } else {
            $this->cards[$index] = $card;
        }

        return $this;
    }

    public function remove(Card $card): CardsCollection
    {
        $index = array_search($card, $this->cards);
        unset($this->cards[$index]);

        return $this;
    }

    public function shuffle(): CardsCollection
    {
        shuffle($this->cards);

        return $this;
    }

    public function sort(): CardsCollection
    {
        usort($this->cards, function ($a, $b) {
            return $a->getValue() - $b->getValue();
        });

        return $this;
    }

    public function filter(string $suit): array
    {
        $filteredCards = [];

        foreach ($this->cards as $card) {
            if ($card->getSuit() === $suit) {
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

    public function removeAllCards(): CardsCollection
    {
        if (!empty($this->cards))
        {
            foreach ($this->cards as $card)
            {
                $this->remove($card);
            }
        }

        return $this;
    }

}