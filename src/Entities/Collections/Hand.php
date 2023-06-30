<?php

namespace CardGameApp\Entities\Collections;

use CardGameApp\Entities\Cards\Card;

class Hand extends CardsCollection
{
    public function returnLastCard(): Card
    {
        return array_pop($this->cards);
    }

    public function returnFirstCard(): Card
    {
        return array_shift($this->cards);
    }
}