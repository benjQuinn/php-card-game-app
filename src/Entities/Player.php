<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Cards\Card;
use CardGameApp\Entities\Collections\Hand;
use CardGameApp\Entities\Collections\Pile;

class Player {
    private static int $counter = 1;

    private int $playerNumber;
    private string $name;
    public Hand $hand;
    public Pile $scorePile;
    private bool $isLeader = false;

    public function __construct(string $name) {
        /**
         * MB: You've correctly understood how static
         * you shouldn't use static within an instance object, to store global state. This could be dangerous.
         * As a principle, you don't really want to be coupled to a global state that can be mutated.
         * This class is being coupled to other players. If Player needs to know its number, then you should pass it in.
         * If we wanted the player object to have agency in deciding what player number it is, (for example; rock-paper-scissors)
         * then we could give each player object, a reference to the other and do something like :
         * $p1->addOpponent($p2);
         * */

        // first player that's instantiated will be playerNumber = 1 and this will increment every time a new player is instantiated
        $this->playerNumber = self::$counter++;
        $this->name = $name;
        $this->hand = new Hand();
        $this->scorePile = new Pile();
    }

    /**
     * changes isLeader to true if whoIsLeader() on Table object returns the instances player number and vice versa
     * @param Table $table
     * @return void
     */
    private function updateLeader(Table $table) {
        // changes isLeader to true if whoIsLeader() returns the instances player number and vice versa
        $this->isLeader = $table->whoIsLeader() === $this->playerNumber;
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
     * @param Table $table
     * @return Card
     */
    public function playCard(Table $table): Card {

        $this->updateLeader($table);
        // sort cards in ascending order
        $this->hand->sort();

        if (!$this->isLeader) {
            // get the card the leader has just played
            $playedCards = $table->playedCards->getCards();
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
                $table->playedCards->add($card);
            }
        }

        return $card;
    }

    /**
     * @param Table $table
     * @param int $cards
     * @return void
     */
    public function drawCards(Table $table, int $cards)
    {
        for ($i = 0; $i < $cards; $i++)
        {
            $this->hand->add($table->draw());
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
