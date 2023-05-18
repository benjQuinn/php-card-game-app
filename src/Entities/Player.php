<?php

namespace CardGameApp\Entities;

class Player {
    private static int $counter = 1;

    private int $playerNumber;
    private string $name;
    private array $hand = [];
    private array $scorePile = [];
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
    }

    /**
     * Sorts cards in the players hand in ascending order
     * @return void
     */
    private function sortHand() {
        usort($this->hand, function ($a, $b) {
            return $a->getValue() - $b->getValue();
        });
    }

    /**
     * Filters players cards to match a given suit
     * @param string $suit e.g "Clubs"
     * @return array array of the filtered cards
     */
    private function filterCards(string $suit): array {
        // filter cards to match the given suit
        $filteredCards = [];

        foreach ($this->hand as $card) {
            if ($card->getSuit() === $suit) {
                $filteredCards[] = $card;
            }
        }
        return $filteredCards;
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

    public function getHandCount(): int {
        return count($this->hand);
    }

    public function getScorePileCount(): int {
        return count($this->scorePile);
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPlayerNumber(): int {
        return $this->playerNumber;
    }

    public function getIsLeader(): bool {
        return $this->isLeader;
    }

    /**
     * Receives a given card and adds to its hand
     * @param Card $card
     * @return void
     */
    public function receiveCardToHand(Card $card) {
        // takes card from the top of deck, provided by the Table object, and adds it to players hand 13 times
        $this->hand[] = $card;
    }

    /**
     * Receives a given card and adds it to its score pile
     * @param Card $card
     * @return void
     */
    public function receiveCardToScorePile(Card $card) {
        $this->scorePile[] = $card;
    }

    /**
     * Plays a card. This function is played whether the player is the leader or not
     * @param Table $table
     * @return Card
     */
    public function playCard(Table $table): Card {

        $this->updateLeader($table);
        // sort cards in ascending order
        $this->sortHand();

        if (!$this->isLeader) {
            // get the card the leader has just played
            $opponentsPlayedCardSuit = $table->getLeadersPlayedCardSuit();
            // filters hand to only show cards that match suit of the played card
            $filteredHand = $this->filterCards($opponentsPlayedCardSuit);

            if (empty($filteredHand)) {
                // if player has no card matching suit, they strategically play their lowest value card from their hand
                $card = array_shift($this->hand);
            } else {
                // else they play their highest value matching card
                $card = array_pop($filteredHand);
                // the played card is removed from the hand
                $index = array_search($card, $this->hand);
                unset($this->hand[$index]);
            }

        } else {
            // if player is the leader they play their highest value card
            $card = array_pop($this->hand);
            $table->receivePlayedCard($card);
        }
        return $card;
    }

    /**
     * Initialises the players properties - used when restarting the game
     * @return void
     */
    public function initialisePlayer() {
        $this->hand = [];
        $this->scorePile = [];
        $this->isLeader = false;
    }
}
