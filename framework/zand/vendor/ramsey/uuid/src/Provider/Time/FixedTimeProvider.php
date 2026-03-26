<?php

namespace Ramsey\Uuid\Provider\Time;

use Ramsey\Uuid\Provider\TimeProviderInterface;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\Type\Time;

/**
 * FixedTimeProvider uses a known time to provide the time
 *
 * This provider allows the use of a previously-generated, or known, time
 * when generating time-based UUIDs.
 */
class FixedTimeProvider implements TimeProviderInterface
{
    /**
     * @var \Ramsey\Uuid\Type\Time
     */
    private $time;
    /**
     * @param \Ramsey\Uuid\Type\Time $time
     */
    public function __construct($time)
    {
        $this->time = $time;
    }

    /**
     * Sets the `usec` component of the time
     *
     * @param int|string|IntegerObject $value The `usec` value to set
     */
    public function setUsec($value)
    {
        $this->time = new Time($this->time->getSeconds(), $value);
    }

    /**
     * Sets the `sec` component of the time
     *
     * @param int|string|IntegerObject $value The `sec` value to set
     */
    public function setSec($value)
    {
        $this->time = new Time($value, $this->time->getMicroseconds());
    }

    public function getTime()
    {
        return $this->time;
    }
}
