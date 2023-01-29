<?php
require_once dirname(__DIR__) . '/models/Player.php';

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
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

    public function test__did_spare(): void
    {
        $p = new Player("John Doe");
        $p->save_throw_value(5, 1, 1);
        $p->save_throw_value(5, 1, 2);
        $this->assertTrue($p->did_spare_in_round(1));
    }

    public function test__did_spare_out_of_bounds(): void
    {
        $p = new Player("John Doe");
        $this->expectException(OutOfBoundsException::class);
        $p->did_spare_in_round(11);
    }

    public function test__did_strike(): void
    {
        $p = new Player("John Doe");
        $p->save_throw_value(10, 1, 1);
        $this->assertTrue($p->did_strike_in_round(1));
    }

    public function test__did_strike_out_of_bounds(): void
    {
        $p = new Player("John Doe");
        $this->expectException(OutOfBoundsException::class);
        $p->did_strike_in_round(11);
    }

    public function test__compute_points_one_round(): void
    {
        $g = new Game([new Player("John Doe")]);
        $g->save_throw(1);
        $g->save_throw(6);

        $p = $g->get_current_player();

        $this->assertEquals(7, $p->point_calculation());
    }

    public function test__compute_points_spare(): void
    {
        $g = new Game([new Player("John Doe")]);
        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $g->save_throw(6);
        $g->save_throw(2);

        $p = $g->get_current_player();

        $this->assertEquals(24, $p->point_calculation());
    }

    public function test__compute_points_spare_2(): void
    {
        $g = new Game([new Player("John Doe")]);
        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $g->save_throw(3);
        $g->save_throw(2);

        $p = $g->get_current_player();

        $this->assertEquals(18, $p->point_calculation());
    }

    public function test__compute_points_strike(): void
    {
        $g = new Game([new Player("John Doe")]);
        $g->save_throw(10);

        $g->next();

        $g->save_throw(5);
        $g->save_throw(4);

        $p = $g->get_current_player();

        $this->assertEquals(28, $p->point_calculation());
    }

    public function test__compute_points_strike_near_end(): void
    {
        $g = new Game([new Player("John Doe")]);

        // On triche un peu pour remplir uniquement le dernier round
        for ($i = 0; $i < 9; $i++) {
            $g->save_throw(2);
            $g->save_throw(2);
            $g->next();
        }

        $g->save_throw(10);
        $g->save_throw(5);
        $g->save_throw(4);

        $p = $g->get_current_player();

        $this->assertEquals(55, $p->point_calculation());
    }

    public function test__compute_points_spare_near_end(): void
    {
        $g = new Game([new Player("John Doe")]);

        // On triche un peu pour remplir uniquement le dernier round
        for ($i = 0; $i < 9; $i++) {
            $g->save_throw(2);
            $g->save_throw(2);
            $g->next();
        }

        $g->save_throw(5);
        $g->save_throw(5);
        $g->save_throw(4);

        $p = $g->get_current_player();

        $this->assertEquals(50, $p->point_calculation());
    }


}
