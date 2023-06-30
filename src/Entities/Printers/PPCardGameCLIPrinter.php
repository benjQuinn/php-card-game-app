<?php

namespace App\Entities\Printers;

class PPCardGameCLIPrinter implements Printer
{
    public function printPlayers(string $firstPlayer, string $secondPlayer)
    {
        echo "$firstPlayer and $secondPlayer step up to the table...".PHP_EOL;
    }
    public function printPregameWinner(string $winner, string $loser, string $pregame)
    {
        echo "$winner wins $pregame and starts the game as leader and Player 1.".PHP_EOL."$loser starts the game as Player 2.";
    }

    public function printStartGame()
    {
        echo "The deck is shuffled...".PHP_EOL.PHP_EOL."Begin!".PHP_EOL;
    }
    public function printPlayedCard(int $playerNumber, string $cardFace, string $cardSuit)
    {
        echo "Player ".$playerNumber." card: ".$cardFace." ".$cardSuit.PHP_EOL;
    }
    public function printRoundWinner(int $round, $winner)
    {
        echo "Round ".$round." winner: Player ".$winner.PHP_EOL.PHP_EOL;
    }
    public function printScore(int $playerNumber, int $score)
    {
        echo "Player " .$playerNumber. " score: " .$score.PHP_EOL;
    }
    public function printGameWinner(int $playerNumber, $playerName)
    {
        echo PHP_EOL."Player " .$playerNumber. ": " .$playerName. " is the winner!".PHP_EOL;
    }
    public function printDraw()
    {
        echo PHP_EOL."It's a tie!".PHP_EOL;
    }

    public function printLineBr()
    {
        echo PHP_EOL;
    }
}
