<?php

namespace App\Entities\Games;

use App\Entities\Players\Player;
use App\Entities\Players\PPCardGamePlayer;


/** MB
 * THis is a good first step. I like how you've created an interface to define the blueprint for a pre-game.
 * A few notes.
 *
 * There are more behaviours to a pre-game then decide leader. I notice that pregame objects are moved around your
 * code. The big adv. of an interface is to tell other code what can be publicly trusted/used from this object. I think
 * we should have more behaviours defined on this interface, such as : getWinner() getLoser()
 *
 * I'm not sure that I see a real difference between play() and decideLeader(). Do we just need 1 trigger function, that runs
 * the pre-game and decides the winner?
 *
 * If you define getWinner() and getLoser(), you might want to consider what happens, if you call them before play() is called
 *
 *
 */
interface Game
{
    public function getName(): string;

    public function decideWinner(Player $playerOne, Player $playerTwo): Player|false;

    public function getWinner(): Player;

    public function getLoser(array $players): Player|false;
}