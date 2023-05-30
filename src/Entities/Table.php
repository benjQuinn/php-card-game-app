<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Collections\Deck;
use CardGameApp\Entities\Collections\Pile;

class Table {
    public array $players;
    public Deck $deck;
    public Pile $playedCards;
    public int $currentLeader = 0;
    private int $currentRound = 0;

    public function __construct(array $players) {
        // creates array of players indexed by their unique player number
        $this->players = [
            $players[0]->getPlayerNumber() => $players[0],
            $players[1]->getPlayerNumber() => $players[1]
        ];
        $this->deck = new Deck();
        $this->playedCards = new Pile();
    }

    /** Returns the top card from the playing deck
     * @return mixed|null no need to validate for empty deck as the game will end if this is the case
     */
    public function draw() {
        // Returns the top card of the deck
        // no need to validate for empty deck as the game will end if this is the case
        $cards = $this->deck->getCards();
        $card = array_pop($cards);
//
        if (!empty($this->deck->getCards()))
        {
            $this->deck->remove($card);
        }
        return $card;
    }

    /**
     * Returns the player number of the current leader when called
     * @return int
     */
    public function whoIsLeader(): int {
        return $this->currentLeader;
    }

    /**
     * Returns the player number of the player that is not the leader when called
     * @return int|void
     */
    public function whoIsNotLeader() {
        foreach ($this->players as $playerNumber => $player) {
            if ($playerNumber !== $this->currentLeader) {
                return $playerNumber;
            }
        }
    }

    /**
     * Returns the two played cards
     * @return array of cards
     */
    public function getPlayedCards(): array
    {
        return $this->playedCards->getCards();
    }

    /**
     * Decides who is the winner when both players play their card
     * @param Player $leader the player that is the current leader when called
     * @param Player $opponent the player that is not the current leader when called
     * @return int the player number of the round winner
     */
    public function play(Player $leader, Player $opponent): int {
        $this->currentRound++;
        // leaders Card gets added to playedCards array on playCard() call
        $leadersPlayedCard = $leader->playCard($this);
        $opponentsPlayedCard = $opponent->playCard($this);
        // adds opponents played card to the playedCards pile
        $this->playedCards->add($opponentsPlayedCard);

        // if leader has the highest value card they remain the leader
        if ($leadersPlayedCard->getValue() > $opponentsPlayedCard->getValue()) {
            $this->currentLeader = $leader->getPlayerNumber();
        }
        // if opponent has the highest value card they become the leader
        if ($leadersPlayedCard->getValue() < $opponentsPlayedCard->getValue()) {
            $this->currentLeader = $opponent->getPlayerNumber();
        }
        // if both cards played have the same value, they are removed from the game and both players play another card
        if ($leadersPlayedCard->getValue() === $opponentsPlayedCard->getValue()) {
            $this->playedCards->remove($leadersPlayedCard);
            $this->playedCards->remove($opponentsPlayedCard);
            return $this->play($leader, $opponent);
        }
        // returns the winner of the round
        return $this->currentLeader;
    }

    /**
     * Initialises the tables properties - used when restarting the game
     * @return void
     */
    public function initialiseTable() {
        $this->cards = [];
        $this->playedCards = new Pile();
        $this->currentLeader = 0;
        $this->currentRound = 0;
    }

    /**
     * Returns the current round when called
     * @return int
     */
    public function getCurrentRound(): int {
        return $this->currentRound;
    }

    /**
     * returns true if either player has an empty deck
     * @return bool
     */
    public function doPlayersHaveCards(): bool {
        foreach ($this->players as $player) {
            if ($player->hand->count() === 0) {
                return false;
            }
        }
        return true;
    }
}