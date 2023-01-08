<?php
require_once "models/Round.php";

use PHPUnit\Framework\TestCase;

final class RoundTest extends TestCase
{
    public function testThrowsCorrectValues()
    {
        $round = new Round([5, 0], 1);
        $this->assertEquals(5, $round->getFirstThrow());

        $round->setFirstThrow(10);
        $this->assertEquals(10, $round->getFirstThrow());

        $round->setFirstThrow(0);
        $this->assertEquals(0, $round->getFirstThrow());

        $round->setSecondThrow(5);
        $this->assertEquals(5, $round->getSecondThrow());

        $round->setSecondThrow(0);
        $this->assertEquals(0, $round->getSecondThrow());

    }

    public function testThrowsIncorrectValues()
    {
        $this->expectException(InvalidArgumentException::class);
        $round = new Round([-1, 0], 1);

        {
            // On s'attend à une exception de type InvalidArgumentException
            $this->expectException(InvalidArgumentException::class);
            $round->setFirstThrow(-1);

            $this->expectException(InvalidArgumentException::class);
            $round->setSecondThrow(-1);

        }

        {
            // Idem, mais à la borne supérieure autorisée
            $this->expectException(InvalidArgumentException::class);
            $round->setFirstThrow(11);

            $this->expectException(InvalidArgumentException::class);
            $round->getSecondThrow(11);
        }
    }

    public function testIsStrike()
    {
        $round = new Round([10, 0], 1);
        $this->assertTrue($round->isStrike());
    }

    public function testIsSpare()
    {
        $round = new Round([5, 5], 1);
        $this->assertTrue($round->isSpare());

        $round = new Round([3, 7], 1);
        $this->assertTrue($round->isSpare());
    }
}
