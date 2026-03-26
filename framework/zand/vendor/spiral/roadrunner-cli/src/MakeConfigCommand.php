<?php

namespace Spiral\RoadRunner\Console;

use Spiral\RoadRunner\Console\Command\InstallationLocationOption;
use Spiral\RoadRunner\Console\Configuration\Generator;
use Spiral\RoadRunner\Console\Configuration\Plugins;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeConfigCommand extends Command
{
    /**
     * @var \Spiral\RoadRunner\Console\Command\InstallationLocationOption
     */
    private $location;

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name ?? 'make-config');
        $this->location = new InstallationLocationOption($this);
    }

    protected function configure()
    {
        $this->addOption(
            'plugin',
            'p',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Generate configuration with selected plugins.'
        );

        $this->addOption(
            'preset',
            null,
            InputOption::VALUE_OPTIONAL,
            'Generate configuration with plugins in a selected preset.'
        );
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function execute($input, $output)
    {
        $io = $this->io($input, $output);


        $target = $this->location->get($input, $io) . '/.rr.yaml';

        if (\is_file($target) || \is_file(\getcwd() . '/.rr.yaml')) {
            return self::FAILURE;
        }

        $generator = new Generator();
        $plugins = $input->getOption('preset') ?
            Plugins::fromPreset($input->getOption('preset')) :
            Plugins::fromPlugins($input->getOption('plugin'));

        try {
            $config = $generator->generate($plugins);
            \file_put_contents($target, $config);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
