<?php

namespace Spiral\RoadRunner\Jobs\Queue;

use Spiral\RoadRunner\Jobs\Queue\AMQP\ExchangeType;

/**
 * The DTO to create the AMQP driver.
 *
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 * @psalm-import-type ExchangeTypeEnum from ExchangeType
 *
 * @see ExchangeType
 */
final class AMQPCreateInfo extends CreateInfo
{
    /**
     * @var positive-int
     */
    public const PREFETCH_DEFAULT_VALUE = 100;

    /**
     * @var non-empty-string
     */
    public const QUEUE_DEFAULT_VALUE = 'default';

    /**
     * @var non-empty-string
     */
    public const EXCHANGE_DEFAULT_VALUE = 'amqp.default';

    /**
     * @var ExchangeTypeEnum
     */
    public const EXCHANGE_TYPE_DEFAULT_VALUE = ExchangeType::TYPE_DIRECT;

    /**
     * @var string
     */
    public const ROUTING_KEY_DEFAULT_VALUE = '';

    /**
     * @var bool
     */
    public const EXCLUSIVE_DEFAULT_VALUE = false;

    /**
     * @var bool
     */
    public const MULTIPLE_ACK_DEFAULT_VALUE = false;

    /**
     * @var bool
     */
    public const REQUEUE_ON_FAIL_DEFAULT_VALUE = false;

    /**
     * @var bool
     */
    public const DURABLE_DEFAULT_VALUE = false;

    /**
     * @var positive-int
     */
    public $prefetch = self::PREFETCH_DEFAULT_VALUE;

    /**
     * @var non-empty-string
     */
    public $queue = self::QUEUE_DEFAULT_VALUE;

    /**
     * @var non-empty-string
     */
    public $exchange = self::EXCHANGE_DEFAULT_VALUE;

    /**
     * @var ExchangeTypeEnum
     */
    public $exchangeType = self::EXCHANGE_TYPE_DEFAULT_VALUE;

    /**
     * @var string
     */
    public $routingKey = self::ROUTING_KEY_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public $exclusive = self::EXCLUSIVE_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public $multipleAck = self::MULTIPLE_ACK_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public $requeueOnFail = self::REQUEUE_ON_FAIL_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public $durable = self::DURABLE_DEFAULT_VALUE;

    /**
     * @param string $name
     * @param positive-int $priority
     * @param positive-int $prefetch
     * @param string $queue
     * @param string $exchange
     * @param string $exchangeType
     * @param string $routingKey
     * @param bool $exclusive
     * @param bool $multipleAck
     * @param bool $requeueOnFail
     * @param bool $durable
     */
    public function __construct(
        $name,
        $priority = self::PRIORITY_DEFAULT_VALUE,
        $prefetch = self::PREFETCH_DEFAULT_VALUE,
        $queue = self::QUEUE_DEFAULT_VALUE,
        $exchange = self::EXCHANGE_DEFAULT_VALUE,
        $exchangeType = self::EXCHANGE_TYPE_DEFAULT_VALUE,
        $routingKey = self::ROUTING_KEY_DEFAULT_VALUE,
        $exclusive = self::EXCLUSIVE_DEFAULT_VALUE,
        $multipleAck = self::MULTIPLE_ACK_DEFAULT_VALUE,
        $requeueOnFail = self::REQUEUE_ON_FAIL_DEFAULT_VALUE,
        $durable = self::DURABLE_DEFAULT_VALUE
    ) {
        parent::__construct(Driver::AMQP, $name, $priority);

        assert($prefetch >= 1, 'Precondition [prefetch >= 1] failed');
        assert($queue !== '', 'Precondition [queue !== ""] failed');
        assert($exchange !== '', 'Precondition [exchange !== ""] failed');
        assert($exchangeType !== '', 'Precondition [exchangeType !== ""] failed');

        $this->prefetch = $prefetch;
        $this->queue = $queue;
        $this->exchange = $exchange;
        $this->exchangeType = $exchangeType;
        $this->routingKey = $routingKey;
        $this->exclusive = $exclusive;
        $this->multipleAck = $multipleAck;
        $this->requeueOnFail = $requeueOnFail;
        $this->durable = $durable;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return \array_merge(parent::toArray(), [
            'prefetch'        => $this->prefetch,
            'queue'           => $this->queue,
            'exchange'        => $this->exchange,
            'exchange_type'   => $this->exchangeType,
            'routing_key'     => $this->routingKey,
            'exclusive'       => $this->exclusive,
            'multiple_ack'    => $this->multipleAck,
            'requeue_on_fail' => $this->requeueOnFail,
            'durable'         => $this->durable,
        ]);
    }
}
