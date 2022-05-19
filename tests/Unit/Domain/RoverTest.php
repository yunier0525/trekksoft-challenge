<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Exceptions\RoverInvalidMissionConfigException;
use App\Domain\Instructions;
use App\Domain\Position;
use App\Domain\Rover;
use App\Domain\Terrain;
use Tests\TestCase;

class RoverTest extends TestCase
{
    public function testRoverSuccess()
    {
        $terrain = new Terrain([3, 4]);
        $position = new Position([1, 4, 'E']);
        $instructions = new Instructions(['R', 'L', 'M', 'M', 'R', 'R', 'L', 'L']);

        $rover = new Rover($terrain, $position, $instructions);

        $this->assertEquals($terrain, $rover->getTerrain());
        $this->assertEquals($position, $rover->getPosition());
        $this->assertEquals($instructions, $rover->getInstructions());
    }

    public function testRoverExceptions()
    {
        $terrain = new Terrain([3, 4]);
        $position = new Position([1, 6, 'E']);
        $instructions = new Instructions(['R', 'L', 'M', 'M', 'R', 'R', 'L', 'L']);

        $this->assertException(fn () => new Rover($terrain, $position, $instructions), RoverInvalidMissionConfigException::class);
    }
}
