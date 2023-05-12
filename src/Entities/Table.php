<?php

namespace CardGameApp\Entities;

class Table
{
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

    private function shuffleCards() {
        shuffle($this->cards);
    }

    private function decideRockPaperScissorsWinner(Player $firstPlayer, Player $secondPlayer): int {

        $firstPlayerRPS = $firstPlayer->generateRockPaperScissors();
        $secondPlayerRPS = $secondPlayer->generateRockPaperScissors();

        // if first wins
        if ($firstPlayerRPS === "rock" && $secondPlayerRPS === "scissors" ||
            $firstPlayerRPS === "paper" && $secondPlayerRPS === "rock" ||
            $firstPlayerRPS === "scissors" && $secondPlayerRPS === "paper") {

            // each player instance has its own number rather than 1 and 2
            $this->currentLeader = $firstPlayer->getPlayerNumber();
        }
        // if playerTwo wins
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

    /**
     *
     * @return int
     */
    public function setUp(): int {
        // This resets the whole game board (deck is filled back to 54 and shuffled)
        $this->cards = $this->deck->getCards();
        $this->shuffleCards();

        // determines who wins RPS and starts the game as leader
        $winner = $this->decideRockPaperScissorsWinner(...$this->players);
        $this->currentLeader = $winner;
        echo "Player $winner wins rock-paper-scissors and starts the game as leader.";
        return $this->currentLeader;
    }

    public function draw() {
        // Return the top card of the deck
        // Returns Card object
        // If deck is empty, then returns null
        if (!empty($this->cards)) {
            return array_pop($this->cards);
        } else {
            return null;
        }
    }

    public function drawCards(int $cards = 13) {
        foreach ($this->players as $player) {
            for ($i = 0; $i < $cards; $i++) {
                $player->addCardToHand($this->draw());
            }
        }
    }

    public function whoIsLeader(): int {
        // return and integer 1 or 2 of which player is the current round leader
        return $this->currentLeader;
    }

    public function addPlayedCard(Card $card) {
        $this->playedCards[] = $card;
    }

    public function getLeadersPlayedCard(): Card {
        // get leaders card which is the first played card. Player uses this for the sortCards() logic
        return array_slice($this->playedCards, 0, 1)[0];
    }

    public function pickUp(): array {
        return $this->playedCards;
    }

    public function play(Player $leader, Player $opponent): int {
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
}