<?php

namespace Ramsey\Uuid\Provider;

use Ramsey\Uuid\Type\Time;

/**
 * A time provider retrieves the current time
 */
interface TimeProviderInterface
{
    /**
     * Returns a time object
     */
    public function getTime();
}
