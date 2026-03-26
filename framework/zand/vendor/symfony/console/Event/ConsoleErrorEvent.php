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
 * Allows to handle throwables thrown while running a command.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
final class ConsoleErrorEvent extends ConsoleEvent
{
    /**
     * @var \Throwable
     */
    private $error;
    /**
     * @var int
     */
    private $exitCode;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Throwable $error
     * @param \Symfony\Component\Console\Command\Command|null $command
     */
    public function __construct($input, $output, $error, $command = null)
    {
        parent::__construct($command, $input, $output);

        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * @param \Throwable $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @param int $exitCode
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;

        $r = new \ReflectionProperty($this->error, 'code');
        $r->setValue($this->error, $this->exitCode);
    }

    public function getExitCode()
    {
        return $this->exitCode ?? (\is_int($this->error->getCode()) && 0 !== $this->error->getCode() ? $this->error->getCode() : 1);
    }
}
