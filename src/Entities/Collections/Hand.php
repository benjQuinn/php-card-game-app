<?php

namespace CardGameApp\Entities\Collections;

class Hand extends CardsCollection
{
    // one issue is that Hand inherits getCards() function - player's hand shouldn't be public
    // need to fix this
}