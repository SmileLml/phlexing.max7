<?php

namespace Spiral\RoadRunner\Jobs\Queue;

use JetBrains\PhpStorm\ArrayShape;

/**
 * The DTO to create the SQS driver.
 *
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 * @psalm-type SQSAttributesMap = array {
 *      DelaySeconds?: positive-int|0,
 *      MaximumMessageSize?: positive-int|0,
 *      MessageRetentionPeriod?: positive-int|0,
 *      Policy?: mixed,
 *      ReceiveMessageWaitTimeSeconds?: positive-int|0,
 *      RedrivePolicy?: array {
 *          deadLetterTargetArn?: mixed,
 *          maxReceiveCount: positive-int|0
 *      },
 *      VisibilityTimeout?: positive-int|0,
 *      KmsMasterKeyId?: string,
 *      KmsDataKeyReusePeriodSeconds?: positive-int|0,
 *      ContentBasedDeduplication?: mixed,
 *      DeduplicationScope?: mixed,
 *      FifoThroughputLimit?: mixed,
 * }
 */
final class SQSCreateInfo extends CreateInfo
{
    /**
     * @var positive-int
     */
    public const PREFETCH_DEFAULT_VALUE = 10;

    /**
     * @var positive-int|0
     */
    public const VISIBILITY_TIMEOUT_DEFAULT_VALUE = 0;

    /**
     * @var positive-int|0
     */
    public const WAIT_TIME_SECONDS_DEFAULT_VALUE = 0;

    /**
     * @var array
     */
    public const ATTRIBUTES_DEFAULT_VALUE = [];

    /**
     * @var array
     */
    public const TAGS_DEFAULT_VALUE = [];

    /**
     * @var non-empty-string
     */
    public const QUEUE_DEFAULT_VALUE = 'default';

    /**
     * @var positive-int
     */
    public $prefetch = self::PREFETCH_DEFAULT_VALUE;

    /**
     * @var positive-int|0
     */
    public $visibilityTimeout = self::VISIBILITY_TIMEOUT_DEFAULT_VALUE;

    /**
     * @var positive-int|0
     */
    public $waitTimeSeconds = self::WAIT_TIME_SECONDS_DEFAULT_VALUE;

    /**
     * @var non-empty-string
     */
    public $queue = self::QUEUE_DEFAULT_VALUE;

    /**
     * @var array|SQSAttributesMap
     */
    public $attributes = self::ATTRIBUTES_DEFAULT_VALUE;

    /**
     * @var array<non-empty-string, non-empty-string>
     */
    public $tags = self::TAGS_DEFAULT_VALUE;

    /**
     * @param string $name
     * @param positive-int $priority
     * @param positive-int $prefetch
     * @param positive-int|0 $visibilityTimeout
     * @param positive-int|0 $waitTimeSeconds
     * @param string $queue
     * @param mixed[] $attributes
     * @param array<non-empty-string, non-empty-string> $tags
     */
    public function __construct(
        $name,
        $priority = self::PRIORITY_DEFAULT_VALUE,
        $prefetch = self::PREFETCH_DEFAULT_VALUE,
        $visibilityTimeout = self::VISIBILITY_TIMEOUT_DEFAULT_VALUE,
        $waitTimeSeconds = self::WAIT_TIME_SECONDS_DEFAULT_VALUE,
        $queue = self::QUEUE_DEFAULT_VALUE,
        $attributes = self::ATTRIBUTES_DEFAULT_VALUE,
        $tags = self::TAGS_DEFAULT_VALUE
    ) {
        parent::__construct(Driver::SQS, $name, $priority);

        assert($prefetch >= 1, 'Precondition [prefetch >= 1] failed');
        assert($visibilityTimeout >= 0, 'Precondition [visibilityTimeout >= 0] failed');
        assert($waitTimeSeconds >= 0, 'Precondition [waitTimeSeconds >= 0] failed');
        assert($queue !== '', 'Precondition [queue !== ""] failed');

        $this->prefetch = $prefetch;
        $this->visibilityTimeout = $visibilityTimeout;
        $this->waitTimeSeconds = $waitTimeSeconds;
        $this->queue = $queue;
        $this->attributes = $attributes;
        $this->tags = $tags;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return \array_merge(parent::toArray(), [
            'prefetch'           => $this->prefetch,
            'visibility_timeout' => $this->visibilityTimeout,
            'wait_time_seconds'  => $this->waitTimeSeconds,
            'queue'              => $this->queue,
            'attributes'         => $this->attributes,
            'tags'               => $this->tags,
        ]);
    }
}
