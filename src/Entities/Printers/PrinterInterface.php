<?php

namespace App\Entities\Printers;

interface PrinterInterface
{
    public function printGameWinner(int $playerNumber, $playerName);

    public function printDraw();

    public function printLineBr();
}