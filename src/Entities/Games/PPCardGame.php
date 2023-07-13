<?php

namespace App\Entities\Games;

use App\Entities\Cards\Jack;
use App\Entities\Cards\Joker;
use App\Entities\Collections\Deck;
use App\Entities\Collections\Pile;
use App\Entities\Players\CardGamePlayer;
use App\Entities\Players\PPCardGamePlayer;
use App\Entities\Players\Player;
use App\Entities\Printers\Printer;

class PPCardGame extends CardGame
{
    public Game $pregame;

    public function __construct(array $players, Printer $printer, string $name, Deck $deck, Pile $pile, TwoPlayerGame $pregame)
    {

        parent::__construct($players, $printer, $name, $deck, $pile);
        $this->pregame = $pregame;
    }

    public function setUp(): int
    {
        $this->deck->shuffle();

        $this->pregame->play(...$this->players);
        $winner = $this->pregame->getWinner();

        // set the players in the array, indexed by their player number, as determined by the pregame
        $this->players = [
            $this->players[0]->getPlayerNumber() => $this->players[0],
            $this->players[1]->getPlayerNumber() => $this->players[1]
        ];

        $this->currentLeader = $winner->getPlayerNumber();

        return $this->currentLeader;
    }

    /**
     * Decides who is the winner when both players play their card
     * @param PPCardGamePlayer $leader the player that is the current leader when called
     * @param PPCardGamePlayer $opponent the player that is not the current leader when called
     * @return int the player number of the round winner
     */
    public function play(CardGamePlayer $leader, CardGamePlayer $opponent): int
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

        if ($leadersPlayedCard && $opponentsPlayedCard) {
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
        }
        // returns the winner of the round
        return $this->currentLeader;
    }

    public function decideWinner(CardGamePlayer $playerOne, CardGamePlayer $playerTwo): Player|false
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
}