<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Event;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author marie <marie@users.noreply.github.com>
 */
final class ConsoleSignalEvent extends ConsoleEvent
{
    /**
     * @var int
     */
    private $handlingSignal;
    /**
     * @var int|false
     */
    private $exitCode;

    /**
     * @param int|false $exitCode
     * @param \Symfony\Component\Console\Command\Command $command
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param int $handlingSignal
     */
    public function __construct($command, $input, $output, $handlingSignal, $exitCode = 0)
    {
        parent::__construct($command, $input, $output);
        $this->handlingSignal = $handlingSignal;
        $this->exitCode = $exitCode;
    }

    public function getHandlingSignal()
    {
        return $this->handlingSignal;
    }

    /**
     * @param int $exitCode
     */
    public function setExitCode($exitCode)
    {
        if ($exitCode < 0 || $exitCode > 255) {
            throw new \InvalidArgumentException('Exit code must be between 0 and 255.');
        }

        $this->exitCode = $exitCode;
    }

    public function abortExit()
    {
        $this->exitCode = false;
    }

    /**
     * @return int|false
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
