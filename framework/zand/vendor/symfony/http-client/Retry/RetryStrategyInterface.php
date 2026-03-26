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

use Symfony\Component\HttpClient\Response\AsyncContext;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
interface RetryStrategyInterface
{
    /**
     * Returns whether the request should be retried.
     *
     * @param ?string $responseContent Null is passed when the body did not arrive yet
     *
     * @return bool|null Returns null to signal that the body is required to take a decision
     * @param \Symfony\Component\HttpClient\Response\AsyncContext $context
     * @param \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface|null $exception
     */
    public function shouldRetry($context, $responseContent, $exception);

    /**
     * Returns the time to wait in milliseconds.
     * @param \Symfony\Component\HttpClient\Response\AsyncContext $context
     * @param string|null $responseContent
     * @param \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface|null $exception
     */
    public function getDelay($context, $responseContent, $exception);
}
