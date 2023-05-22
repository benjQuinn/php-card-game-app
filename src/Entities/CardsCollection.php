<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Cards\CardInterface;
use Traversable;

abstract class CardsCollection implements \IteratorAggregate
{
    protected array $cards;

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->cards);
    }

    public function add(CardInterface $card)
    {
        $this->cards[] = $card;
    }

    public function remove(CardInterface $card)
    {
        $index = array_search($card, $this->cards);
        unset($this->cards[$index]);
    }

    public function shuffle()
    {
        shuffle($this->cards);
    }

    public function sort()
    {
        usort($this->cards, function ($a, $b) {
            return $a->getValue() - $b->getValue();
        });
    }

    public function filter($suit): array
    {
        $filteredCards = [];

        foreach ($this->cards as $card) {
            if ($card->getSuit() === $suit) {
                $filteredCards[] = $card;
            }
        }
        return $filteredCards;
    }

    public function count(): int {
        return count($this->cards);
    }
}