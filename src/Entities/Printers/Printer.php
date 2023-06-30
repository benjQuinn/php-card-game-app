<?php

namespace App\Entities\Printers;

interface Printer
{
    public function print(string $text, string $colour = "default");

    public function printGameWinner(int $playerNumber, $playerName, $colour = "default");

    public function printDraw($colour = "default");

    public function printLineBr();
}