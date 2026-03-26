<?php

namespace Spiral\RoadRunner\Console\Archive;

final class PharArchive extends PharAwareArchive
{
    /**
     * @param \SplFileInfo $file
     * @return \PharData
     */
    protected function open($file)
    {
        return new \PharData($file->getPathname());
    }
}
