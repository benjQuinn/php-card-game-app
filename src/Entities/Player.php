<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Cards\Card;
use CardGameApp\Entities\Collections\Hand;
use CardGameApp\Entities\Collections\Pile;

class Player {
    private int $playerNumber = 0;
    private string $name;
    public Hand $hand;
    public Pile $scorePile;
    private bool $isLeader = false;

    public function __construct(string $name) {
        $this->name = $name;
        $this->hand = new Hand();
        $this->scorePile = new Pile();
    }

    /**
     * changes isLeader to true if whoIsLeader() on Table object returns the instances player number and vice versa
     * @param GameController $gameController
     * @return void
     */
    private function updateLeader(GameController $gameController) {
        // changes isLeader to true if whoIsLeader() returns the instances player number and vice versa
        $this->isLeader = $gameController->whoIsLeader() === $this->playerNumber;
    }

    /**
     * Generates rock, paper or scissors string randomly every time it is called
     * @return string
     */
    public function generateRockPaperScissors(): string {
        $rps = ["rock", "paper", "scissors"];
        $randomIndex = rand(0, 2);

        return $rps[$randomIndex];
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPlayerNumber(): int {
        return $this->playerNumber;
    }

    /**
     * Plays a card. This function is played whether the player is the leader or not
     * @param GameController $gameController
     * @return Card
     */
    public function playCard(GameController $gameController): Card {

        $this->updateLeader($gameController);
        // sort cards in ascending order
        $this->hand->sort();

        if (!$this->isLeader) {
            // get the card the leader has just played
            $playedCards = $gameController->playedCards->getCards();
            $leadersPlayedCard = reset($playedCards);

            // filters hand to only show cards that match suit of the played card
            $filteredHand = $this->hand->filter($leadersPlayedCard->getSuit());

            if (empty($filteredHand)) {
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
        /* MB: $card is not always set. There is a path through your code where we get here and $card was never set
            which could throw a PHP warning. Ideally, $card should have a starting value/ default / fallback value.
            In fact, because your function return type is set to 'Card', then it will likely be a PHP error, not warning.
         */
        return $card;
    }

    /**
     * @param GameController $gameController
     * @param int $cards
     * @return void
     */
    public function drawCards(GameController $gameController, int $cards)
    {
        for ($i = 0; $i < $cards; $i++)
        {
            $this->hand->add($gameController->draw());
        }
    }

    public function changePlayerNumber(int $number)
    {
        $this->playerNumber = $number;
    }

    /**
     * Initialises the players properties - used when restarting the game
     * @return void
     */
    public function initialisePlayer() {
        $this->hand = new Hand();
        $this->scorePile = new Pile();
        $this->isLeader = false;
    }
}
