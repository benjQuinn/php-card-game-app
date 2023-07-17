<?php

namespace App\Entities\Printers;

interface Printer
{
    public function print(string $text, string $colour = "default"): Printer;

    public function printGameWinner(int $playerNumber, $playerName, $colour = "default"): Printer;

    public function printDraw($colour = "default"): Printer;

    public function printLineBr(): Printer;
}