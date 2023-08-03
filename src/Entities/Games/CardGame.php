<?php

namespace App\Entities\Games;

use App\Entities\Cards\Card;
use App\Entities\Collections\Deck;
use App\Entities\Collections\Pile;
use App\Entities\Printers\Printer;

abstract class CardGame extends TwoPlayerGame
{
    public Printer $printer;
    public array $players;
    public Deck $deck;
    public Pile $playedCards;
    public Game $pregame;
    public int $currentLeader = 0;
    protected int $currentRound = 0;

    public function __construct(array $players, string $name, Deck $deck, Pile $pile, Game $pregame)
    {
        $this->name = $name;
        $this->players = $players;
        $this->deck = $deck;
        $this->playedCards = $pile;
        $this->pregame = $pregame;
    }

    public function getCurrentRound(): int
    {
        return $this->currentRound;
    }

    public function whoIsLeader(): int
    {
        return $this->currentLeader;
    }

    public function whoIsNotLeader(): ?int
    {
        $notLeader = null;
        foreach ($this->players as $playerNumber => $player)
        {
            if ($playerNumber !== $this->currentLeader)
            {
                $notLeader = $playerNumber;
            }
        }
        return $notLeader;
    }

    public function setUp(): CardGame
    {
        $this->deck->shuffle();

        $winner = $this->pregame->playRound(...$this->players)->getWinner();

        // set the players in the array, indexed by their player number, as determined by the pregame
        $this->players = [
            $this->players[0]->getPlayerNumber() => $this->players[0],
            $this->players[1]->getPlayerNumber() => $this->players[1]
        ];

        $this->currentLeader = $winner->getPlayerNumber();

        return $this;
    }

    public function draw(): Card
    {
        // Returns the top card of the deck
        $cards = $this->deck->getCards();
        $card = array_pop($cards);

        if (!empty($this->deck->getCards()))
        {
            $this->deck->remove($card);
        }

        return $card;
    }

    public function getPlayedCards(): Pile
    {
        return $this->playedCards;
    }


}