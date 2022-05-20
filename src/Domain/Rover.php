<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exceptions\RoverCannotMoveException;
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

    public function getInstructions(): Instructions
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

    public function executeInstructions()
    {
        // While the mission is not over (EOM = End of mission)
        $step = $this->instructions->next();
        while ($step !== 'EOM') {
            switch ($step) {
                case 'R':
                    $this->position->turnRight();
                    break;
                case 'L':
                    $this->position->turnLeft();
                    break;
                case 'M':
                    $this->move();
                    break;
            }

            $step = $this->instructions->next();
        }

        return $this->position->toString();
    }

    public function move()
    {
        $x = -1;
        $y = -1;

        $this->position->futureMove($x, $y);

        if ($this->terrain->isInsideTerrain($x, $y)) {
            $this->position->move();
        } else {
            throw new RoverCannotMoveException();
        }
    }
}
