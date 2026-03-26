<?php

namespace Spiral\RoadRunner\Jobs\Queue;

use Ramsey\Uuid\Uuid;
use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\Jobs\DTO\V1\HeaderValue;
use Spiral\RoadRunner\Jobs\DTO\V1\Job;
use Spiral\RoadRunner\Jobs\DTO\V1\Options as OptionsMessage;
use Spiral\RoadRunner\Jobs\DTO\V1\PushBatchRequest;
use Spiral\RoadRunner\Jobs\DTO\V1\PushRequest;
use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Exception\SerializationException;
use Spiral\RoadRunner\Jobs\OptionsInterface;
use Spiral\RoadRunner\Jobs\QueueInterface;
use Spiral\RoadRunner\Jobs\Serializer\SerializerAwareInterface;
use Spiral\RoadRunner\Jobs\Serializer\SerializerInterface;
use Spiral\RoadRunner\Jobs\Task\PreparedTaskInterface;
use Spiral\RoadRunner\Jobs\Task\QueuedTask;
use Spiral\RoadRunner\Jobs\Task\QueuedTaskInterface;
use Spiral\RoadRunner\Jobs\Task\TaskInterface;

/**
 * @internal Executor is an internal library class, please do not use it in your code.
 * @psalm-internal Spiral\RoadRunner\Jobs
 */
final class Pipeline implements SerializerAwareInterface
{
    /**
     * @var RPCInterface
     */
    private $rpc;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @param QueueInterface $queue
     * @param RPCInterface $rpc
     * @param SerializerInterface $serializer
     */
    public function __construct($queue, $rpc, $serializer)
    {
        $this->rpc = $rpc;
        $this->serializer = $serializer;
        $this->queue = $queue;
    }

    /**
     * {@inheritDoc}
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * {@inheritDoc}
     * @param \Spiral\RoadRunner\Jobs\Serializer\SerializerInterface $serializer
     */
    public function withSerializer($serializer)
    {
        $self = clone $this;
        $self->serializer = $serializer;
        return $self;
    }

    /**
     * @param PreparedTaskInterface $task
     * @return QueuedTaskInterface
     * @throws JobsException
     */
    public function send($task)
    {
        try {
            $job = $this->taskToProto($task, $task);
            $this->rpc->call('jobs.Push', new PushRequest(['job' => $job]));
        } catch (JobsException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        return $this->createQueuedTask($job, $task);
    }

    /**
     * @param array<PreparedTaskInterface> $tasks
     * @return array<QueuedTaskInterface>
     * @throws JobsException
     */
    public function sendMany($tasks)
    {
        try {
            $result = $jobs = [];

            foreach ($tasks as $task) {
                $job = $jobs[] = $this->taskToProto($task, $task);
                $result[] = $this->createQueuedTask($job, $task);
            }

            $this->rpc->call('jobs.PushBatch', new PushBatchRequest([
                'jobs' => $jobs
            ]));
        } catch (JobsException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        return $result;
    }

    /**
     * @return non-empty-string
     */
    private function createTaskId()
    {
        return (string)Uuid::uuid4();
    }

    /**
     * @param TaskInterface $task
     * @param OptionsInterface $options
     * @return Job
     * @throws SerializationException
     */
    private function taskToProto($task, $options)
    {
        return new Job([
            'job'      => $task->getName(),
            'id'       => $this->createTaskId(),
            'payload'  => $this->payloadToProtoData($task),
            'headers'  => $this->headersToProtoData($task),
            'options'  => $this->optionsToProto($options),
            'auto_ack' => $options->getAutoAck(),
        ]);
    }

    /**
     * @param TaskInterface $task
     * @return string
     * @throws SerializationException
     */
    private function payloadToProtoData($task)
    {
        return $this->serializer->serialize($task->getPayload());
    }

    /**
     * @param TaskInterface $task
     * @return array<string, HeaderValue>
     */
    private function headersToProtoData($task)
    {
        $result = [];

        foreach ($task->getHeaders() as $name => $values) {
            if (\count($values) === 0) {
                continue;
            }

            $result[$name] = new HeaderValue([
                'value' => $values,
            ]);
        }

        return $result;
    }

    /**
     * @param OptionsInterface $options
     * @return OptionsMessage
     */
    private function optionsToProto($options)
    {
        return new OptionsMessage([
            'priority' => $options->getPriority(),
            'pipeline' => $this->queue->getName(),
            'delay'    => $options->getDelay(),
        ]);
    }

    /**
     * @param Job $job
     * @param TaskInterface $task
     * @return QueuedTask
     * @psalm-suppress ArgumentTypeCoercion Protobuf Job ID can not be empty
     */
    private function createQueuedTask($job, $task)
    {
        return new QueuedTask(
            $job->getId(),
            $this->queue->getName(),
            $task->getName(),
            $task->getPayload(),
            $task->getHeaders()
        );
    }
}
