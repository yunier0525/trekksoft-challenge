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
