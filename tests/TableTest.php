<?php

namespace Tests;

use CardGameApp\Entities\Player;
use CardGameApp\Entities\Table;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase {
    protected Table $table;

    public function setUp(): void {
        $this->table = new Table([new Player(""), new Player("")]);
    }

    // __construct()
    public function test_it_can_be_instantiated() {
        $this->assertInstanceOf(Table::class, $this->table);
    }

    public function test_it_creates_a_deck_of_54_cards_when_instantiated() {
        $this->assertSame(54, count($this->table->getCards()));
    }

    // setUp()

    // drawCards()

    // addPlayedCard()

    // getLastPlayedCard()

    // pickUp()

    // play()
}