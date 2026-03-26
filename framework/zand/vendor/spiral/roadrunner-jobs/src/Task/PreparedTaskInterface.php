<?php

namespace Spiral\RoadRunner\Jobs\Task;

use Spiral\RoadRunner\Jobs\OptionsInterface;

interface PreparedTaskInterface extends
    TaskInterface,
    OptionsInterface,
    WritableHeadersInterface,
    MutatesDelayInterface
{
    /**
     * Adds additional data to the task.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated payload data.
     *
     * See {@see getPayload()} to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param mixed $value Passed payload data
     * @param array-key|null $name Optional payload data's name (key)
     * @return static
     */
    public function withValue($value, $name = null);

    /**
     * Excludes payload data from task by given key (name).
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated payload data.
     *
     * See {@see getPayload()} to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param array-key $name
     * @return static
     */
    public function withoutValue($name);
}
