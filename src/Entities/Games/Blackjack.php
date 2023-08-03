<?php

namespace App\Entities\Games;

use App\Entities\Players\BlackjackPlayer;

class Blackjack extends CardGame
{
    const BLACKJACK = 21;

    public function setUp(): Blackjack
    {
        if (!$this->playedCards->isEmpty()) {
            $this->playedCards->removeAllCards();
        }
        // Remove all cards from Deck and instantiate
        $this->deck->removeAllCards()->createDeck(11)->shuffle();
        parent::setUp();

        return $this;
    }

    public function startRound(): Blackjack
    {
        $this->winner = false;
        foreach ($this->players as $player) {
            $player->initialisePlayer();
            $player->drawCards($this, 2);
        }

        $this->currentRound++;
        return $this;
    }

//    public function decideRoundWinner(BlackjackPlayer $playerOne, BlackjackPlayer $playerTwo): int|false
//    {
//        // Draw conditions
//        if ($playerOne->isBust() && $playerTwo->isBust() || $playerOne->isBlackjack() && $playerTwo->isBlackjack())
//        {
//            $this->winner = false;
//        } else if ($playerOne->isBlackjack() || $playerTwo->isBust()) {
//            $this->winner = $playerOne;
//        } else if ($playerTwo->isBlackjack() || $playerOne->isBust()) {
//            $this->winner = $playerTwo;
//        } else {
//            // If no bust or blackjack, find the player whose score is closest to 21
//            $handValues = [$playerOne->getHandValue(), $playerTwo->getHandValue()];
//
//            $values = [];
//            foreach ($handValues as $handValue) {
//                $value = self::BLACKJACK - $handValue;
//                $values[] = $value;
//                var_dump($this->players);
//            }
//            if ($values[0] === $values[1]) {
//                $this->winner = false;
//            }
//
//            $winningValue = max($values);
//            var_dump($winningValue);
//            $players = [$playerOne, $playerTwo];
//
//
//                foreach ($players as $player) {
//                    if (($winningValue + self::BLACKJACK) === $player->getHandValue()) {
//                        var_dump($winningValue);
//                        $this->winner = $player;
//                    }
//                }
//
//        }

        public function decideRoundWinner(): int|false
    {
        $playerOne = $this->players[1];
        $playerTwo = $this->players[2];

        // Draw conditions
        if ($playerOne->isBust() && $playerTwo->isBust() || $playerOne->isBlackjack() && $playerTwo->isBlackjack() || $playerOne->getHandValue() === $playerTwo->getHandValue())
        {
            $this->winner = false;
        } else if ($playerOne->isBlackjack() || $playerTwo->isBust()) {
            $this->winner = $playerOne;
        } else if ($playerTwo->isBlackjack() || $playerOne->isBust()) {
            $this->winner = $playerTwo;
        } else {
            // If no bust or blackjack, find the player whose score is closest to 21
            $handValues = [$playerOne->getHandValue(), $playerTwo->getHandValue()];

            $values = [];
            foreach ($handValues as $handValue) {
                $value = $handValue - self::BLACKJACK ;
                $values[] = $value;
            }

            $winningValue = max($values);

            foreach ($this->players as $player) {
                if (($winningValue + self::BLACKJACK) === $player->getHandValue()) {
                    $this->winner = $player;
                }
            }
        }

        if ($this->winner)
        {
            $this->winner->wonRound();
            return $this->winner->getPlayerNumber();
        } else {
            return $this->winner;
        }
    }

    public function decideWinner(): BlackjackPlayer|false
    {
        $scores = [];
        foreach ($this->players as $player) {
            $scores[] = $player->getRoundsWon();
        }

        $winningScore = max($scores);
        if ($scores[0] === $scores[1]) {
            $this->winner = false;
        } else {
            foreach ($this->players as $player) {
                if ($winningScore + 21 === $player->getHandValue()) {
                    $this->winner = $player;
                }
            }
        }
        return $this->winner;
    }
}


