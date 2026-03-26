<?php

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @psalm-type InputOptionType = InputOption::VALUE_*
 */
abstract class Option implements OptionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param Command $command
     * @param string $name
     * @param string|null $short
     */
    public function __construct($command, $name, $short = null)
    {
        $this->name = $name;

        $this->register($command, $name, $short ?? $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    private function register($command, $name, $short)
    {
        $command->addOption($name, $short, $this->getMode(), $this->getDescription(), $this->default());
    }

    /**
     * @return InputOptionType
     */
    protected function getMode()
    {
        return InputOption::VALUE_OPTIONAL;
    }

    /**
     * @return string
     */
    abstract protected function getDescription();

    /**
     * @param InputInterface $input
     * @param StyleInterface $io
     * @return string
     */
    public function get($input, $io)
    {
        $result = $input->getOption($this->name) ?: $this->default();

        return \is_string($result) ? $result : '';
    }

    /**
     * @return string|null
     */
    abstract protected function default();
}
