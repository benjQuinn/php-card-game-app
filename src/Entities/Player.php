<?php

namespace CardGameApp\Entities;

class Player {
    private static int $counter = 1;

    private int $playerNumber;
    private string $name;
    public array $hand = [];
    private array $scorePile = [];
    private bool $isLeader = false;

    public function __construct(string $name) {
        // first player that's instantiated will be playerNumber = 1 and this will increment every time a new player is instantiated
        $this->playerNumber = self::$counter++;
        $this->name = $name;
    }

    private function sortHand() {
        // sort cards in ascending order
        usort($this->hand, function ($a, $b) {
            return $a->getValue() - $b->getValue();
        });
    }

    private function filterCards(string $suit): array {
        // filter cards to match the given suit
        $filteredCards = [];

        foreach ($this->hand as $card) {
            if ($card->getSuit() === $suit) {
                $filteredCards[] = $card;
            }
        }
        return $filteredCards;
    }

    private function updateLeader(Table $table) {
        $leader = $table->whoIsLeader();
        // changes isLeader to true if whoIsLeader() returns the instances player number and vice versa
        $this->isLeader = $leader === $this->playerNumber;
    }

    public function generateRockPaperScissors(): string {
        $rps = ["rock", "paper", "scissors"];
        $randomIndex = rand(0, 2);

        return $rps[$randomIndex];
    }

    public function getHandCount(): int {
        return count($this->hand);
    }

    public function getScorePileCount(): int {
        return count($this->scorePile);
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPlayerNumber(): int {
        return $this->playerNumber;
    }

    public function checkLeader(): bool {
        return $this->isLeader;
    }

    public function drawCards(Table $table, int $cards = 13) {
        // takes card from the top of deck, provided by the Table object, and adds it to players hand 13 times
        for ($i = 0; $i < $cards; $i++) {
            $this->hand[] = $table->draw();
        }
    }

    public function playCard(Table $table): Card {
        $this->updateLeader($table);

        if (!$this->isLeader) {
            $this->sortHand();
            // get the card opponent has just played
            $opponentsPlayedCard = $table->getLastPlayedCard();
            // filters hand to only show cards that match suit of the played card
            $filteredHand = $this->filterCards($opponentsPlayedCard->getSuit());

            if (empty($filteredHand)) {
                // if player has no card matching suit, they strategically play their lowest card from their hand
                return array_shift($this->hand);
            } else {
                // else they play their highest matching card
                $card = array_pop($filteredHand);
                // now we need to remove the played card from hand
                $index = array_search($card, $this->hand);
                unset($this->hand[$index]);

                return $card;
            }

        } else {
            // if player is the leader they can sort cards to choose best card regardless of suit
            $this->sortHand();
            $card = array_pop($this->hand);
            // as leader goes first it also needs to update last played card on Table object
            $table->addPlayedCard($card);

            return $card;
        }
    }
}
