<?php

namespace App\Entities\Cards;

class Joker extends Card
{
    public function __construct(int $value)
    {
        $data["suit"] = "N/A";
        $data["face"] = "Joker";
        $data["value"] = $value;
        parent::__construct($data);
    }
}