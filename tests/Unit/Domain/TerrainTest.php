<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Exceptions\TerrainSizeInvalidException;
use App\Domain\Terrain;
use Tests\TestCase;

class TerrainTest extends TestCase
{
    public function testTerrainSuccess()
    {
        $x = 7;
        $y = 9;

        $terrain = new Terrain([$x, $y]);

        $this->assertEquals($x + 1, $terrain->getWidth());
        $this->assertEquals($y + 1, $terrain->getHeight());

        $terrainXs = new Terrain([0, 0]);
        $this->assertEquals(1, $terrainXs->getHeight());
        $this->assertEquals(1, $terrainXs->getHeight());

        $this->assertTrue($terrain->isInsideTerrain(3, 7));
        $this->assertFalse($terrain->isInsideTerrain(8, 7));
    }

    public function testTerrainExceptions()
    {
        $this->assertException(fn () => new Terrain([]), TerrainSizeInvalidException::class);
        $this->assertException(fn () => new Terrain([2, 3, 4]), TerrainSizeInvalidException::class);
        $this->assertException(fn () => new Terrain(['2', 2]), TerrainSizeInvalidException::class);
        $this->assertException(fn () => new Terrain([2, '4']), TerrainSizeInvalidException::class);
        $this->assertException(fn () => new Terrain([-1, 2]), TerrainSizeInvalidException::class);
    }
}
