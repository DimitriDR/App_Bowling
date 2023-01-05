<?php
require_once "classes/Player.php";

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testScore()
    {
        $player = new Player("John");

        $this->assertEquals(0, $player->get_score());
        $this->assertEquals("John", $player->name);

        $player->add_score(10);
        $this->assertEquals(10, $player->get_score());

        $player->add_score(5);
        $this->assertEquals(15, $player->get_score());

        $this->expectException(InvalidArgumentException::class);
        $player->add_score(-30);
    }
}
