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
        /**
         * MB: we have an IF statement repeated 3 times here. What we're repeating is the evaluator.
         * ideally, we want to define the evaluation logic once and reuse. Something like :
        function decideRPSWin($choice1,$choice2):int{}
         * this func could take 2 string R/P/S choices and return 1 or 2 for which is the winner.
         * There is many ways to implement decideRPSWin(), but the importance is to encapsulate the evaluation
         * maybe you could have a go at this
         *
         * WORK TO DO
         */
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
        /**
         * MB: A small nit-pick, but in apps, you generally want a UI layer and a data layer.
         * This separates user input&feedback from your logic model. This gives you loose coupling and allows you to
         * develop them separately without being tied together. It seems that Table,Player,Card,Deck are your model and
         * GameController is the orchestrator and feeds back to the user. So all cmd prints, should be there.
         * By keeping them separate, this allows to change the UI without breaking your code.
         */
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
        /**
         * MB: If we assume that the Player object is the object with Agency and the Table is a world object, then this feels
         * like the table is acting on the player. Maybe this should live in the Player object. If we try to model things as close
         * to the real world, then it makes code easier for other devs to pick up.
         */
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
     * @param Card $card gets the leaders card from the players playCard method and they opponents card from the tables play method
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
        /**
         * MB: Code is fine. This is more of a design comment.
         * Here, you've effectively created wrapper func, that querying a specific attribute of an object's child. This
         * can sometimes be fine/ good, especially if we're encapsulating really complicated logic, to retrieve an answer,
         * such as $order->greenSpend(), which rolls of all items, checks if they are eco products and sums up their qty x gross.
         * However, in most scenarios, we want to avoid wrappers and make the object attribute available.
         * Its a balance between:
         *  - have we exposed something really useful for other devs or is it niche/bloat
         *  - have we simplified a complicated thing for other devs to re-use
         *  - SHOULD the data be exposed, given the apps rules/domain
         *
         * These are often the hard questions to think about, when designing. It might seem inconsequential here, but this theory
         * has a domino effect on large code bases.
         *
         * I think we probably want $table->getLeadersPlayedCard() or $table->getFirstPlayedCard() which returns a card object.
         * this gives the consumer more flexibility on the questions they want to ask, without writing many wrappers. For example,
         * what if we now want to know the card face of the leaders card, or if it was a joker etc.
         *
         */
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
     * returns true if either player has an empty deck
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