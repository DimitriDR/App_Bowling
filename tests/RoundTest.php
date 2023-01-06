<?php
require_once "classes/Round.php";

use PHPUnit\Framework\TestCase;

final class RoundTest extends TestCase
{
    public function testThrowsCorrectValues()
    {
        $round = new Round();

        $round->setFirstThrow(5);
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
        $round = new Round();

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
        $round = new Round();
        $round->setFirstThrow(10);
        $this->assertTrue($round->isStrike());
    }

    public function testIsSpare()
    {
        $round = new Round();
        $round->setFirstThrow   (5);
        $round->setSecondThrow  (5);

        $this->assertTrue($round->isSpare());

        $round = new Round();
        $round->setFirstThrow   (3);
        $round->setSecondThrow  (7);

        $this->assertTrue($round->isSpare());
    }

}
