<?php
require_once "classes/Game.php";

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testConstructor()
    {
        $player_1 = new Player("Player 1");
        $player_2 = new Player("Player 2");
        $player_3 = new Player("Player 3");
        $player_4 = new Player("Player 4");
        $player_5 = new Player("Player 5");

        $game = new Game([$player_1, $player_2, $player_3, $player_4, $player_5]);

        $this->assertEquals($player_1, $game->get_player_at(0));
        $this->assertEquals($player_2, $game->get_player_at(1));
        $this->assertEquals($player_3, $game->get_player_at(2));
        $this->assertEquals($player_4, $game->get_player_at(3));
        $this->assertEquals($player_5, $game->get_player_at(4));

        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals($game->get_player_at(5));

        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals($game->get_player_at(-1));
    }

    public function testAddPlayers()
    {
        $player_1 = new Player("Player 1");
        $player_2 = new Player("Player 2");
        $player_3 = new Player("Player 3");

        $game = new Game([$player_1, $player_2]);

        {
            $this->assertEquals($player_1, $game->get_player_at(0));
            $this->assertEquals($player_2, $game->get_player_at(1));

            $this->expectException(InvalidArgumentException::class);
            $this->assertEquals($player_3, $game->get_player_at(2));
        }

        {
            $game->add_player($player_3);
            $this->assertEquals($player_3, $game->get_player_at(2));
        }
    }
}
