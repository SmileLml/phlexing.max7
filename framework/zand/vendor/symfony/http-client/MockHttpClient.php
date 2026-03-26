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

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Response\ResponseStream;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * A test-friendly HttpClient that doesn't make actual HTTP requests.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class MockHttpClient implements HttpClientInterface, ResetInterface
{
    use HttpClientTrait;

    /**
     * @var \Symfony\Contracts\HttpClient\ResponseInterface|\Closure|mixed[]|null
     */
    private $responseFactory;
    /**
     * @var int
     */
    private $requestsCount = 0;
    /**
     * @var mixed[]
     */
    private $defaultOptions = [];

    /**
     * @param callable|mixed[]|\Symfony\Contracts\HttpClient\ResponseInterface $responseFactory
     * @param string|null $baseUri
     */
    public function __construct($responseFactory = null, $baseUri = 'https://example.com')
    {
        $this->setResponseFactory($responseFactory);
        $this->defaultOptions['base_uri'] = $baseUri;
    }

    /**
     * @param callable|callable[]|ResponseInterface|ResponseInterface[]|iterable|null $responseFactory
     */
    public function setResponseFactory($responseFactory)
    {
        if ($responseFactory instanceof ResponseInterface) {
            $responseFactory = [$responseFactory];
        }

        if (!$responseFactory instanceof \Iterator && null !== $responseFactory && !\is_callable($responseFactory)) {
            $responseFactory = (static function () use ($responseFactory) {
                yield from $responseFactory;
            })();
        }

        $this->responseFactory = !\is_callable($responseFactory) ? $responseFactory : \Closure::fromCallable($responseFactory);
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed[] $options
     */
    public function request($method, $url, $options = [])
    {
        [$url, $options] = $this->prepareRequest($method, $url, $options, $this->defaultOptions, true);
        $url = implode('', $url);

        if (null === $this->responseFactory) {
            $response = new MockResponse();
        } elseif (\is_callable($this->responseFactory)) {
            $response = ($this->responseFactory)($method, $url, $options);
        } elseif (!$this->responseFactory->valid()) {
            throw new TransportException($this->requestsCount ? 'No more response left in the response factory iterator passed to MockHttpClient: the number of requests exceeds the number of responses.' : 'The response factory iterator passed to MockHttpClient is empty.');
        } else {
            $responseFactory = $this->responseFactory->current();
            $response = \is_callable($responseFactory) ? $responseFactory($method, $url, $options) : $responseFactory;
            $this->responseFactory->next();
        }
        ++$this->requestsCount;

        if (!$response instanceof ResponseInterface) {
            throw new TransportException(sprintf('The response factory passed to MockHttpClient must return/yield an instance of ResponseInterface, "%s" given.', get_debug_type($response)));
        }

        return MockResponse::fromRequest($method, $url, $options, $response);
    }

    /**
     * @param \Symfony\Contracts\HttpClient\ResponseInterface|mixed[] $responses
     * @param float|null $timeout
     */
    public function stream($responses, $timeout = null)
    {
        if ($responses instanceof ResponseInterface) {
            $responses = [$responses];
        }

        return new ResponseStream(MockResponse::stream($responses, $timeout));
    }

    public function getRequestsCount()
    {
        return $this->requestsCount;
    }

    /**
     * @return $this
     * @param mixed[] $options
     */
    public function withOptions($options)
    {
        $clone = clone $this;
        $clone->defaultOptions = self::mergeDefaultOptions($options, $this->defaultOptions, true);

        return $clone;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->requestsCount = 0;
    }
}
