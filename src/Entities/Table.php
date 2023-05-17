<?php

namespace CardGameApp\Entities;

class Table {
    private array $players;
    private Deck $deck;
    private array $cards;
    private array $playedCards = [];
    private int $currentLeader = 0;
    private int $currentRound = 0;

    public function __construct(array $players) {
        // creates array of players indexed by their unique player number
        $this->players = [
            $players[0]->getPlayerNumber() => $players[0],
            $players[1]->getPlayerNumber() => $players[1]
        ];
        $this->deck = new Deck();
        $this->cards = $this->deck->getCards();
    }

    /**
     * Shuffles the cards in the playing deck
     * @return void
     */
    private function shuffleCards() {
        shuffle($this->cards);
    }

    /**
     * Decides the winner of rock-paper-scissors. Order of params has no effect on the outcome
     * @param Player $firstPlayer
     * @param Player $secondPlayer
     * @return int the player number of the winning player
     */
    private function decideRockPaperScissorsWinner(Player $firstPlayer, Player $secondPlayer): int {

        $firstPlayerRPS = $firstPlayer->generateRockPaperScissors();
        $secondPlayerRPS = $secondPlayer->generateRockPaperScissors();

        // if first player wins they become the leader
        if ($firstPlayerRPS === "rock" && $secondPlayerRPS === "scissors" ||
            $firstPlayerRPS === "paper" && $secondPlayerRPS === "rock" ||
            $firstPlayerRPS === "scissors" && $secondPlayerRPS === "paper") {

            // each player instance has its own number rather than 1 and 2
            $this->currentLeader = $firstPlayer->getPlayerNumber();
        }
        // if second player wins they become the leader
        if ($firstPlayerRPS === "rock" && $secondPlayerRPS === "paper" ||
            $firstPlayerRPS === "paper" && $secondPlayerRPS === "scissors" ||
            $firstPlayerRPS === "scissors" && $secondPlayerRPS === "rock") {

            $this->currentLeader = $secondPlayer->getPlayerNumber();
        }
        // draw - recursively execute function until one player wins
        if ($firstPlayerRPS === "rock" && $secondPlayerRPS === "rock" ||
            $firstPlayerRPS === "paper" && $secondPlayerRPS === "paper" ||
            $firstPlayerRPS === "scissors" && $secondPlayerRPS === "scissors") {

            return $this->decideRockPaperScissorsWinner($firstPlayer, $secondPlayer);
        }
        return $this->currentLeader;
    }

    public function getCards(): array {
        return $this->cards;
    }

    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * Resets the table (deck is filled back to 54 and shuffled). It also determines who wins rock-paper-scissors and starts the game as leader
     * @return int the player number of the leader
     */
    public function setUp(): int {
        $this->cards = $this->deck->getCards();
        $this->shuffleCards();

        $winner = $this->decideRockPaperScissorsWinner(...$this->players);
        $this->currentLeader = $winner;
        echo "Player $winner wins rock-paper-scissors and starts the game as leader.";
        return $this->currentLeader;
    }

    /** Returns the top card from the playing deck
     * @return mixed|null no need to validate for empty deck as the game will end if this is the case
     */
    private function draw() {
        // Returns the top card of the deck
        // no need to validate for empty deck as the game will end if this is the case
        return array_pop($this->cards);
    }

    /**
     * Gives each player a given number of cards to their hand
     * @param int $cards number of cards each player receives - defaulted at 13
     * @return void
     */
    public function drawCards(int $cards = 13) {
        foreach ($this->players as $player) {
            for ($i = 0; $i < $cards; $i++) {
                $player->receiveCardToHand($this->draw());
            }
        }
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
     * Receives a card and adds it to its played cards array
     * @param Card $card gets the leaders card from the players playCard method and the opponents card from the tables play method
     * @return void
     */
    public function receivePlayedCard(Card $card) {
        $this->playedCards[] = $card;
    }

    /**
     * Returns the string of the leaders card which is the first played card. Player uses this to filter their cards
     * @return string the suit of the leaders card
     */
    public function getLeadersPlayedCardSuit(): string {
        // get leaders card which is the first played card. Player uses this for the filterCards() logic
        return array_slice($this->playedCards, 0, 1)[0]->getSuit();
    }

    /**
     * Returns the two played cards
     * @return array of cards
     */
    public function pickUp(): array {
        return $this->playedCards;
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
        $this->playedCards[] = $opponentsPlayedCard;

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
            $this->playedCards = [];
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
        $this->playedCards = [];
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
     * returns false if either player has an empty deck
     * @return bool
     */
    public function doPlayersHaveCards(): bool {
        foreach ($this->players as $player) {
            if ($player->getHandCount() === 0) {
                return false;
            }
        }
        return true;
    }
}