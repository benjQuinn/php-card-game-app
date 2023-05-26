<?php

namespace CardGameApp\Entities;

use CardGameApp\Entities\Pregames\PregameInterface;

class GameController {
    private Table $table;
    private array $players;
    protected PregameInterface $pregame;
    // private Pregame $pregame

    public function __construct(Table $table, PregameInterface $pregame) {
        $this->table = $table;
        $this->players = $this->table->getPlayers();
        $this->pregame = $pregame;
    }

    public function setUp(): int {
        $winner = $this->pregame->decideLeader(...$this->players);
        $this->table->currentLeader = $winner;

        return $this->table->currentLeader;
    }

    /**
     * Automates a game. The function sets up the table and plays through each round of the game, displaying the winner of each, before displaying the winner based on each player's score pile after the final round
     * @return string
     */
    public function runGame(): string {
        foreach ($this->table->players as $player)
        {
            $player->drawCards($this->table, 13);
        }

        while ($this->table->doPlayersHaveCards() && $this->table->getCurrentRound() <= 27) {
            $leader = $this->players[$this->table->whoIsLeader()];
            $opponent = $this->players[$this->table->whoIsNotLeader()];

            $roundWinner = $this->table->play($leader, $opponent);

            // displays the winner of each round
            echo "Round ".$this->table->getCurrentRound()." winner: Player $roundWinner".PHP_EOL.PHP_EOL;

            // round winner picks up the two played cards and adds them to their score pile
            foreach ($this->table->getPlayedCards() as $card) {
                // add played card to winner's score pile
                $this->players[$roundWinner]->scorePile->add($card);
                // and remove it from the table's played cards pile
                $this->table->playedCards->remove($card);
            }

            // after the winner picks up the played cards from this round, each player gets a new card from the deck IF the deck is not empty
            if (!empty($this->table->deck->getCards())) {
               foreach ($this->table->players as $player)
               {
                   $player->drawCards($this->table, 1);
               }
            }
        }

        foreach ($this->players as $player) {
            echo "Player " . $player->getPlayerNumber() . " score: " . $player->scorePile->count() . PHP_EOL;
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
            $scores[] = $player->scorePile->count();
        }

        // find the highest score in scores array
        $winningScore = max($scores);
        $resultStr = "";

        if ($scores[0] === $scores[1]) {
            $resultStr .= "It's a tie!";
        } else {
            // find which player had the winning score
            foreach ($this->players as $player) {
                if ($winningScore === $player->scorePile->count()) {
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
