<?php

namespace Spiral\RoadRunner\Jobs\Task;

interface TaskInterface extends ProvidesHeadersInterface
{
    /**
     * Returns the (non-empty) name of the task/job.
     *
     * @psalm-mutation-free
     * @return non-empty-string
     */
    public function getName();

    /**
     * Returns payload of the task/job.
     *
     * @psalm-mutation-free
     * @return array
     */
    public function getPayload();

    /**
     * Retrieves value from payload by its key.
     *
     * @psalm-mutation-free
     * @param array-key $key
     * @param mixed $default
     * @return mixed
     */
    public function getValue($key, $default = null);

    /**
     * Determines that key defined in the payload.
     *
     * @psalm-mutation-free
     * @param array-key $key
     * @return bool
     */
    public function hasValue($key);
}
