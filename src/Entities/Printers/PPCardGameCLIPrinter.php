<?php

namespace App\Entities\Printers;

class PPCardGameCLIPrinter extends CLIPrinter
{
    public function printPlayedCard(int $playerNumber, string $cardFace, string $cardSuit, string $colour = "default"): CLIPrinter
    {
        echo "\e".$this->colours[$colour]."Player ".$playerNumber." card: ".$cardFace." ".$cardSuit.$this->closeColourStatement.PHP_EOL;

        return $this;
    }
}
