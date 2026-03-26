<?php

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Spiral\RoadRunner\Console\Environment\OperatingSystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class OperatingSystemOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct($command, $name = 'os', $short = 'o')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDescription()
    {
        return 'Required operating system (one of: ' . $this->choices() . ')';
    }

    /**
     * {@inheritDoc}
     */
    protected function default()
    {
        return OperatingSystem::createFromGlobals();
    }

    /**
     * {@inheritDoc}
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\StyleInterface $io
     */
    public function get($input, $io)
    {
        $os = parent::get($input, $io);

        if (! OperatingSystem::isValid($os)) {
            $message = 'Possibly invalid operating system (--%s=%s) option (available: %s)';
            $io->warning(\sprintf($message, $this->name, $os, $this->choices()));
        }

        return $os;
    }

    /**
     * @return string
     */
    private function choices()
    {
        return \implode(', ', OperatingSystem::all());
    }
}
