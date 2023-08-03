<?php

namespace App\Entities\Printers;

use SebastianBergmann\CodeCoverage\Report\PHP;

class BlackjackCLIPrinter extends CLIPrinter
{
    public function printHand(int $playerNumber, array $hand, int $handValue, bool $blackjack = false, bool $bust = false, string $colour = "default"): BlackjackCLIPrinter
    {
        $str = "Player ".$playerNumber." hand: ";

        foreach ($hand as $card)
        {
            if (end($hand) !== $card)
            {
                $str .= $card->getFace()." ".$card->getSuit().", ";
            } else {
                $str .= ", & ".$card->getFace()." ".$card->getSuit();
            }
        }
        $toRemove = [", ,",", & "];

        $result = str_replace($toRemove, "", $str);
        $result .= " = ".$handValue;

        if ($blackjack)
        {
            $result .= "\e".$this->colours["green"]." BLACKJACK".$this->closeColourStatement;
        }
        if ($bust)
        {
            $result .= "\e".$this->colours["red"]." BUST".$this->closeColourStatement;
        }

        echo $result.PHP_EOL;

        return $this;
    }
}