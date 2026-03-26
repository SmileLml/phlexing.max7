<?php

namespace Spiral\RoadRunner\Console\Repository;

interface AssetInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getUri();

    /**
     * @param \Closure|null $progress
     * @return iterable<mixed, string>
     */
    public function download($progress = null);
}
