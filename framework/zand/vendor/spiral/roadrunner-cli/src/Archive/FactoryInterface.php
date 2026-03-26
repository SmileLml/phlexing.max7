<?php

namespace Spiral\RoadRunner\Console\Archive;

use Spiral\RoadRunner\Console\Repository\AssetInterface;

/**
 * @psalm-type ArchiveMatcher = \Closure(\SplFileInfo): ?ArchiveInterface
 */
interface FactoryInterface
{
    /**
     * @param \Closure $matcher
     * @return $this
     */
    public function extend($matcher);

    /**
     * @param \SplFileInfo $file
     * @return ArchiveInterface
     */
    public function create($file);

    /**
     * @param AssetInterface $asset
     * @param \Closure|null $progress
     * @param string|null $temp
     * @return ArchiveInterface
     */
    public function fromAsset($asset, $progress = null, $temp = null);
}
