<?php

namespace Spiral\RoadRunner\Console\Repository;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\Stability;

/**
 * @psalm-import-type StabilityType from Stability
 */
interface ReleaseInterface
{
    /**
     * Returns Composer's compatible "pretty" release version.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns internal release tag version.
     * Please note that this version may not be compatible with Composer's
     * comparators.
     *
     * @return string
     */
    public function getVersion();

    /**
     * @return string
     */
    public function getRepositoryName();

    /**
     * @return StabilityType
     */
    public function getStability();

    /**
     * @return AssetsCollection|iterable<AssetInterface>
     */
    public function getAssets();

    /**
     * @param string $constraint
     * @return bool
     */
    public function satisfies($constraint);

    /**
     * @return string
     */
    public function getConfig();
}
