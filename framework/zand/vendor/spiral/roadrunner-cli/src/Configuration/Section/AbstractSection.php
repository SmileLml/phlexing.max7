<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

abstract class AbstractSection implements SectionInterface
{
    public function getRequired()
    {
        return [];
    }

    abstract public function render();
}
