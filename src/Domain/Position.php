<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exceptions\PositionInvalidException;

class Position
{
    private $x;
    private $y;
    private $direction;

    public function __construct(array $data)
    {
        $this->validate($data);

        $this->x = $data[0];
        $this->y = $data[1];
        $this->direction = $data[2];
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function validate(array $data)
    {
        if (
            count($data) !== 3
            || !is_int($data[0])
            || !is_int($data[1])
            || $data[0] < 0
            || $data[1] < 0
            || !is_string($data[2])
            || !in_array($data[2], ['N', 'S', 'E', 'W'])
        ) {
            throw new PositionInvalidException('Invalid position data.');
        }
    }
}
