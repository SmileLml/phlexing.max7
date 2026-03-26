<?php

namespace Spiral\RoadRunner\Console\Archive;

interface ArchiveInterface
{
    /**
     * @param iterable<string, string> $mappings
     * @return \Generator<mixed, \SplFileInfo>
     */
    public function extract($mappings);
}
