<?php
require_once dirname(__DIR__) . '/models/Player.php';

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    private const NUMBER_OF_ROUNDS  = 10;
    private const NUMBER_OF_PINS    = 10;

    /**
     * Test du constructeur de la classe Player
     * @return void
     */
    public function test__player_init(): void
    {
        $p = new Player("John Doe");
        $this->assertEquals("John Doe", $p->name);
    }

    public function test__save_throw_value(): void
    {
        // Cas corrects
        {
            $p = new Player("John Doe");
            $p->save_throw_value(5, 1, 1);
            $this->assertEquals(5, $p->get_scoreboard()[1]->get_first_throw());

            $p->save_throw_value(5, 1, 2);
            $this->assertEquals(5, $p->get_scoreboard()[1]->get_second_throw());

            $p->save_throw_value(9, 1, 3);
            $this->assertEquals(9, $p->get_scoreboard()[1]->get_third_throw());
        }
    }

    public function test__save_throw_value_out_of_bounds_round(): void
    {
        $p_2 = new Player("John Doe");
        $this->expectException(OutOfBoundsException::class);
        $p_2->save_throw_value(1, -9, 1);
    }

    public function test__save_throw_value_out_of_bounds_throw(): void
    {
        $p_2 = new Player("Johnny Depp");
        $this->expectException(OutOfBoundsException::class);
        $p_2->save_throw_value(1, 1, -9);
    }

    public function test__new_round(): void
    {
        $p = new Player("John Doe");
        $p->new_round();
        $this->assertEquals(2, sizeof($p->get_scoreboard()));
    }
}
