<?php

namespace Tests;

use CardGameApp\Entities\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase {
    protected Card $card;

    public function setUp(): void {
        $this->card = new Card([
            "suit" => "Hearts",
            "face" => "Joker",
            "value" => 11,
            "isAce" => false,
            "isJoker" => true
        ]);
    }

    public function test_it_can_be_instantiated() {
        $this->assertInstanceOf(Card::class, $this->card);
    }
    public function test_it_has_a_suit_property() {
        $this->assertSame("Hearts", $this->card->getSuit());
    }
    public function test_it_has_a_value_property() {
        $this->assertSame(11, $this->card->getValue());
    }
}