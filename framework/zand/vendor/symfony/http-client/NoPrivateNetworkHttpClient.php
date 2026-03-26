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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Decorator that blocks requests to private networks by default.
 *
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
final class NoPrivateNetworkHttpClient implements HttpClientInterface, LoggerAwareInterface, ResetInterface
{
    use HttpClientTrait;

    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;
    /**
     * @var string|mixed[]|null
     */
    private $subnets;

    /**
     * @param string|mixed[] $subnets String or array of subnets using CIDR notation that will be used by IpUtils.
                                 If null is passed, the standard private subnets will be used.
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
    */
    public function __construct($client, $subnets = null)
    {
        if (!class_exists(IpUtils::class)) {
            throw new \LogicException(sprintf('You cannot use "%s" if the HttpFoundation component is not installed. Try running "composer require symfony/http-foundation".', __CLASS__));
        }

        $this->client = $client;
        $this->subnets = $subnets;
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed[] $options
     */
    public function request($method, $url, $options = [])
    {
        $onProgress = $options['on_progress'] ?? null;
        if (null !== $onProgress && !\is_callable($onProgress)) {
            throw new InvalidArgumentException(sprintf('Option "on_progress" must be callable, "%s" given.', get_debug_type($onProgress)));
        }

        $subnets = $this->subnets;

        $options['on_progress'] = function (int $dlNow, int $dlSize, array $info) use ($onProgress, $subnets): void {
            static $lastPrimaryIp = '';
            if ($info['primary_ip'] !== $lastPrimaryIp) {
                if ($info['primary_ip'] && IpUtils::checkIp($info['primary_ip'], $subnets ?? IpUtils::PRIVATE_SUBNETS)) {
                    throw new TransportException(sprintf('IP "%s" is blocked for "%s".', $info['primary_ip'], $info['url']));
                }

                $lastPrimaryIp = $info['primary_ip'];
            }

            null !== $onProgress && $onProgress($dlNow, $dlSize, $info);
        };

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
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        if ($this->client instanceof LoggerAwareInterface) {
            $this->client->setLogger($logger);
        }
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

    public function reset()
    {
        if ($this->client instanceof ResetInterface) {
            $this->client->reset();
        }
    }
}
