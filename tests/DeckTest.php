<?php

namespace Tests;

use CardGameApp\Entities\Deck;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase {
    protected Deck $deck;

    public function setUp(): void {
        $this->deck = new Deck();
    }

    public function test_it_can_be_instantiated() {
        $this->assertInstanceOf(Deck::class, $this->deck);
    }

    public function test_created_deck_includes_13_cards_from_each_suit() {
        $hearts = [];
        $clubs = [];
        $spades = [];
        $diamonds = [];

        foreach ($this->deck->getCards() as $card) {
            if ($card->getSuit() === "Hearts") {
                $hearts[] = $card;
            }
            if ($card->getSuit() === "Clubs") {
                $clubs[] = $card;
            }
            if ($card->getSuit() === "Spades") {
                $spades[] = $card;
            }
            if ($card->getSuit() === "Diamonds") {
                $diamonds[] = $card;
            }
        }

        $this->assertSame(13, count($hearts));
        $this->assertSame(13, count($clubs));
        $this->assertSame(13, count($spades));
        $this->assertSame(13, count($diamonds));
    }

    public function test_created_deck_includes_2_jokers() {
        $jokers = [];

        foreach ($this->deck->getCards() as $card) {
            if ($card->getFace() === "Joker") {
                $jokers[] = $card;
            }
        }

        $this->assertSame(2, count($jokers));
    }

    public function test_jack_cards_has_a_value_of_11() {
        $jackCards = [];

        foreach ($this->deck->getCards() as $card) {
            if ($card->getFace() === "Jack") {
                $jackCards[] = $card;
            }
        }

        $this->assertSame(11, $jackCards[0]->getValue());
    }

    public function test_queen_cards_has_a_value_of_12() {
        $queenCards = [];

        foreach ($this->deck->getCards() as $card) {
            if ($card->getFace() === "Queen") {
                $queenCards[] = $card;
            }
        }

        $this->assertSame(12, $queenCards[0]->getValue());
    }

    public function test_king_cards_has_a_value_of_13() {
        $kingCards = [];

        foreach ($this->deck->getCards() as $card) {
            if ($card->getFace() === "King") {
                $kingCards[] = $card;
            }
        }

        $this->assertSame(13, $kingCards[0]->getValue());
    }

    public function test_ace_cards_has_a_value_of_14() {
        $aceCards = [];

        foreach ($this->deck->getCards() as $card) {
            if ($card->getFace() === "Ace") {
                $aceCards[] = $card;
            }
        }

        $this->assertSame(14, $aceCards[0]->getValue());
    }
}

