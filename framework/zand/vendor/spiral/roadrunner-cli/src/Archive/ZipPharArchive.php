<?php

namespace Spiral\RoadRunner\Console\Archive;

final class ZipPharArchive extends PharAwareArchive
{
    /**
     * @param \SplFileInfo $file
     * @return \PharData
     */
    protected function open($file)
    {
        $format = \Phar::ZIP | \Phar::GZ;

        return new \PharData($file->getPathname(), 0, null, $format);
    }
}
