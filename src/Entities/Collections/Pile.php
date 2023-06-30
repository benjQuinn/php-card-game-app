<?php

namespace CardGameApp\Entities\Collections;

use Traversable;

class Pile extends CardsCollection implements \IteratorAggregate
{
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->cards);
    }
}