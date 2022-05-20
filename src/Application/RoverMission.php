<?php

declare(strict_types=1);

namespace App\Application;

class RoverMission
{
    public function __construct(
        private array $terrainSize,
        private array $roverPosition,
        private array $instructions
    ) {
    }

    public function getTerrainSize(): array
    {
        return $this->terrainSize;
    }

    public function getRoverPosition(): array
    {
        return $this->roverPosition;
    }

    public function getInstructions(): array|string
    {
        return $this->instructions;
    }
}
