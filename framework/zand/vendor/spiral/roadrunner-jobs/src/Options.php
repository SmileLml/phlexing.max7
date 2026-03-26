<?php

namespace Spiral\RoadRunner\Jobs;

final class Options implements OptionsInterface
{
    /**
     * @var positive-int|0
     */
    public $delay = self::DEFAULT_DELAY;

    /**
     * @var positive-int|0
     */
    public $priority = self::DEFAULT_PRIORITY;

    /**
     * @var bool
     */
    public $autoAck = self::DEFAULT_AUTO_ACK;

    /**
     * @param positive-int|0 $delay
     * @param positive-int|0 $priority
     * @param bool $autoAck
     */
    public function __construct(
        $delay = self::DEFAULT_DELAY,
        $priority = self::DEFAULT_PRIORITY,
        $autoAck = self::DEFAULT_AUTO_ACK
    ) {
        assert($delay >= 0, 'Precondition [delay >= 0] failed');
        assert($priority >= 0, 'Precondition [priority >= 0] failed');

        $this->delay = $delay;
        $this->priority = $priority;
        $this->autoAck = $autoAck;
    }

    /**
     * @param OptionsInterface $options
     * @return static
     */
    public static function from($options)
    {
        return new self(
            $options->getDelay(),
            $options->getPriority(),
            $options->getAutoAck()
        );
    }

    /**
     * @psalm-immutable
     * @return positive-int|0
     */
    public function getDelay()
    {
        assert($this->delay >= 0, 'Invariant [delay >= 0] failed');

        return $this->delay;
    }

    /**
     * @psalm-immutable
     * @param positive-int|0 $delay
     * @return $this
     */
    public function withDelay($delay)
    {
        assert($delay >= 0, 'Precondition [delay >= 0] failed');

        $self = clone $this;
        $self->delay = $delay;

        return $self;
    }

    /**
     * @psalm-immutable
     * @return positive-int|0
     */
    public function getPriority()
    {
        assert($this->priority >= 0, 'Invariant [priority >= 0] failed');

        return $this->priority;
    }

    /**
     * @psalm-immutable
     * @param positive-int|0 $priority
     * @return $this
     */
    public function withPriority($priority)
    {
        assert($priority >= 0, 'Precondition [priority >= 0] failed');

        $self = clone $this;
        $self->priority = $priority;

        return $self;
    }

    /**
     * @psalm-immutable
     * @return bool
     */
    public function getAutoAck()
    {
        return $this->autoAck;
    }

    /**
     * @psalm-immutable
     * @param bool $autoAck
     * @return $this
     */
    public function withAutoAck($autoAck)
    {
        $self = clone $this;
        $self->autoAck = $autoAck;

        return $self;
    }

    /**
     * @param OptionsInterface|null $options
     * @return OptionsInterface
     */
    public function mergeOptional($options)
    {
        if ($options === null) {
            return $this;
        }

        return $this->merge($options);
    }

    /**
     * @param OptionsInterface $options
     * @return OptionsInterface
     */
    public function merge($options)
    {
        $self = clone $this;

        if (($delay = $options->getDelay()) !== self::DEFAULT_DELAY) {
            $self->delay = $delay;
        }

        if (($priority = $options->getPriority()) !== self::DEFAULT_PRIORITY) {
            $self->priority = $priority;
        }

        if (($autoAck = $options->getAutoAck()) !== self::DEFAULT_AUTO_ACK) {
            $self->autoAck = $autoAck;
        }

        return $self;
    }
}
