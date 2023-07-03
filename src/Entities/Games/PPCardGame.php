<?php

namespace App\Entities\Games;

use App\Entities\Cards\Jack;
use App\Entities\Cards\Joker;
use App\Entities\Collections\Deck;
use App\Entities\Collections\Pile;
use App\Entities\Players\PPCardGamePlayer;
use App\Entities\Players\Player;
use App\Entities\Printers\Printer;

class PPCardGame extends TwoPlayerGame
{
    public Game $pregame;
    public Printer $printer;
    public array $players;
    public Deck $deck;
    public Pile $playedCards;
    public int $currentLeader = 0;
    private int $currentRound = 0;

    public function __construct(array $players, TwoPlayerGame $pregame, Printer $printer)
    {
        $this->name = "Procure Plus Card Game App";
        $this->pregame = $pregame;
        $this->printer = $printer;
        $this->players = $players;
        $this->deck = new Deck();
        $this->playedCards = new Pile();
    }

    public function setUp(): int
    {
        $this->deck->shuffle();

        $winner = $this->pregame->decideWinner(...$this->players);

        // set the players in the array, indexed by their player number, as determined by the pregame
        $this->players = [
            $this->players[0]->getPlayerNumber() => $this->players[0],
            $this->players[1]->getPlayerNumber() => $this->players[1]
        ];

        $this->currentLeader = $winner->getPlayerNumber();

        return $this->currentLeader;
    }

    public function draw()
    {
        // Returns the top card of the deck
        $cards = $this->deck->getCards();
        $card = array_pop($cards);

        if (!empty($this->deck->getCards())) {
            $this->deck->remove($card);
        }

        return $card;
    }

    /**
     * Returns the player number of the current leader when called
     * @return int
     */
    public function whoIsLeader(): int
    {
        return $this->currentLeader;
    }

    /**
     * Returns the player number of the player that is not the leader when called
     * @return int
     */
    public function whoIsNotLeader()
    {
        foreach ($this->players as $playerNumber => $player) {
            if ($playerNumber !== $this->currentLeader) {
                return $playerNumber;
            }
        }
    }

    /**
     * Decides who is the winner when both players play their card
     * @param PPCardGamePlayer $leader the player that is the current leader when called
     * @param PPCardGamePlayer $opponent the player that is not the current leader when called
     * @return int the player number of the round winner
     */
    public function play(PPCardGamePlayer $leader, PPCardGamePlayer $opponent): int
    {
        $this->currentRound++;
        /** MB:
         * We have some two-way tight coupling here. It is hard to see if this will be bad or not, but normally
         * we want to avoid tight coupling. The player & PPCardGame classes are tightly coupled ond are acting on each other
         * Ideally we want 1 actor, so its clear which class/code is in charge.
         *
         * I think you've got a few design challenges here:
         * - The PPCardGame is acting as the main actor/orchestrator because we don't have 2 real humans playing
         * - we could simulate 2 PPCardGamePlayer classes to behave like humans, and remove the central PPCardGame, but that would
         *    be really complicated, so PPCardGame is fine.
         * - We want to be careful when mixing state and controllers. Your PPCardGame is also acting as the "table"
         *   which is why the players need access to it, so they can interact with the world.
         * - We ideally want the PPCardGamePlayer class only coupled to what it needs, which is
         *
         */
        // leaders Card gets added to playedCards array on playCard() call
        $leadersPlayedCard = $leader->playCard($this);
        $opponentsPlayedCard = $opponent->playCard($this);
        // adds opponents played card to the playedCards pile
        $this->playedCards->add($opponentsPlayedCard);

        // if leader has the highest value card they remain the leader
        if (($leadersPlayedCard->getValue() > $opponentsPlayedCard->getValue()) || ($leadersPlayedCard instanceof Joker && $opponentsPlayedCard instanceof Jack)) {
            $this->currentLeader = $leader->getPlayerNumber();
        }
        // if opponent has the highest value card they become the leader
        if (($leadersPlayedCard->getValue() < $opponentsPlayedCard->getValue()) || ($leadersPlayedCard instanceof Jack && $opponentsPlayedCard instanceof Joker)) {
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
    public function getCurrentRound(): int
    {
        return $this->currentRound;
    }

    /**
     * Works out the winner based on each player's score pile
     * @param Player $playerOne
     * @param Player $playerTwo
     * @return Player|false
     */
    public function decideWinner(Player $playerOne, Player $playerTwo): Player|false
    {
        $scores = [];

        foreach ($this->players as $player) {
            $scores[] = $player->scorePile->count();
        }

        $winningScore = max($scores);

        if ($scores[0] === $scores[1]) {
            $this->winner = false;
        } else {
            foreach ($this->players as $player) {
                if ($winningScore === $player->scorePile->count()) {
                    $this->winner = $player;
                }
            }
        }
        return $this->winner;
    }

    /**
     * Automates a game. The function sets up the table and plays through each round of the game, displaying the winner of each, before displaying the winner based on each player's score pile after the final round
     * @return void
     */
//    public function runGame(): void
//    {
//        foreach ($this->players as $player) {
//            $player->drawCards($this, 13);
//        }
//
//        for ($round = 1; $round <= 27; $round++) {
//            $leader = $this->players[$this->whoIsLeader()];
//            $opponent = $this->players[$this->whoIsNotLeader()];
//
//            $roundWinner = $this->play($leader, $opponent);
//
//            // displays the winner of each round
//            $playedCards = $this->getPlayedCards();
//
//            foreach ($playedCards as $index => $card) {
//                if ($index === 0) {
//                    $this->printer->printPlayedCard($leader->getPlayerNumber(), $card->getFace(), $card->getSuit());
//                } else {
//                    $this->printer->printPlayedCard($opponent->getPlayerNumber(), $card->getFace(), $card->getSuit());
//                }
//            }
//
//            $this->printer->printRoundWinner($this->getCurrentRound(), $roundWinner, "cyan");
//
//            // round winner picks up the two played cards and adds them to their score pile
//            foreach ($playedCards as $card) {
//                // add played card to winner's score pile
//                $this->players[$roundWinner]->scorePile->add($card);
//                // and remove it from the table's played cards pile
//                $this->playedCards->remove($card);
//            }
//            // after the winner picks up the played cards from this round, each player gets a new card from the deck IF the deck is not empty
//            if (!empty($this->deck->getCards())) {
//                foreach ($this->players as $player) {
//                    $player->drawCards($this, 1);
//                }
//            }
//        }
//
//        foreach ($this->players as $player) {
//            $this->printer->printScore($player->getPlayerNumber(), $player->scorePile->count(), "magenta");
//        }
//
//        $this->decideWinner(...$this->players);
//
//        if ($this->winner) {
//            $this->printer->printGameWinner($this->winner->getPlayerNumber(), $this->winner->getName(), "green");
//        } else {
//            $this->printer->printDraw("yellow");
//        }
//    }
};

//    /**
//     * Initialises the tables properties - used when restarting the game
//     * @return void
//     */
//    public function initialiseGame(): void
//    {
//        $this->deck = new Deck();
//        $this->playedCards = new Pile();
//        $this->currentLeader = 0;
//        $this->currentRound = 0;
//    }
//
//    /** Initialises the player and table properties so the game can be restarted
//     * @return void
//     */
//    public function restartGame(): void
//    {
//        // initialise table properties
//        $this->initialiseGame();
//        // initialise both player properties
//        foreach ($this->players as $player)
//        {
//            $player->initialisePlayer();
//        }
//    }
//}
