<?php

namespace Spiral\RoadRunner\Console\Repository;

interface RepositoryInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ReleasesCollection|iterable<ReleaseInterface>
     */
    public function getReleases();
}
