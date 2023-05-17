<?php

namespace CardGameApp\Entities;

class GameController {
    private Table $table;
    private array $players;

    public function __construct(Table $table) {
        $this->table = $table;
        $this->players = $this->table->getPlayers();
    }

    /**
     * Automates a game. The function sets up the table and plays through each round of the game, displaying the winner of each, before displaying the winner based on each player's score pile after the final round
     * @return string
     */
    public function runGame(): string {
        //table is set up and 13 cards are drawn to both players
        $this->table->setUp();
        $this->table->drawCards();

        echo PHP_EOL . PHP_EOL;

        while ($this->table->doPlayersHaveCards() && $this->table->getCurrentRound() <= 27) {

            $leader = $this->players[$this->table->whoIsLeader()];
            $opponent = $this->players[$this->table->whoIsNotLeader()];

            $roundWinner = $this->table->play($leader, $opponent);
            $currentRound = $this->table->getCurrentRound();

            // displays the winner of each round
            echo "Round $currentRound winner: Player $roundWinner" . PHP_EOL;

            // round winner picks up the two played cards and adds them to their score pile
            $this->players[$roundWinner]->receiveCardToScorePile($this->table->pickUp()[0]);
            $this->players[$roundWinner]->receiveCardToScorePile($this->table->pickUp()[1]);

            // after the winner picks up the played cards from this round, each player gets a new card from the deck IF the deck is not empty
            if (!empty($this->table->getCards())) {
                $this->table->drawCards(1);
            }
        }

        echo PHP_EOL;

        foreach ($this->players as $player) {
            echo "Player " . $player->getPlayerNumber() . " score: " . $player->getScorePileCount() . PHP_EOL;
        }

        echo PHP_EOL;

        return $this->decideWinner();
    }

    /**
     * Works out the winner based on each player's score pile
     * @return string
     */
    private function decideWinner(): string {
        $scores = [];

        // put each players score into an array
        foreach ($this->players as $player) {
            $scores[] = $player->getScorePileCount();
        }

        // find the highest score in scores array
        $winningScore = max($scores);
        $resultStr = "";

        if ($scores[0] === $scores[1]) {
            $resultStr .= "It's a tie!";
        } else {
            // find which player had the winning score
            foreach ($this->players as $player) {
                if ($winningScore === $player->getScorePileCount()) {
                    $resultStr .= "Player " . $player->getPlayerNumber() . " (" . $player->getName() . ") is the winner!";
                }
            }
        }

        return $resultStr;
    }

    /** Initialises the player and table properties so the game can be restarted
     * @return void
     */
    public function restartGame() {
        // initialise table properties
        $this->table->initialiseTable();
        // initialise both player properties
        foreach ($this->players as $player) {
            $player->initialisePlayer();
        }
    }
}
