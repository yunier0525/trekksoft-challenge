<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exceptions\TerrainSizeInvalidException;

class Terrain
{
    private $width;
    private $height;

    public function __construct(array $size)
    {
        $this->validateSize($size);

        $this->width = $size[0] + 1;
        $this->height = $size[1] + 1;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    private function validateSize(array $size)
    {
        if (
            count($size) < 2
            || count($size) > 2
            || !is_int($size[0])
            || !is_int($size[1])
            || $size[0] < 0
            || $size[1] < 0
        ) {
            throw new TerrainSizeInvalidException('Invalid terrain size.');
        }
    }

    public function isInsideTerrain($x, $y)
    {
        if (
            $x >= 0 && $x < $this->getWidth()
            && $y >= 0 && $y < $this->getHeight()
        ) {
            return true;
        }

        return false;
    }
}
