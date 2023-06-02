<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Collections\Deck;
use CardGameApp\Entities\Collections\Pile;
use CardGameApp\Entities\Pregames\PregameInterface;

class GameController
{
    protected PregameInterface $pregame;
    public array $players;
    public Deck $deck;
    public Pile $playedCards;
    public int $currentLeader = 0;
    private int $currentRound = 0;

    public function __construct(array $players, PregameInterface $pregame) {
        $this->pregame = $pregame;
        $this->players = $players;
        $this->deck = new Deck();
        $this->playedCards = new Pile();
    }

    public function setUp(): int {
        $winner = $this->pregame->decideLeader(...$this->players);

        $this->players = [
            $this->players[0]->getPlayerNumber() => $this->players[0],
            $this->players[1]->getPlayerNumber() => $this->players[1]
        ];

        $this->currentLeader = $winner;

        return $this->currentLeader;
    }

    public function draw() {
        // Returns the top card of the deck
        $cards = $this->deck->getCards();
        $card = array_pop($cards);

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
     * Returns the two played cards
     * @return array of cards
     */
    public function getPlayedCards(): array
    {
        return $this->playedCards->getCards();
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

    /**
     * Works out the winner based on each player's score pile
     * @return string
     */
    private function decideWinner(): string {
        $scores = [];
        $printer = new Printer();

        // put each players score into an array
        foreach ($this->players as $player) {
            $scores[] = $player->scorePile->count();
        }
        // find the highest score in scores array
        $winningScore = max($scores);
        $resultStr = "";

        if ($scores[0] === $scores[1]) {
            $resultStr .= $printer->printDraw();
        } else {
            // find which player had the winning score
            foreach ($this->players as $player) {
                if ($winningScore === $player->scorePile->count()) {
                    $resultStr .= $printer->printGameWinner($player->getPlayerNumber(), $player->getName());
                }
            }
        }
        return $resultStr;
    }

    /**
     * Automates a game. The function sets up the table and plays through each round of the game, displaying the winner of each, before displaying the winner based on each player's score pile after the final round
     * @return string
     */
    public function runGame(): string {
        $printer = new Printer();

        foreach ($this->players as $player)
        {
            $player->drawCards($this, 13);
        }

        while ($this->doPlayersHaveCards() && $this->getCurrentRound() <= 27) {
            $leader = $this->players[$this->whoIsLeader()];
            $opponent = $this->players[$this->whoIsNotLeader()];

            $roundWinner = $this->play($leader, $opponent);

            // displays the winner of each round
            $this->playedCards->resetCardsArrayIndex();
            $playedCards = $this->getPlayedCards();

            foreach ($playedCards as $index => $card) {
                if ($index === 0) {
                    echo $printer->printPlayedCard($leader->getPlayerNumber(), $card->getFace(), $card->getSuit());
                } else {
                    echo $printer->printPlayedCard($opponent->getPlayerNumber(), $card->getFace(), $card->getSuit());
                }
            }
            echo $printer->printRoundWinner($this->getCurrentRound(), $roundWinner);

            // round winner picks up the two played cards and adds them to their score pile
            foreach ($this->getPlayedCards() as $card) {
                // add played card to winner's score pile
                $this->players[$roundWinner]->scorePile->add($card);
                // and remove it from the table's played cards pile
                $this->playedCards->remove($card);
            }

            // after the winner picks up the played cards from this round, each player gets a new card from the deck IF the deck is not empty
            if (!empty($this->deck->getCards())) {
                foreach ($this->players as $player)
                {
                    $player->drawCards($this, 1);
                }
            }
        }

        foreach ($this->players as $player) {
            echo $printer->printScore($player->getPlayerNumber(), $player->scorePile->count());
        }
        return $this->decideWinner();
    }

    /**
     * Initialises the tables properties - used when restarting the game
     * @return void
     */
    public function initialiseGame() {
        $this->deck = new Deck();
        $this->playedCards = new Pile();
        $this->currentLeader = 0;
        $this->currentRound = 0;
    }

    /** Initialises the player and table properties so the game can be restarted
     * @return void
     */
    public function restartGame() {
        // initialise table properties
        $this->initialiseGame();
        // initialise both player properties
        foreach ($this->players as $player) {
            $player->initialisePlayer();
        }
    }
}
