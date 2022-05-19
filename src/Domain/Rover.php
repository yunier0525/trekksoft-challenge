<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exceptions\RoverInvalidMissionConfigException;

class Rover
{
    private $terrain;
    private $position;
    private $instructions;

    public function __construct(Terrain $terrain, Position $position, Instructions $instructions)
    {
        $this->validate($terrain, $position);

        $this->terrain = $terrain;
        $this->position = $position;
        $this->instructions = $instructions;
    }

    public function getTerrain(): Terrain
    {
        return $this->terrain;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getInstructions()
    {
        return $this->instructions;
    }

    public function validate(Terrain $terrain, Position $position)
    {
        if (
            $terrain->getWidth() - 1 < $position->getX()
            || $terrain->getHeight() - 1 < $position->getY()
        ) {
            throw new RoverInvalidMissionConfigException();
        }
    }
}
