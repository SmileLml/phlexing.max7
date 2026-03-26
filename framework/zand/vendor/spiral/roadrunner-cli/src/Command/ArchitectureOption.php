<?php

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Spiral\RoadRunner\Console\Environment\Architecture;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class ArchitectureOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct($command, $name = 'arch', $short = 'a')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDescription()
    {
        return 'Required processor architecture (one of: ' . $this->choices() . ')';
    }

    /**
     * {@inheritDoc}
     */
    protected function default()
    {
        return Architecture::createFromGlobals();
    }

    /**
     * {@inheritDoc}
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\StyleInterface $io
     */
    public function get($input, $io)
    {
        $architecture = parent::get($input, $io);

        if (! Architecture::isValid($architecture)) {
            $message = 'Possibly invalid architecture (--%s=%s) option (available: %s)';
            $io->warning(\sprintf($message, $this->name, $architecture, $this->choices()));
        }

        return $architecture;
    }

    /**
     * @return string
     */
    private function choices()
    {
        return \implode(', ', Architecture::all());
    }
}
