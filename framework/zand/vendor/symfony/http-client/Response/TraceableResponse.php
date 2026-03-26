<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient\Response;

use Symfony\Component\HttpClient\Chunk\ErrorChunk;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\TraceableHttpClient;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class TraceableResponse implements ResponseInterface, StreamableInterface
{
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;
    /**
     * @var \Symfony\Contracts\HttpClient\ResponseInterface
     */
    private $response;
    /**
     * @var mixed
     */
    private $content;
    /**
     * @var \Symfony\Component\Stopwatch\StopwatchEvent|null
     */
    private $event;

    /**
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
     * @param \Symfony\Contracts\HttpClient\ResponseInterface $response
     * @param \Symfony\Component\Stopwatch\StopwatchEvent|null $event
     */
    public function __construct($client, $response, &$content, $event = null)
    {
        $this->client = $client;
        $this->response = $response;
        $this->content = &$content;
        $this->event = $event;
    }

    public function __sleep()
    {
        throw new \BadMethodCallException('Cannot serialize '.__CLASS__);
    }

    public function __wakeup()
    {
        throw new \BadMethodCallException('Cannot unserialize '.__CLASS__);
    }

    public function __destruct()
    {
        try {
            $this->response->__destruct();
        } finally {
            if (($event = $this->event) ? $event->isStarted() : null) {
                $this->event->stop();
            }
        }
    }

    public function getStatusCode()
    {
        try {
            return $this->response->getStatusCode();
        } finally {
            if (($event = $this->event) ? $event->isStarted() : null) {
                $this->event->lap();
            }
        }
    }

    /**
     * @param bool $throw
     */
    public function getHeaders($throw = true)
    {
        try {
            return $this->response->getHeaders($throw);
        } finally {
            if (($event = $this->event) ? $event->isStarted() : null) {
                $this->event->lap();
            }
        }
    }

    /**
     * @param bool $throw
     */
    public function getContent($throw = true)
    {
        try {
            if (false === $this->content) {
                return $this->response->getContent($throw);
            }

            return $this->content = $this->response->getContent(false);
        } finally {
            if (($event = $this->event) ? $event->isStarted() : null) {
                $this->event->stop();
            }
            if ($throw) {
                $this->checkStatusCode($this->response->getStatusCode());
            }
        }
    }

    /**
     * @param bool $throw
     */
    public function toArray($throw = true)
    {
        try {
            if (false === $this->content) {
                return $this->response->toArray($throw);
            }

            return $this->content = $this->response->toArray(false);
        } finally {
            if (($event = $this->event) ? $event->isStarted() : null) {
                $this->event->stop();
            }
            if ($throw) {
                $this->checkStatusCode($this->response->getStatusCode());
            }
        }
    }

    public function cancel()
    {
        $this->response->cancel();

        if (($event = $this->event) ? $event->isStarted() : null) {
            $this->event->stop();
        }
    }

    /**
     * @return mixed
     * @param string|null $type
     */
    public function getInfo($type = null)
    {
        return $this->response->getInfo($type);
    }

    /**
     * Casts the response to a PHP stream resource.
     *
     * @return resource
     *
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     * @param bool $throw
     */
    public function toStream($throw = true)
    {
        if ($throw) {
            // Ensure headers arrived
            $this->response->getHeaders(true);
        }

        if ($this->response instanceof StreamableInterface) {
            return $this->response->toStream(false);
        }

        return StreamWrapper::createResource($this->response, $this->client);
    }

    /**
     * @internal
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
     * @param mixed[] $responses
     * @param float|null $timeout
     */
    public static function stream($client, $responses, $timeout)
    {
        $wrappedResponses = [];
        $traceableMap = new \SplObjectStorage();

        foreach ($responses as $r) {
            if (!$r instanceof self) {
                throw new \TypeError(sprintf('"%s::stream()" expects parameter 1 to be an iterable of TraceableResponse objects, "%s" given.', TraceableHttpClient::class, get_debug_type($r)));
            }

            $traceableMap[$r->response] = $r;
            $wrappedResponses[] = $r->response;
            if ($r->event && !$r->event->isStarted()) {
                $r->event->start();
            }
        }

        foreach ($client->stream($wrappedResponses, $timeout) as $r => $chunk) {
            if ($traceableMap[$r]->event && $traceableMap[$r]->event->isStarted()) {
                try {
                    if ($chunk->isTimeout() || !$chunk->isLast()) {
                        $traceableMap[$r]->event->lap();
                    } else {
                        $traceableMap[$r]->event->stop();
                    }
                } catch (TransportExceptionInterface $e) {
                    $traceableMap[$r]->event->stop();
                    if ($chunk instanceof ErrorChunk) {
                        $chunk->didThrow(false);
                    } else {
                        $chunk = new ErrorChunk($chunk->getOffset(), $e);
                    }
                }
            }
            yield $traceableMap[$r] => $chunk;
        }
    }

    /**
     * @param int $code
     */
    private function checkStatusCode($code)
    {
        if (500 <= $code) {
            throw new ServerException($this);
        }

        if (400 <= $code) {
            throw new ClientException($this);
        }

        if (300 <= $code) {
            throw new RedirectionException($this);
        }
    }
}
