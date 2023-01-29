<?php
require_once dirname(__DIR__) . '/models/Round.php';

use PHPUnit\Framework\TestCase;

final class RoundTest extends TestCase
{
    /**
     * @covers Round::get_first_throw
     * @covers Round::set_first_throw
     *
     **/
    public function test__set_first_throw(): void
    {
        // Cas correct
        {
            $r = new Round();
            $r->set_first_throw(5);
            $this->assertEquals(5, $r->get_first_throw());
        }

        // Cas retournant une exception
        {
            $this->expectException(InvalidArgumentException::class);
            $r = new Round();
            $r->set_first_throw(-1);

            $this->expectException(InvalidArgumentException::class);
            $r->set_first_throw(11);
        }
    }

    /**
     * @covers Round::get_second_throw
     * @covers Round::set_second_throw
     *
     **/
    public function test__set_second_throw(): void
    {
        // Cas correct
        {
            $r = new Round();
            $r->set_second_throw(5);
            $this->assertEquals(5, $r->get_second_throw());
        }

        // Cas retournant une exception
        {
            $this->expectException(InvalidArgumentException::class);
            $r_1 = new Round();
            $r_1->set_second_throw(-1);

            $r_2 = new Round();
            $this->expectException(InvalidArgumentException::class);
            $r_2->set_second_throw(11);
        }
    }

    /**
     * @covers Round::get_third_throw
     * @covers Round::set_third_throw
     *
     **/
    public function test__set_third_throw(): void
    {
        // Cas correct
        {
            $r = new Round();
            $r->set_first_throw(5);
            $r->set_second_throw(5);
            $r->set_third_throw(6);

            $this->assertEquals(6, $r->get_third_throw());
        }

        // Cas retournant une exception
        {
            // Cas où on veut définir le troisième lancer sans rien définir avant
            {
                $this->expectException(InvalidArgumentException::class);
                $r = new Round();
                $r->set_third_throw(-1);
            }

            // Cas où le joueur a bien fait deux lancers mais n'a pas fait de strike ou de spare
            {
                $this->expectException(InvalidArgumentException::class);
                $r = new Round();
                $r->set_first_throw(4);
                $r->set_second_throw(3);
                $r->set_third_throw(1);
            }
        }
    }
}
