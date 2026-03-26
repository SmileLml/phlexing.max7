<?php

namespace Spiral\RoadRunner\Console\Archive;

abstract class Archive implements ArchiveInterface
{
    /**
     * @param \SplFileInfo $archive
     */
    public function __construct($archive)
    {
        $this->assertArchiveValid($archive);
    }

    /**
     * @param \SplFileInfo $archive
     */
    private function assertArchiveValid($archive)
    {
        if (! $archive->isFile()) {
            throw new \InvalidArgumentException(
                \sprintf('Archive "%s" is not a file', $archive->getFilename())
            );
        }

        if (! $archive->isReadable()) {
            throw new \InvalidArgumentException(
                \sprintf('Archive file "%s" is not readable', $archive->getFilename())
            );
        }
    }
}
