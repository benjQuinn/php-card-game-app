<?php

namespace App\Entities\Games;

use App\Entities\Collections\Deck;
use App\Entities\Collections\Pile;
use App\Entities\Printers\Printer;

abstract class CardGame extends TwoPlayerGame
{
    public Printer $printer;
    public array $players;
    public Deck $deck;
    public Pile $playedCards;
    public int $currentLeader = 0;
    protected int $currentRound = 0;

    public function __construct(array $players, Printer $printer, string $name, Deck $deck, Pile $pile)
    {
        $this->name = $name;
        $this->printer = $printer;
        $this->players = $players;
        $this->deck = $deck;
        $this->playedCards = $pile;
    }

    public function draw()
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

    public function getPlayedCards(): Pile
    {
        return $this->playedCards;
    }

    public function getCurrentRound(): int
    {
        return $this->currentRound;
    }
}