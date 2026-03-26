<?php

namespace Spiral\RoadRunner\Jobs;

interface OptionsInterface
{
    /**
     * @var positive-int|0
     */
    public const DEFAULT_DELAY = 0;

    /**
     * @var positive-int|0
     */
    public const DEFAULT_PRIORITY = 10;

    /**
     * @var bool
     */
    public const DEFAULT_AUTO_ACK = false;

    /**
     * @psalm-immutable
     * @return positive-int|0
     */
    public function getDelay();

    /**
     * @psalm-immutable
     * @return positive-int|0
     */
    public function getPriority();

    /**
     * @psalm-immutable
     * @return bool
     */
    public function getAutoAck();
}
