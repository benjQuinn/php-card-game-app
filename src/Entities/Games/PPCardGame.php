<?php

namespace App\Entities\Games;

use App\Entities\Cards\Jack;
use App\Entities\Cards\Joker;
use App\Entities\Collections\Deck;
use App\Entities\Collections\Hand;
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

    public function playRound(CardGamePlayer $leader, CardGamePlayer $opponent): void
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

            $this->playedCards->add($leadersCard, "leader");
            $this->playedCards->add($opponentsCard, "opponent");
        }
        $this->currentRound++;
    }

    public function decideRoundWinner(CardGamePlayer $leader, CardGamePlayer $opponent, Pile $playedCards): int
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
                $this->playRound($leader, $opponent);
                return $this->decideRoundWinner($leader, $opponent, $this->playedCards);
            }
        }
        return $this->currentLeader;
    }

    public function decideWinner(): Player|false
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