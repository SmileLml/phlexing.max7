<?php

namespace Spiral\RoadRunner\Jobs\Queue;

/**
 * @psalm-import-type DriverType from Driver
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 */
class CreateInfo implements CreateInfoInterface
{
    /**
     * @var positive-int
     */
    public const PRIORITY_DEFAULT_VALUE = 10;

    /**
     * @var non-empty-string
     */
    public $name;

    /**
     * @var DriverType
     */
    public $driver;

    /**
     * Queue default priority for for each task pushed into this queue if the
     * priority value for these tasks was not explicitly set.
     *
     * @var positive-int
     */
    public $priority = self::PRIORITY_DEFAULT_VALUE;

    /**
     * @param string $driver
     * @param string $name
     * @param positive-int $priority
     */
    public function __construct($driver, $name, $priority = self::PRIORITY_DEFAULT_VALUE)
    {
        assert($driver !== '', 'Precondition [driver !== ""] failed');
        assert($name !== '', 'Precondition [name !== ""] failed');
        assert($priority >= 1, 'Precondition [priority >= 1] failed');

        $this->driver = $driver;
        $this->name = $name;
        $this->priority = $priority;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'driver' => $this->driver,
            'priority' => $this->priority,
        ];
    }
}
