<?php

namespace App\Entities\Players;

use App\Entities\Cards\Card;
use App\Entities\Collections\Hand;
use App\Entities\Collections\Pile;
use App\Entities\Games\PPCardGame;

class PPCardGamePlayer implements Player
{
    private int $playerNumber = 0;
    private string $name;
    public Hand $hand;
    public Pile $scorePile;
    private bool $isLeader = false;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new Hand();
        $this->scorePile = new Pile();
    }

    /**
     * changes isLeader to true if whoIsLeader() on Table object returns the instances player number and vice versa
     * @param PPCardGame $gameController
     * @return void
     */
    private function updateLeader(PPCardGame $gameController): void
    {
        // changes isLeader to true if whoIsLeader() returns the instances player number and vice versa
        $this->isLeader = $gameController->whoIsLeader() === $this->playerNumber;
    }

    /**
     * Generates rock, paper or scissors string randomly every time it is called
     * @return string
     */
    public function generateRockPaperScissors(): string
    {
        $rps = ["rock", "paper", "scissors"];
        $randomIndex = rand(0, 2);

        return $rps[$randomIndex];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    /**
     * Plays a card. This function is played whether the player is the leader or not
     * @param PPCardGame $gameController
     * @return Card|false
     */
    public function playCard(PPCardGame $gameController): Card|false
    {

        $card = false;

        $this->updateLeader($gameController);
        // sort cards in ascending order
        $this->hand->sort();

        if (!$this->isLeader)
        {
            // get the card the leader has just played
            $playedCards = $gameController->playedCards->getCards();
            $leadersPlayedCard = reset($playedCards);

            // filters hand to only show cards that match suit of the played card
            $filteredHand = $this->hand->filter($leadersPlayedCard->getSuit());

            if (empty($filteredHand))
            {
                // if player has no card matching suit, they strategically play their lowest value card from their hand
                $card = $this->hand->returnFirstCard();
            } else {
                // else they play their highest value matching card
                $card = array_pop($filteredHand);
            }

        } else {
            // if player is the leader they play their highest value card
            if (!$this->hand->isEmpty())
            {
                $card = $this->hand->returnLastCard();
                $gameController->playedCards->add($card);
            }
        }
        return $card;
    }

    /**
     * @param PPCardGame $gameController
     * @param int $cards
     * @return void
     */
    public function drawCards(PPCardGame $gameController, int $cards): void
    {
        for ($i = 0; $i < $cards; $i++)
        {
            $this->hand->add($gameController->draw());
        }
    }

    public function changePlayerNumber(int $number): void
    {
        $this->playerNumber = $number;
    }

    /**
     * Initialises the players properties - used when restarting the game
     * @return void
     */
    public function initialisePlayer()
    {
        $this->hand = new Hand();
        $this->scorePile = new Pile();
        $this->isLeader = false;
    }
}
