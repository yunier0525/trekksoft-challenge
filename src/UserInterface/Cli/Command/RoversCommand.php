<?php

namespace App\UserInterface\Cli\Command;

use App\Application\MissionControl;
use App\Application\RoverMission;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class RoversCommand extends Command
{
    public function __construct()
    {
        parent::__construct('rovers');

        pcntl_signal(SIGINT, [$this, 'doInterrupt']);
        pcntl_signal(SIGTERM, [$this, 'doTerminate']);
    }

    protected function configure()
    {
        // $this->addArgument('name', InputArgument::REQUIRED, 'Name of the one running the example command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        do {
            $this->captureData($input, $output);

            $anotherRoverQuestion = new Question('Do you want to setup another rover? [yes]: ', true);
            $anotherRover = $helper->ask($input, $output, $anotherRoverQuestion);
        } while ($anotherRover === true || $anotherRover === 'yes');

        return self::SUCCESS;
    }

    private function captureData(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $upperRightCoordinatesQuestion = new Question('Please enter the upper-right coordinates of the area "X Y": ');
        $upperRightCoordinatesQuestion->setValidator(function ($answer) {
            $error = 'The coordinates of the rover should be have the format "X Y"';

            $params = explode(' ', $answer);

            if (count($params) !== 2) {
                throw new \RuntimeException($error);
            }

            $x = intval($params[0]);
            $y = intval($params[1]);

            if (
                !is_int($x) && $x < 0
                && !is_int($y) && $y < 0
            ) {
                throw new \RuntimeException($error);
            }

            return [$x, $y];
        });
        $upperRightCoordinatesQuestion->setMaxAttempts(2);
        $upperRightCoordinates = $helper->ask(
            $input,
            $output,
            $upperRightCoordinatesQuestion
        );

        $roverPositionQuestion = new Question('Please enter the rover’s position "X Y Direction(N, E, S, W): ');
        $roverPositionQuestion->setValidator(function ($answer) use ($upperRightCoordinates) {
            $params = explode(' ', $answer);

            $error = 'The position of the rover should be have the format "X Y P"';
            if (count($params) !== 3) {
                throw new \RuntimeException($error);
            }

            $x = intval($params[0]);
            $y = intval($params[1]);
            $p = $params[2];

            if (
                !is_int($x) || $x < 0 || $x > $upperRightCoordinates[0]
                || !is_int($y) || $y < 0 || $y > $upperRightCoordinates[1]
            ) {
                throw new \RuntimeException(
                    'The position coordinates must have this values: X [0 - ' . $upperRightCoordinates[0] . '], Y [0 - ' . $upperRightCoordinates[1] . ']'
                );
            }

            if (!in_array($p, ['N', 'S', 'E', 'W'])) {
                throw new \RuntimeException('The directon values must be one of this: N, E, S, W');
            }

            return [$x, $y, $p];
        });
        $roverPositionQuestion->setMaxAttempts(2);
        $roverPosition = $helper->ask(
            $input,
            $output,
            $roverPositionQuestion
        );

        $instructionsQuestion = new Question('Please enter the instructions to explore the area "R L M letters without spaces": ');
        $instructionsQuestion->setValidator(function ($answer) {
            preg_match_all('/[^RLM]/', $answer, $output_array);
            if (count($output_array[0]) !== 0) {
                $values = implode(', ', $output_array[0]);

                throw new \RuntimeException(
                    'The instructions of the rover should be have the format "[RLM]", the values ' . $values . ' are not allowed.'
                );
            }

            return str_split($answer, 1);
        });
        $instructionsQuestion->setMaxAttempts(2);
        $instructions = $helper->ask(
            $input,
            $output,
            $instructionsQuestion
        );

        $this->processData($output, $upperRightCoordinates, $roverPosition, $instructions);
    }

    private function processData(OutputInterface $output, $terrainSize, $position, $instructions)
    {
        $mission = new RoverMission($terrainSize, $position, $instructions);
        $missionControl = new MissionControl;

        $output->writeln($missionControl->execute($mission));
    }

    /**
     * Ctrl-C
     */
    private function doInterrupt()
    {
        $this->output->writeln('Interruption signal received.');

        exit;
    }

    /**
     * kill PID
     */
    private function doTerminate()
    {
        $this->output->writeln('Termination signal received.');

        exit;
    }
}
