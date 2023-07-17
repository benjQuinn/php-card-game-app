<?php

namespace App\Entities\Players;

use App\Entities\Collections\Hand;
use App\Entities\Games\CardGame;

abstract class CardGamePlayer implements Player
{
    protected int $playerNumber = 0;
    protected string $name;
    protected Hand $hand;

    public function __construct(string $name, Hand $hand)
    {
        $this->name = $name;
        $this->hand = $hand;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function drawCards(CardGame $game, int $cards): CardGamePlayer
    {
        for ($i = 0; $i < $cards; $i++)
        {
            $this->hand->add($game->draw());
        }

        return $this;
    }

    public function changePlayerNumber(int $number): CardGamePlayer
    {
        $this->playerNumber = $number;

        return $this;
    }
}