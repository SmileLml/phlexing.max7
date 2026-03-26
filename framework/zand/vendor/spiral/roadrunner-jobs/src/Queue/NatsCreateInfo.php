<?php

namespace Spiral\RoadRunner\Jobs\Queue;

/**
 * The DTO to create the NATS driver.
 *
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 */
final class NatsCreateInfo extends CreateInfo
{
    /**
     * @var positive-int
     */
    public const PREFETCH_DEFAULT_VALUE = 100;

    /**
     * @var bool
     */
    public const DELIVER_NEW_DEFAULT_VALUE = true;

    /**
     * @var positive-int
     */
    public const RATE_LIMIT_DEFAULT_VALUE = 100;

    /**
     * @var bool
     */
    public const DELETE_STREAM_ON_STOP_DEFAULT_VALUE = false;

    /**
     * @var bool
     */
    public const DELETE_AFTER_ACK_DEFAULT_VALUE = false;

    /**
     * @var positive-int
     */
    public const PRIORITY_DEFAULT_VALUE = 2;

    /**
     * @var positive-int
     */
    public $prefetch = self::PREFETCH_DEFAULT_VALUE;

    /**
     * @var non-empty-string
     */
    public $stream;

    /**
     * @var non-empty-string
     */
    public $subject;

    /**
     * @var bool
     */
    public $deliverNew = self::DELIVER_NEW_DEFAULT_VALUE;

    /**
     * @var positive-int
     */
    public $rateLimit = self::RATE_LIMIT_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public $deleteStreamOnStop = self::DELETE_STREAM_ON_STOP_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public $deleteAfterAck = self::DELETE_AFTER_ACK_DEFAULT_VALUE;

    /**
     * @var positive-int
     */
    public $priority = self::PRIORITY_DEFAULT_VALUE;

    /**
     * @param string $name
     * @param string $subject
     * @param string $stream
     * @param positive-int $priority
     * @param positive-int $prefetch
     * @param bool $deliverNew
     * @param positive-int $rateLimit
     * @param bool $deleteStreamOnStop
     * @param bool $deleteAfterAck
     */
    public function __construct(
        $name,
        $subject,
        $stream,
        $priority = self::PRIORITY_DEFAULT_VALUE,
        $prefetch = self::PREFETCH_DEFAULT_VALUE,
        $deliverNew = self::DELIVER_NEW_DEFAULT_VALUE,
        $rateLimit = self::RATE_LIMIT_DEFAULT_VALUE,
        $deleteStreamOnStop = self::DELETE_STREAM_ON_STOP_DEFAULT_VALUE,
        $deleteAfterAck = self::DELETE_AFTER_ACK_DEFAULT_VALUE
    ) {
        parent::__construct(Driver::NATS, $name, $priority);

        assert($prefetch >= 1, 'Precondition [prefetch >= 1] failed');
        assert($rateLimit >= 1, 'Precondition [rateLimit >= 1] failed');
        assert($subject !== '', 'Precondition [subject !== ""] failed');
        assert($stream !== '', 'Precondition [stream !== ""] failed');

        $this->stream = $stream;
        $this->prefetch = $prefetch;
        $this->subject = $subject;
        $this->deliverNew = $deliverNew;
        $this->rateLimit = $rateLimit;
        $this->deleteStreamOnStop = $deleteStreamOnStop;
        $this->deleteAfterAck = $deleteAfterAck;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return \array_merge(parent::toArray(), [
            'prefetch'              => $this->prefetch,
            'subject'               => $this->subject,
            'deliver_new'           => $this->deliverNew,
            'rate_limit'            => $this->rateLimit,
            'stream'                => $this->stream,
            'delete_stream_on_stop' => $this->deleteStreamOnStop,
            'delete_after_ack'      => $this->deleteAfterAck,
        ]);
    }
}
