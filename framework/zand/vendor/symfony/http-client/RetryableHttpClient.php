<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Response\AsyncContext;
use Symfony\Component\HttpClient\Response\AsyncResponse;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;
use Symfony\Component\HttpClient\Retry\RetryStrategyInterface;
use Symfony\Contracts\HttpClient\ChunkInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Automatically retries failing HTTP requests.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class RetryableHttpClient implements HttpClientInterface, ResetInterface
{
    use AsyncDecoratorTrait;

    /**
     * @var \Symfony\Component\HttpClient\Retry\RetryStrategyInterface
     */
    private $strategy;
    /**
     * @var int
     */
    private $maxRetries;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var mixed[]
     */
    private $baseUris = [];

    /**
     * @param int $maxRetries The maximum number of times to retry
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
     * @param \Symfony\Component\HttpClient\Retry\RetryStrategyInterface|null $strategy
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct($client, $strategy = null, $maxRetries = 3, $logger = null)
    {
        $this->client = $client;
        $this->strategy = $strategy ?? new GenericRetryStrategy();
        $this->maxRetries = $maxRetries;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @return $this
     * @param mixed[] $options
     */
    public function withOptions($options)
    {
        if (\array_key_exists('base_uri', $options)) {
            if (\is_array($options['base_uri'])) {
                $this->baseUris = $options['base_uri'];
                unset($options['base_uri']);
            } else {
                $this->baseUris = [];
            }
        }

        $clone = clone $this;
        $clone->client = $this->client->withOptions($options);

        return $clone;
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed[] $options
     */
    public function request($method, $url, $options = [])
    {
        $baseUris = \array_key_exists('base_uri', $options) ? $options['base_uri'] : $this->baseUris;
        $baseUris = \is_array($baseUris) ? $baseUris : [];
        $options = self::shiftBaseUri($options, $baseUris);

        if ($this->maxRetries <= 0) {
            return new AsyncResponse($this->client, $method, $url, $options);
        }

        return new AsyncResponse($this->client, $method, $url, $options, function (ChunkInterface $chunk, AsyncContext $context) use ($method, $url, $options, &$baseUris) {
            static $retryCount = 0;
            static $content = '';
            static $firstChunk;

            $exception = null;
            try {
                if ($context->getInfo('canceled') || $chunk->isTimeout() || null !== $chunk->getInformationalStatus()) {
                    yield $chunk;

                    return;
                }
            } catch (TransportExceptionInterface $exception) {
                // catch TransportExceptionInterface to send it to the strategy
            }
            if (null !== $exception) {
                // always retry request that fail to resolve DNS
                if ('' !== $context->getInfo('primary_ip')) {
                    $shouldRetry = $this->strategy->shouldRetry($context, null, $exception);
                    if (null === $shouldRetry) {
                        throw new \LogicException(sprintf('The "%s::shouldRetry()" method must not return null when called with an exception.', get_class($this->strategy)));
                    }

                    if (false === $shouldRetry) {
                        yield from $this->passthru($context, $firstChunk, $content, $chunk);

                        return;
                    }
                }
            } elseif ($chunk->isFirst()) {
                if (false === $shouldRetry = $this->strategy->shouldRetry($context, null, null)) {
                    yield from $this->passthru($context, $firstChunk, $content, $chunk);

                    return;
                }

                // Body is needed to decide
                if (null === $shouldRetry) {
                    $firstChunk = $chunk;
                    $content = '';

                    return;
                }
            } else {
                if (!$chunk->isLast()) {
                    $content .= $chunk->getContent();

                    return;
                }

                if (null === $shouldRetry = $this->strategy->shouldRetry($context, $content, null)) {
                    throw new \LogicException(sprintf('The "%s::shouldRetry()" method must not return null when called with a body.', get_class($this->strategy)));
                }

                if (false === $shouldRetry) {
                    yield from $this->passthru($context, $firstChunk, $content, $chunk);

                    return;
                }
            }

            $context->getResponse()->cancel();

            $delay = $this->getDelayFromHeader($context->getHeaders()) ?? $this->strategy->getDelay($context, !$exception && $chunk->isLast() ? $content : null, $exception);
            ++$retryCount;
            $content = '';
            $firstChunk = null;

            $this->logger->info('Try #{count} after {delay}ms'.($exception ? ': '.$exception->getMessage() : ', status code: '.$context->getStatusCode()), [
                'count' => $retryCount,
                'delay' => $delay,
            ]);

            $context->setInfo('retry_count', $retryCount);
            $context->replaceRequest($method, $url, self::shiftBaseUri($options, $baseUris));
            $context->pause($delay / 1000);

            if ($retryCount >= $this->maxRetries) {
                $context->passthru();
            }
        });
    }

    /**
     * @param mixed[] $headers
     */
    private function getDelayFromHeader($headers)
    {
        if (null !== $after = $headers['retry-after'][0] ?? null) {
            if (is_numeric($after)) {
                return (int) ($after * 1000);
            }

            if (false !== $time = strtotime($after)) {
                return max(0, $time - time()) * 1000;
            }
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpClient\Response\AsyncContext $context
     * @param \Symfony\Contracts\HttpClient\ChunkInterface|null $firstChunk
     * @param string $content
     * @param \Symfony\Contracts\HttpClient\ChunkInterface $lastChunk
     */
    private function passthru($context, $firstChunk, &$content, $lastChunk)
    {
        $context->passthru();

        if (null !== $firstChunk) {
            yield $firstChunk;
        }

        if ('' !== $content) {
            $chunk = $context->createChunk($content);
            $content = '';

            yield $chunk;
        }

        yield $lastChunk;
    }

    /**
     * @param mixed[] $options
     * @param mixed[] $baseUris
     */
    private static function shiftBaseUri($options, &$baseUris)
    {
        if ($baseUris) {
            $baseUri = 1 < \count($baseUris) ? array_shift($baseUris) : current($baseUris);
            $options['base_uri'] = \is_array($baseUri) ? $baseUri[array_rand($baseUri)] : $baseUri;
        }

        return $options;
    }
}
