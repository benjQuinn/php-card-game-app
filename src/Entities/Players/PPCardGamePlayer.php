<?php

namespace App\Entities\Players;

use App\Entities\Cards\Card;
use App\Entities\Collections\Hand;
use App\Entities\Collections\Pile;
use App\Entities\Games\PPCardGame;

class PPCardGamePlayer extends CardGamePlayer
{
    public Pile $scorePile;
    protected bool $isLeader = false;
    protected int $playerNumber = 0;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new Hand();
        $this->scorePile = new Pile();
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function changePlayerNumber(int $number): void
    {
        $this->playerNumber = $number;
    }

    public function updateIsLeader(PPCardGame $game): void
    {
        $this->isLeader = $game->whoIsLeader() === $this->playerNumber;
    }

    /**
     * Plays a card. This function is played whether the player is the leader or not
     * @param PPCardGame $gameController
     * @return Card|false
     */
    public function playCard(PPCardGame $gameController): Card|null
    {
        $card = null;

        $this->updateIsLeader($gameController);
        // sort cards in ascending order
        $this->hand->sort();

        if (!$this->isLeader)
        {
            // get the card the leader has just played
            $playedCards = $gameController->playedCards->getCards();
            $leadersPlayedCard = current($playedCards);

            // filters hand to only show cards that match suit of the played card
            if ($leadersPlayedCard instanceof Card)
            {
                $filteredHand = $this->hand->filter($leadersPlayedCard->getSuit());
            }

            if (empty($filteredHand))
            {
                // if player has no card matching suit, they strategically play their lowest value card from their hand
                $card = $this->hand->returnFirstCard();
            } else {
                // else they play their highest value matching card
                $card = end($filteredHand);
            }

            if ($card)
            {
                $this->hand->remove($card);
            }

        } else {
            // if player is the leader they play their highest value card
            if (!$this->hand->isEmpty())
            {
                $card = $this->hand->returnLastCard();
                $this->hand->remove($card);
                $gameController->playedCards->add($card);
            }
        }
        return $card;
    }
}
