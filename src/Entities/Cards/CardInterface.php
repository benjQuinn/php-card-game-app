<?php

namespace CardGameApp\Entities\Cards;

interface CardInterface
{
    public function getSuit();

    public function getFace();

    public function getValue();
}