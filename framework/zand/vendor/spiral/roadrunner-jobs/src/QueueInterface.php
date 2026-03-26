<?php

namespace Spiral\RoadRunner\Jobs;

use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Task\PreparedTaskInterface;
use Spiral\RoadRunner\Jobs\Task\QueuedTaskInterface;

/**
 * An interface that provides methods for working with a specific queue.
 */
interface QueueInterface
{
    /**
     * Returns the (non-empty) name of the queue.
     *
     * @return non-empty-string
     */
    public function getName();

    /**
     * Returns the default settings (options) for all tasks created
     * within this queue.
     *
     * @return OptionsInterface
     */
    public function getDefaultOptions();

    /**
     * Updates all default options for all tasks created in this queue.
     *
     * Please note that the settings for already created tasks will NOT
     * be changed.
     *
     * @param OptionsInterface|null $options
     * @return $this
     */
    public function withDefaultOptions($options);

    /**
     * Creates a new task to run on the specified queue.
     *
     * @param string $name
     * @param array $payload
     * @param OptionsInterface|null $options
     * @return PreparedTaskInterface
     */
    public function create($name, $payload = [], $options = null);

    /**
     * Sends a task to the queue.
     *
     * @param PreparedTaskInterface $task
     * @return QueuedTaskInterface
     * @throws JobsException
     */
    public function dispatch($task);

    /**
     * Sends multiple tasks to the queue
     *
     * @param PreparedTaskInterface ...$tasks
     * @return iterable<QueuedTaskInterface>
     * @throws JobsException
     */
    public function dispatchMany(...$tasks);

    /**
     * @throws JobsException
     */
    public function pause();

    /**
     * @throws JobsException
     */
    public function resume();

    /**
     * @return bool
     * @throws JobsException
     */
    public function isPaused();
}
