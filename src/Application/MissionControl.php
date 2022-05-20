<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Exceptions\RoverCannotMoveException;
use App\Domain\Instructions;
use App\Domain\Position;
use App\Domain\Rover;
use App\Domain\Terrain;

class MissionControl
{
    public function execute(RoverMission $mission)
    {
        $terrain = new Terrain($mission->getTerrainSize());
        $position = new Position($mission->getRoverPosition());
        $instructions = new Instructions($mission->getInstructions());

        $rover = new Rover($terrain, $position, $instructions);

        try {
            return $rover->executeInstructions();
        } catch (RoverCannotMoveException $ex) {
            $lastPosition = $rover->getPosition()->toString();
            $instructionsExecuted = implode('', $rover->getInstructions()->getInstructionsExecuted());
            return "The Rover can't move, the final position was '$lastPosition' and the instructions executed are '$instructionsExecuted'";
        }
    }
}
