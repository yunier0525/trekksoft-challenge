<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Exceptions\PositionInvalidException;
use App\Domain\Position;
use Tests\TestCase;

class PositionTest extends TestCase
{
    public function testPositionSuccess()
    {
        $x = 4;
        $y = 6;
        $d = 'E';

        $position = new Position([$x, $y, $d]);

        $this->assertEquals($x, $position->getX());
        $this->assertEquals($y, $position->getY());
        $this->assertEquals($d, $position->getDirection());

        $futureX = -1;
        $futureY = -1;
        $position->futureMove($futureX, $futureY);
        $this->assertEquals(4, $futureX);
        $this->assertEquals(7, $futureY);

        $position->turnLeft();
        $this->assertEquals('N', $position->getDirection());
        $position->move();
        $this->assertEquals(5, $position->getX());
        $this->assertEquals($y, $position->getY());


        $position->turnLeft();
        $this->assertEquals('W', $position->getDirection());
        $position->move();
        $this->assertEquals(5, $position->getX());
        $this->assertEquals(5, $position->getY());

        $position->turnLeft();
        $this->assertEquals('S', $position->getDirection());
        $position->move();
        $this->assertEquals(4, $position->getX());
        $this->assertEquals(5, $position->getY());

        $position->turnLeft();
        $this->assertEquals('E', $position->getDirection());
        $position->move();
        $this->assertEquals(4, $position->getX());
        $this->assertEquals(6, $position->getY());

        $position->turnRight();
        $this->assertEquals('S', $position->getDirection());
        $position->move();
        $this->assertEquals(3, $position->getX());
        $this->assertEquals(6, $position->getY());

        $position->turnRight();
        $this->assertEquals('W', $position->getDirection());
        $position->move();
        $this->assertEquals(3, $position->getX());
        $this->assertEquals(5, $position->getY());

        $position->turnRight();
        $this->assertEquals('N', $position->getDirection());
        $position->move();
        $this->assertEquals(4, $position->getX());
        $this->assertEquals(5, $position->getY());

        $position->turnRight();
        $this->assertEquals('E', $position->getDirection());
        $position->move();
        $this->assertEquals(4, $position->getX());
        $this->assertEquals(6, $position->getY());

        $this->assertEquals('4 6 E', $position->toString());
    }

    public function testPositionException()
    {
        $x = 4;
        $y = 6;
        $d = 'Q';

        // Error for direction
        $this->assertException(fn () => new Position([$x, $y, $d]), PositionInvalidException::class);

        // Error for count of params
        $this->assertException(fn () => new Position([$x, $y]), PositionInvalidException::class);

        // Error for X range value
        $this->assertException(fn () => new Position([-1, $y, 'W']), PositionInvalidException::class);

        // Error for Y range value
        $this->assertException(fn () => new Position([$x, -1, 'E']), PositionInvalidException::class);

        // Error for X value type
        $this->assertException(fn () => new Position(['2', $y, 'E']), PositionInvalidException::class);

        // Error for Y value type
        $this->assertException(fn () => new Position([$x, '4', 'E']), PositionInvalidException::class);
    }
}
