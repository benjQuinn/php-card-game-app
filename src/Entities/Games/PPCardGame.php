<?php

namespace App\Entities\Games;

use App\Entities\Cards\Jack;
use App\Entities\Cards\Joker;
use App\Entities\Collections\Hand;
use App\Entities\Collections\Pile;
use App\Entities\Players\PPCardGamePlayer;

class PPCardGame extends CardGame
{
    public function playRound(PPCardGamePlayer $leader, PPCardGamePlayer $opponent): PPCardGame
    {
        if ($leader->hasCards() && $opponent->hasCards())
        {
            $leadersCard = $leader->playCard($leader->returnHighestValueCard());

            $opponentsFilteredHand = $opponent->getFilteredCards($leadersCard->getSuit(), new Hand());

            if ($opponentsFilteredHand->isEmpty())
            {
                $opponentsCard = $opponent->playCard($opponent->returnLowestValueCard());
            } else {
                $opponentsCard = $opponent->filteredHand->returnLastCard();
            }

            $this->playedCards->add($leadersCard, "leader")->add($opponentsCard, "opponent");
        }
        $this->currentRound++;

        return $this;
    }

    public function decideRoundWinner(PPCardGamePlayer $leader, PPCardGamePlayer $opponent, Pile $playedCards): int
    {
        if (!$this->playedCards->isEmpty())
        {
            if ($playedCards->getCards()["leader"]->getValue() > $playedCards->getCards()["opponent"]->getValue() ||
                ($playedCards->getCards()["leader"] instanceof Joker && $playedCards->getCards()["opponent"] instanceof Jack))
            {
                $this->currentLeader = $leader->getPlayerNumber();
            }
            if ($playedCards->getCards()["leader"]->getValue() < $playedCards->getCards()["opponent"]->getValue() ||
                ($playedCards->getCards()["leader"] instanceof Jack && $playedCards->getCards()["opponent"] instanceof Joker))
            {
                $this->currentLeader = $opponent->getPlayerNumber();
            }
            if ($playedCards->getCards()["leader"]->getValue() === $playedCards->getCards()["opponent"]->getValue())
            {
                foreach ($playedCards as $card)
                {
                    $this->playedCards->remove($card);
                }
                return $this->playRound($leader, $opponent)->decideRoundWinner($leader, $opponent, $this->playedCards);
            }
        }
        return $this->currentLeader;
    }

    public function decideWinner(): PPCardGamePlayer|false
    {
        $scores = [];

        foreach ($this->players as $player)
        {
            $scores[] = $player->scorePile->count();
        }

        $winningScore = max($scores);

        if ($scores[0] === $scores[1])
        {
            $this->winner = false;
        } else {
            foreach ($this->players as $player) {
                if ($winningScore === $player->scorePile->count())
                {
                    $this->winner = $player;
                }
            }
        }
        return $this->winner;
    }
}