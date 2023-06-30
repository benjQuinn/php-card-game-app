<?php

namespace App\Entities\Printers;

interface Printer
{
    public function printGameWinner(int $playerNumber, $playerName);

    public function printDraw();

    public function printLineBr();
}