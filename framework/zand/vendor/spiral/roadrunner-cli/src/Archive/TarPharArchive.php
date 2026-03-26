<?php

namespace Spiral\RoadRunner\Console\Archive;

final class TarPharArchive extends PharAwareArchive
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
