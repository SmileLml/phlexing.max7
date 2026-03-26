<?php

namespace Spiral\RoadRunner\Jobs;

use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Queue\CreateInfoInterface;

/**
 * Aggregate interface that provides methods for working with the queue server.
 *
 * @template-extends \IteratorAggregate<string, QueueInterface>
 */
interface JobsInterface extends \IteratorAggregate, \Countable
{
    /**
     * The method returns information about the availability of the queue server.
     *
     * @return bool
     */
    public function isAvailable();

    /**
     * A method that returns the selected queue. As the first argument, you
     * need to pass the name of a specific queue.
     *
     * @param string $queue
     * @return QueueInterface
     */
    public function connect($queue);

    /**
     * A method that creates an arbitrary queue. The first argument should be
     * information about the queue being created.
     *
     * @param CreateInfoInterface $info
     * @return QueueInterface
     * @throws JobsException
     */
    public function create($info);

    /**
     * A method that pauses an arbitrary number of queues whose names must be
     * specified as method arguments.
     *
     * @param QueueInterface|non-empty-string $queue
     * @param QueueInterface|non-empty-string ...$queues
     * @throws JobsException
     */
    public function pause($queue, ...$queues);

    /**
     * A method that resumes (unpauses) an arbitrary number of queues whose
     * names must be specified as method arguments.
     *
     * @param QueueInterface|non-empty-string $queue
     * @param QueueInterface|non-empty-string ...$queues
     * @throws JobsException
     */
    public function resume($queue, ...$queues);
}
