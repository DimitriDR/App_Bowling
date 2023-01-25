<?php
require_once "models/Round.php";

use PHPUnit\Framework\TestCase;

final class RoundTest extends TestCase
{
    public function testThrowsCorrectValues()
    {
        $round = new Round([5, 0], 1);
        $this->assertEquals(5, $round->get_first_throw());

        $round->set_first_throw(10);
        $this->assertEquals(10, $round->get_first_throw());

        $round->set_first_throw(0);
        $this->assertEquals(0, $round->get_first_throw());

        $round->setSecondThrow(5);
        $this->assertEquals(5, $round->get_second_throw());

        $round->setSecondThrow(0);
        $this->assertEquals(0, $round->get_second_throw());

    }

    public function testThrowsIncorrectValues()
    {
        $this->expectException(InvalidArgumentException::class);
        $round = new Round([-1, 0], 1);

        {
            // On s'attend à une exception de type InvalidArgumentException
            $this->expectException(InvalidArgumentException::class);
            $round->set_first_throw(-1);

            $this->expectException(InvalidArgumentException::class);
            $round->setSecondThrow(-1);

        }

        {
            // Idem, mais à la borne supérieure autorisée
            $this->expectException(InvalidArgumentException::class);
            $round->set_first_throw(11);

            $this->expectException(InvalidArgumentException::class);
            $round->get_second_throw(11);
        }
    }

    public function testIsStrike()
    {
        $round = new Round([10, 0], 1);
        $this->assertTrue($round->is_strike());
    }

    public function testIsSpare()
    {
        $round = new Round([5, 5], 1);
        $this->assertTrue($round->isSpare());

        $round = new Round([3, 7], 1);
        $this->assertTrue($round->isSpare());
    }
}
