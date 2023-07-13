<?php

namespace App\Entities\Collections;

use App\Entities\Cards\Card;

class Hand extends CardsCollection
{
    public function returnLastCard(): Card|null
    {
        if (!empty($this->cards))
        {
            return end($this->cards);
        } else {
            return null;
        }
    }

    public function returnFirstCard(): Card|null
    {
        if (!empty($this->cards))
        {
            return current($this->cards);
        } else {
            return null;
        }
    }
}