<?php

namespace Spiral\RoadRunner\Console\Command;

use Spiral\RoadRunner\Console\Environment\Stability;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @psalm-import-type StabilityType from Stability
 */
class StabilityOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct($command, $name = 'stability', $short = 's')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        return 'Release minimum stability flag';
    }

    /**
     * {@inheritDoc}
     */
    protected function default()
    {
        return Stability::STABILITY_STABLE;
    }

    /**
     * {@inheritDoc}
     * @return StabilityType|string
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\StyleInterface $io
     */
    public function get($input, $io)
    {
        $stability = parent::get($input, $io);

        if (! Stability::isValid($stability)) {
            $message = 'Possibly invalid stability (--%s=%s) option (available: %s)';
            $io->warning(\sprintf($message, $this->name, $stability, $this->choices()));
        }

        return $stability;
    }

    /**
     * @return string
     */
    private function choices()
    {
        return \implode(', ', Stability::all());
    }
}
