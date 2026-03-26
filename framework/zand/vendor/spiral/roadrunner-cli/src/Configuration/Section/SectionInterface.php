<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

interface SectionInterface
{
    public function render();

    public function getRequired();

    public static function getShortName();
}
