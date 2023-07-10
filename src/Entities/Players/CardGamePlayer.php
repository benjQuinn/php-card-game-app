<?php

namespace App\Entities\Players;

use App\Entities\Collections\Hand;
use App\Entities\Games\PPCardGame;

abstract class CardGamePlayer implements Player
{
    protected int $playerNumber = 0;
    protected string $name;
    protected Hand $hand;
    protected bool $isLeader = false;

    public function updateIsLeader(PPCardGame $game): void
    {
        $this->isLeader = $game->whoIsLeader() === $this->playerNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function drawCards(PPCardGame $game, int $cards): void
    {
        for ($i = 0; $i < $cards; $i++)
        {
            $this->hand->add($game->draw());
        }
    }

    public function changePlayerNumber(int $number): void
    {
        $this->playerNumber = $number;
    }

    public function initialisePlayer(): void
    {
        $this->hand = new Hand();
        $this->isLeader = false;
    }
}