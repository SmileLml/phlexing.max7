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

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Eases with writing decorators.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
trait DecoratorTrait
{
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;

    /**
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface|null $client
     */
    public function __construct($client = null)
    {
        $this->client = $client ?? HttpClient::create();
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed[] $options
     */
    public function request($method, $url, $options = [])
    {
        return $this->client->request($method, $url, $options);
    }

    /**
     * @param \Symfony\Contracts\HttpClient\ResponseInterface|mixed[] $responses
     * @param float|null $timeout
     */
    public function stream($responses, $timeout = null)
    {
        return $this->client->stream($responses, $timeout);
    }

    /**
     * @return $this
     * @param mixed[] $options
     */
    public function withOptions($options)
    {
        $clone = clone $this;
        $clone->client = $this->client->withOptions($options);

        return $clone;
    }

    /**
     * @return void
     */
    public function reset()
    {
        if ($this->client instanceof ResetInterface) {
            $this->client->reset();
        }
    }
}
