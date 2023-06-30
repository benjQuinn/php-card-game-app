<?php

namespace App\Entities\Cards;

class Joker extends Card
{
    public function __construct()
    {
        $data["suit"] = "N/A";
        $data["face"] = "Joker";
        $data["value"] = 11;
        parent::__construct($data);
    }
}