<?php

namespace Spiral\RoadRunner;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Environment\Mode;

/**
 * Provides base values to configure roadrunner worker.
 *
 * @psalm-import-type ModeType from Mode
 * @see Mode
 */
interface EnvironmentInterface
{
    /**
     * Returns worker mode assigned to the PHP process.
     *
     * @return ModeType|string
     */
    public function getMode();

    /**
     * Address worker should be connected to (or pipes).
     *
     * @return string
     */
    public function getRelayAddress();

    /**
     * RPC address.
     *
     * @return string
     */
    public function getRPCAddress();
}
