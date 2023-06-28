<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Cards\Jack;
use CardGameApp\Entities\Cards\Joker;
use CardGameApp\Entities\Collections\Deck;
use CardGameApp\Entities\Collections\Pile;
use CardGameApp\Entities\Pregames\PregameInterface;

/** MB
 * something for you to think about. GameController defines the core controller that runs the game. That makes sense,
 * but we make some assumptions. Firstly, that the game is 2 player. Second, that it is a card game. Third, it is our
 * specific card game. How could you restructure the code enforce its sue for the desired purpose and/or leave the door
 * open for accommodating changes to those assumptions in the future.
 */

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
        $winner = $this->pregame->decideWinner(...$this->players);

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
        /** MB:
         * We have some two-way tight coupling here. It is hard to see if this will be bad or not, but normally
         * we want to avoid tight coupling. The player & GameController classes are tightly coupled ond are acting on each other
         * Ideally we want 1 actor, so its clear which class/code is in charge.
         *
         * I think you've got a few design challenges here:
         * - The GameController is acting as the main actor/orchestrator because we don't have 2 real humans playing
         * - we could simulate 2 Player classes to behave like humans, and remove the central GameController, but that would
         *    be really complicated, so GameController is fine.
         * - We want to be careful when mixing state and controllers. Your GameController is also acting as the "table"
         *   which is why the players need access to it, so they can interact with the world.
         * - We ideally want the Player class only coupled to what it needs, which is
         *
         */
        // leaders Card gets added to playedCards array on playCard() call
        $leadersPlayedCard = $leader->playCard($this);
        $opponentsPlayedCard = $opponent->playCard($this);
        // adds opponents played card to the playedCards pile
        $this->playedCards->add($opponentsPlayedCard);

        // if leader has the highest value card they remain the leader
        if (($leadersPlayedCard->getValue() > $opponentsPlayedCard->getValue()) || ($leadersPlayedCard instanceof Joker && $opponentsPlayedCard instanceof Jack))
        {
            $this->currentLeader = $leader->getPlayerNumber();
        }
        // if opponent has the highest value card they become the leader
        if (($leadersPlayedCard->getValue() < $opponentsPlayedCard->getValue()) ||($leadersPlayedCard instanceof Jack && $opponentsPlayedCard instanceof Joker))
        {
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
        $printer = new PPCardGameCLIPrinter();

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
        /** MB : you probably want to look up dependency inversion and dependency injection. It would be better to have */
        $printer = new PPCardGameCLIPrinter();


        foreach ($this->players as $player)
        {
            $player->drawCards($this, 13);
        }
        /** MB : is there a reason that we check both the round no. AND the size of players hands?
         * If the game is fixed to 27 rounds, then shouldn't that be the only condition ?
         * Also, something to consider, while loops are dangerous. We should only use them, if we MUST use them. I think
         * a for loop would be fine here, because we have a fixed amount of iterations.
         * while loops always have the risk of running forever, if you have a bug and/or no escape conditions.
         */
        while ($this->doPlayersHaveCards() && $this->getCurrentRound() <= 27) {
            $leader = $this->players[$this->whoIsLeader()];
            $opponent = $this->players[$this->whoIsNotLeader()];

            $roundWinner = $this->play($leader, $opponent);

            // displays the winner of each round
            $playedCards = $this->getPlayedCards();

            foreach ($playedCards as $index => $card) {
                if ($index === 0) {
                    $printer->printPlayedCard($leader->getPlayerNumber(), $card->getFace(), $card->getSuit());
                } else {
                    $printer->printPlayedCard($opponent->getPlayerNumber(), $card->getFace(), $card->getSuit());
                }
            }
            /** MB : As mentioned elsewhere, we ideally don't want the echo cmd in our 'logic' code, just in our 'render' code. */
            $printer->printRoundWinner($this->getCurrentRound(), $roundWinner);

            // round winner picks up the two played cards and adds them to their score pile
            /** MB: we are calling $this->getPlayedCards() here, but just a few lines up, we already have $this->playedCards. Is there a need for this getter?  */
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
            $printer->printScore($player->getPlayerNumber(), $player->scorePile->count());
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
