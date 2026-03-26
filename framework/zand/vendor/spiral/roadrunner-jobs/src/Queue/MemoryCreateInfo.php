<?php

namespace Spiral\RoadRunner\Jobs\Queue;

/**
 * The DTO to create the Memory driver.
 *
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 */
final class MemoryCreateInfo extends CreateInfo
{
    /**
     * @var positive-int
     */
    public const PREFETCH_DEFAULT_VALUE = 10;

    /**
     * @var positive-int
     */
    public $prefetch = self::PREFETCH_DEFAULT_VALUE;

    /**
     * @param string $name
     * @param positive-int $priority
     * @param positive-int $prefetch
     */
    public function __construct(
        $name,
        $priority = self::PRIORITY_DEFAULT_VALUE,
        $prefetch = self::PREFETCH_DEFAULT_VALUE
    ) {
        parent::__construct(Driver::MEMORY, $name, $priority);

        assert($prefetch >= 1, 'Precondition [prefetch >= 1] failed');

        $this->prefetch = $prefetch;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return \array_merge(parent::toArray(), [
            'prefetch' => $this->prefetch,
        ]);
    }
}
