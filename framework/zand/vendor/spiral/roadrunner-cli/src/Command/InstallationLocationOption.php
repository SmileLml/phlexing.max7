<?php

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class InstallationLocationOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct($command, $name = 'location', $short = 'l')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDescription()
    {
        return 'Installation directory';
    }

    /**
     * {@inheritDoc}
     */
    protected function default()
    {
        return \getcwd() ?: '.';
    }

    /**
     * {@inheritDoc}
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\StyleInterface $io
     */
    public function get($input, $io)
    {
        $location = parent::get($input, $io);

        if (! \is_dir($location) || ! \is_writable($location)) {
            $message = 'Invalid installation directory (--%s=%s) option';
            $message = \sprintf($message, $this->name, $location);

            $io->warning($message);

            throw new \InvalidArgumentException('Installation directory not found or not writable');
        }

        return $location;
    }
}
