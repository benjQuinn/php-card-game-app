<?php

use CardGameApp\Entities\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase {
    protected Player $playerOne;
    protected Player $playerTwo;

    public function setUp(): void {
        $this->playerOne = new Player("");
        $this->playerTwo = new Player("");
        $this->playerThree = new Player("");
    }

    // __construct()
    public function test_it_can_be_instantiated() {
        $this->assertInstanceOf(Player::class, $this->playerOne);
    }

    // generateRockPaperScissors()
    public function test_it_can_generate_rock_paper_or_scissors() {
        $result = $this->playerOne->generateRockPaperScissors();
        $exp = "/rock|paper|scissors/i";

        $this->assertSame(1, preg_match($exp, $result));
    }

    // getHandCount()

    // getScorePileCount()

    // getName()

    // getPlayerNumber()
    public function test_it_should_return_the_player_number_depending_on_the_order_of_instantiation() {
        $this->assertSame(7, $this->playerOne->getPlayerNumber());
        $this->assertSame(8, $this->playerTwo->getPlayerNumber());
        $this->assertSame(9, $this->playerThree->getPlayerNumber());
    }

    // checkLeader()

    // drawCards()

    // playCard()
}