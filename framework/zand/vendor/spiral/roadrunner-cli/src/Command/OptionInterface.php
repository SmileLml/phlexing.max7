<?php

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

interface OptionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param InputInterface $input
     * @param StyleInterface $io
     * @return string
     */
    public function get($input, $io);
}
