<?php

namespace Spiral\RoadRunner\Jobs\Task;

use Spiral\RoadRunner\Jobs\Options;
use Spiral\RoadRunner\Jobs\OptionsInterface;

/**
 * @psalm-suppress MissingImmutableAnnotation QueuedTask class is mutable.
 */
final class PreparedTask extends Task implements PreparedTaskInterface
{
    use WritableHeadersTrait;

    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * @param string $name
     * @param array $payload
     * @param OptionsInterface|null $options
     */
    public function __construct($name, $payload, $options = null)
    {
        $this->options = $options ?? new Options();

        parent::__construct($name, $payload);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->options = clone $this->options;
    }

    /**
     * @return OptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @return $this
     */
    public function withValue($value, $name = null)
    {
        $name = $name ?? $this->getPayloadNextIndex();
        assert(\is_string($name) || \is_int($name), 'Precondition [name is string|int] failed');

        $self = clone $this;
        $self->payload[$name] = $value;

        return $self;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @return $this
     */
    public function withoutValue($name)
    {
        assert(\is_string($name) || \is_int($name), 'Precondition [name is string|int] failed');

        $self = clone $this;
        unset($self->payload[$name]);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getDelay()
    {
        return $this->options->getDelay();
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @return $this
     * @param int $seconds
     */
    public function withDelay($seconds)
    {
        assert($seconds >= 0, 'Precondition [seconds >= 0] failed');

        $self = clone $this;
        $self->options = Options::from($this->options)
            ->withDelay($seconds)
        ;

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return $this->options->getPriority();
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @param int $priority
     */
    public function withPriority($priority)
    {
        assert($priority >= 0, 'Precondition [priority >= 0] failed');

        $self = clone $this;
        $self->options = Options::from($this->options)
            ->withPriority($priority)
        ;

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoAck()
    {
        return $this->options->getAutoAck();
    }

    /**
     * {@inheritDoc}
     * @param bool $autoAck
     */
    public function withAutoAck($autoAck)
    {
        $self = clone $this;
        $self->options = Options::from($this->options)
            ->withAutoAck($autoAck)
        ;

        return $self;
    }

    /**
     * @return int
     */
    private function getPayloadNextIndex()
    {
        /** @var array<int> $indices */
        $indices = \array_filter(\array_keys($this->getPayload()), '\\is_int');

        if ($indices === []) {
            return 0;
        }

        return \max($indices) + 1;
    }
}
