<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exceptions\InstructionsInvalidException;

class Instructions
{
    private $step;
    public function __construct(private array|string $instructions)
    {
        $this->validate($instructions);

        $this->step = -1;
    }

    public function getInstructions(): array
    {
        if (!is_array($this->instructions)) {
            $this->instructions = str_split($this->instructions, 1);
        }

        return $this->instructions;
    }

    public function next(): string
    {
        if ($this->step < count($this->getInstructions()) - 1) {
            $this->step++;

            return $this->getInstructions()[$this->step];
        }

        // EOM >>> End of mission
        return 'EOM';
    }

    public function validate(array|string $instructions)
    {
        if (empty($instructions)) {
            throw new InstructionsInvalidException('Empty value is not allowed.');
        }

        $stringInstructions = $instructions;
        if (is_array($stringInstructions)) {
            $stringInstructions = implode('', $stringInstructions);
        }

        preg_match_all('/[^RLM]/', $stringInstructions, $output_array);
        if (count($output_array[0]) !== 0) {
            $values = implode(', ', $output_array[0]);
            throw new InstructionsInvalidException(
                'The instructions of the rover should be have the format "[RLM]", the values ' . $values . ' are not allowed.'
            );
        }
    }

    public function getInstructionsExecuted()
    {
        if ($this->step < 0) {
            return [];
        }

        return array_slice($this->getInstructions(), 0, $this->step + 1);
    }
}
