<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient\Retry;

use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpClient\Response\AsyncContext;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Decides to retry the request when HTTP status codes belong to the given list of codes.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class GenericRetryStrategy implements RetryStrategyInterface
{
    public const IDEMPOTENT_METHODS = ['GET', 'HEAD', 'PUT', 'DELETE', 'OPTIONS', 'TRACE'];
    public const DEFAULT_RETRY_STATUS_CODES = [
        0 => self::IDEMPOTENT_METHODS, // for transport exceptions
        423,
        425,
        429,
        500 => self::IDEMPOTENT_METHODS,
        502,
        503,
        504 => self::IDEMPOTENT_METHODS,
        507 => self::IDEMPOTENT_METHODS,
        510 => self::IDEMPOTENT_METHODS,
    ];

    /**
     * @var mixed[]
     */
    private $statusCodes;
    /**
     * @var int
     */
    private $delayMs;
    /**
     * @var float
     */
    private $multiplier;
    /**
     * @var int
     */
    private $maxDelayMs;
    /**
     * @var float
     */
    private $jitter;

    /**
     * @param array $statusCodes List of HTTP status codes that trigger a retry
     * @param int   $delayMs     Amount of time to delay (or the initial value when multiplier is used)
     * @param float $multiplier  Multiplier to apply to the delay each time a retry occurs
     * @param int   $maxDelayMs  Maximum delay to allow (0 means no maximum)
     * @param float $jitter      Probability of randomness int delay (0 = none, 1 = 100% random)
     */
    public function __construct($statusCodes = self::DEFAULT_RETRY_STATUS_CODES, $delayMs = 1000, $multiplier = 2.0, $maxDelayMs = 0, $jitter = 0.1)
    {
        $this->statusCodes = $statusCodes;

        if ($delayMs < 0) {
            throw new InvalidArgumentException(sprintf('Delay must be greater than or equal to zero: "%s" given.', $delayMs));
        }
        $this->delayMs = $delayMs;

        if ($multiplier < 1) {
            throw new InvalidArgumentException(sprintf('Multiplier must be greater than or equal to one: "%s" given.', $multiplier));
        }
        $this->multiplier = $multiplier;

        if ($maxDelayMs < 0) {
            throw new InvalidArgumentException(sprintf('Max delay must be greater than or equal to zero: "%s" given.', $maxDelayMs));
        }
        $this->maxDelayMs = $maxDelayMs;

        if ($jitter < 0 || $jitter > 1) {
            throw new InvalidArgumentException(sprintf('Jitter must be between 0 and 1: "%s" given.', $jitter));
        }
        $this->jitter = $jitter;
    }

    /**
     * @param \Symfony\Component\HttpClient\Response\AsyncContext $context
     * @param string|null $responseContent
     * @param \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface|null $exception
     */
    public function shouldRetry($context, $responseContent, $exception)
    {
        $statusCode = $context->getStatusCode();
        if (\in_array($statusCode, $this->statusCodes, true)) {
            return true;
        }
        if (isset($this->statusCodes[$statusCode]) && \is_array($this->statusCodes[$statusCode])) {
            return \in_array($context->getInfo('http_method'), $this->statusCodes[$statusCode], true);
        }
        if (null === $exception) {
            return false;
        }

        if (\in_array(0, $this->statusCodes, true)) {
            return true;
        }
        if (isset($this->statusCodes[0]) && \is_array($this->statusCodes[0])) {
            return \in_array($context->getInfo('http_method'), $this->statusCodes[0], true);
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpClient\Response\AsyncContext $context
     * @param string|null $responseContent
     * @param \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface|null $exception
     */
    public function getDelay($context, $responseContent, $exception)
    {
        $delay = $this->delayMs * $this->multiplier ** $context->getInfo('retry_count');

        if ($this->jitter > 0) {
            $randomness = (int) ($delay * $this->jitter);
            $delay = $delay + random_int(-$randomness, +$randomness);
        }

        if ($delay > $this->maxDelayMs && 0 !== $this->maxDelayMs) {
            return $this->maxDelayMs;
        }

        return (int) $delay;
    }
}
