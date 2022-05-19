<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Exceptions\InstructionsInvalidException;
use App\Domain\Instructions;
use Tests\TestCase;

class InstructionsTest extends TestCase
{
    public function testInstructionsSuccess()
    {
        $instructionsString = 'RLMRLMRLRMRLMLMLMLMMMMMLLLLLRRRLMRLMRLRM';
        $instructionsArray = ['R', 'L', 'M', 'M', 'R', 'R', 'L', 'L'];

        $instructions = new Instructions($instructionsString);
        $instructionsA = new Instructions($instructionsArray);

        $this->assertEquals(str_split($instructionsString), $instructions->getInstructions());
        $this->assertEquals($instructionsArray, $instructionsA->getInstructions());
    }

    public function testInstructionsExceptions()
    {
        $this->assertException(fn () => new Instructions('RLMRLMRLRMRLMLMLQ'), InstructionsInvalidException::class);
        $this->assertException(fn () => new Instructions(['R', 'Z', 'M', 'M', 'R', 'R', 'L', 'L']), InstructionsInvalidException::class);
        $this->assertException(fn () => new Instructions([]), InstructionsInvalidException::class);
        $this->assertException(fn () => new Instructions(''), InstructionsInvalidException::class);
    }
}
