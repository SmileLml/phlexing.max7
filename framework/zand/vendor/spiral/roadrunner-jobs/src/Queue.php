<?php

namespace Spiral\RoadRunner\Jobs;

use Spiral\Goridge\RPC\Codec\ProtobufCodec;
use Spiral\Goridge\RPC\RPC;
use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\Environment;
use Spiral\RoadRunner\Jobs\DTO\V1\Pipelines;
use Spiral\RoadRunner\Jobs\DTO\V1\Stat;
use Spiral\RoadRunner\Jobs\DTO\V1\Stats;
use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Queue\Pipeline;
use Spiral\RoadRunner\Jobs\Serializer\JsonSerializer;
use Spiral\RoadRunner\Jobs\Serializer\SerializerAwareInterface;
use Spiral\RoadRunner\Jobs\Serializer\SerializerInterface;
use Spiral\RoadRunner\Jobs\Task\PreparedTask;
use Spiral\RoadRunner\Jobs\Task\PreparedTaskInterface;
use Spiral\RoadRunner\Jobs\Task\QueuedTaskInterface;

final class Queue implements QueueInterface, SerializerAwareInterface
{
    /**
     * @var Options
     */
    private $options;

    /**
     * @var non-empty-string
     */
    private $name;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var RPCInterface
     */
    private $rpc;

    /**
     * @param string $name
     * @param RPCInterface|null $rpc
     * @param SerializerInterface|null $serializer
     */
    public function __construct($name, $rpc = null, $serializer = null)
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        $this->rpc = ($rpc ?? $this->createRPCConnection())
            ->withCodec(new ProtobufCodec())
        ;

        $this->pipeline = new Pipeline($this, $this->rpc, $serializer ?? new JsonSerializer());

        $this->name = $name;
        $this->options = new Options();
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->options = clone $this->options;
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
    public function getSerializer()
    {
        return $this->pipeline->getSerializer();
    }

    /**
     * {@inheritDoc}
     * @param \Spiral\RoadRunner\Jobs\Serializer\SerializerInterface $serializer
     */
    public function withSerializer($serializer)
    {
        $self = clone $this;
        $self->pipeline = $this->pipeline->withSerializer($serializer);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @return $this
     * @param \Spiral\RoadRunner\Jobs\OptionsInterface|null $options
     */
    public function withDefaultOptions($options = null)
    {
        $self = clone $this;
        /** @psalm-suppress PropertyTypeCoercion */
        $self->options = $options ?? new Options();

        return $self;
    }

    /**
     * {@inheritDoc}
     * @param string $name
     * @param mixed[] $payload
     * @param \Spiral\RoadRunner\Jobs\OptionsInterface|null $options
     */
    public function create($name, $payload = [], $options = null)
    {
        $options = Options::from($this->options)
            ->mergeOptional($options)
        ;

        return new PreparedTask($name, $payload, $options);
    }

    /**
     * Creates a nre task and push it into specified queue.
     *
     * This method exists for compatibility with version RoadRunner 1.x.
     *
     * @param string $name
     * @param array $payload
     * @param OptionsInterface|null $options
     * @return QueuedTaskInterface
     * @throws JobsException
     */
    public function push($name, $payload = [], $options = null)
    {
        return $this->dispatch(
            $this->create($name, $payload, $options)
        );
    }

    /**
     * {@inheritDoc}
     * @param \Spiral\RoadRunner\Jobs\Task\PreparedTaskInterface $task
     */
    public function dispatch($task)
    {
        return $this->pipeline->send($task);
    }

    /**
     * {@inheritDoc}
     * @param \Spiral\RoadRunner\Jobs\Task\PreparedTaskInterface ...$tasks
     */
    public function dispatchMany(...$tasks)
    {
        return $this->pipeline->sendMany($tasks);
    }

    /**
     * {@inheritDoc}
     */
    public function pause()
    {
        try {
            $this->rpc->call('jobs.Pause', new Pipelines([
                'pipelines' => [$this->getName()],
            ]));
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function resume()
    {
        try {
            $this->rpc->call('jobs.Resume', new Pipelines([
                'pipelines' => [$this->getName()],
            ]));
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isPaused()
    {
        $stat = $this->getPipelineStat();

        return $stat !== null && ! $stat->getReady();
    }

    private function createRPCConnection()
    {
        $env = Environment::fromGlobals();

        return RPC::create($env->getRPCAddress());
    }

    public function getPipelineStat()
    {
        try {
            /** @var Stats $stats */
            $stats = $this->rpc->call('jobs.Stat', '', Stats::class);
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        /** @var Stat $stat */
        foreach ($stats->getStats() as $stat) {
            if ($stat->getPipeline() === $this->name) {
                return $stat;
            }
        }

        return null;
    }
}
