<?php

namespace App\Entities\Players;

use App\Entities\Cards\Card;
use App\Entities\Collections\CardsCollection;
use App\Entities\Collections\Hand;
use App\Entities\Collections\Pile;
use App\Entities\Games\PPCardGame;

class PPCardGamePlayer extends CardGamePlayer
{
    public Pile $scorePile;
    protected bool $isLeader = false;
    protected int $playerNumber = 0;
    public CardsCollection $filteredHand;

    public function __construct(string $name, Hand $hand, Pile $pile)
    {
        parent::__construct($name, $hand);
        $this->scorePile = $pile;
    }

    private function setFilteredHand(CardsCollection $collection): PPCardGamePlayer
    {
        $this->filteredHand = $collection;
        return $this;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function changePlayerNumber(int $number): PPCardGamePlayer
    {
        $this->playerNumber = $number;
        return $this;
    }

    public function updateIsLeader(PPCardGame $game): PPCardGamePlayer
    {
        $this->isLeader = $game->whoIsLeader() === $this->playerNumber;
        return $this;
    }

    public function playCard(Card $card): Card
    {
        $this->hand->remove($card);
        return $card;
    }

    public function hasCards(): bool
    {
        return !$this->hand->isEmpty();
    }

    public function returnHighestValueCard(): Card
    {
        $this->hand->sort();
        return $this->hand->returnLastCard();
    }

    public function returnLowestValueCard(): Card
    {
        $this->hand->sort();
        return $this->hand->returnFirstCard();
    }

    public function getFilteredCards(string $suit, CardsCollection $collection): CardsCollection
    {
        $cards = $this->setFilteredHand($collection)->filteredHand->filter($suit);

        foreach ($cards as $card)
        {
            $this->filteredHand->add($card);
        }

        return $this->filteredHand;
    }
}
