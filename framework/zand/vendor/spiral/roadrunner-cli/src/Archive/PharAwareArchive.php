<?php

namespace Spiral\RoadRunner\Console\Archive;

abstract class PharAwareArchive extends Archive
{
    /**
     * @var \PharData
     */
    protected $archive;

    /**
     * @param \SplFileInfo $archive
     */
    public function __construct($archive)
    {
        parent::__construct($archive);

        $this->archive = $this->open($archive);
    }

    /**
     * @param \SplFileInfo $file
     * @return \PharData
     */
    abstract protected function open($file);

    /**
     * @param iterable<string, string> $mappings
     * @return \Generator<mixed, \SplFileInfo>
     */
    public function extract($mappings)
    {
        $phar = $this->open($this->archive);

        if (! $phar->isReadable()) {
            throw new \LogicException(\sprintf('Could not open "%s" for reading', $this->archive->getPathname()));
        }

        /** @var \PharFileInfo $file */
        foreach (new \RecursiveIteratorIterator($phar) as $file) {
            foreach ($mappings as $from => $to) {
                if ($file->getFilename() === $from && (yield $file => new \SplFileInfo($to)) !== false) {
                    \copy($file->getPathname(), $to);
                }
            }
        }
    }
}
