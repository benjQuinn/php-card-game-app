<?php

namespace CardGameApp\Entities\Cards;

class Joker extends Card implements \CardGameApp\Entities\Cards\CardInterface
{
    public function __construct()
    {
        $data["suit"] = "N/A";
        $data["face"] = "Joker";
        $data["value"] = 11;
        parent::__construct($data);
    }
}