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
use Symfony\Contracts\Service\ResetInterface;

class UriTemplateHttpClient implements HttpClientInterface, ResetInterface
{
    use DecoratorTrait;
    /**
     * @var \Closure(string $url, array $vars):string|null
     */
    private $expander;
    /**
     * @var mixed[]
     */
    private $defaultVars = [];

    /**
     * @param (\Closure(string $url, array $vars): string)|null $expander
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface|null $client
     * @param mixed[] $defaultVars
     */
    public function __construct($client = null, $expander = null, $defaultVars = [])
    {
        $this->expander = $expander;
        $this->defaultVars = $defaultVars;
        $this->client = $client ?? HttpClient::create();
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed[] $options
     */
    public function request($method, $url, $options = [])
    {
        $vars = $this->defaultVars;

        if (\array_key_exists('vars', $options)) {
            if (!\is_array($options['vars'])) {
                throw new \InvalidArgumentException('The "vars" option must be an array.');
            }
            $item1Unpacked = $options['vars'];

            $vars = array_merge($vars, $item1Unpacked);
            unset($options['vars']);
        }

        if ($vars) {
            $url = ($this->expander = $this->expander ?? $this->createExpanderFromPopularVendors())($url, $vars);
        }

        return $this->client->request($method, $url, $options);
    }

    /**
     * @return $this
     * @param mixed[] $options
     */
    public function withOptions($options)
    {
        if (!\is_array($options['vars'] ?? [])) {
            throw new \InvalidArgumentException('The "vars" option must be an array.');
        }

        $clone = clone $this;
        $item0Unpacked = $clone->defaultVars;
        $item1Unpacked = $options['vars'] ?? [];
        $clone->defaultVars = array_merge($item0Unpacked, $item1Unpacked);
        unset($options['vars']);

        $clone->client = $this->client->withOptions($options);

        return $clone;
    }

    /**
     * @return \Closure(string $url, array $vars): string
     */
    private function createExpanderFromPopularVendors()
    {
        if (class_exists(\GuzzleHttp\UriTemplate\UriTemplate::class)) {
            return \Closure::fromCallable([\GuzzleHttp\UriTemplate\UriTemplate::class, 'expand']);
        }

        if (class_exists(\League\Uri\UriTemplate::class)) {
            return static function (string $url, array $vars) : string {
                return (new \League\Uri\UriTemplate($url))->expand($vars);
            };
        }

        if (class_exists(\Rize\UriTemplate::class)) {
            return \Closure::fromCallable([new \Rize\UriTemplate(), 'expand']);
        }

        throw new \LogicException('Support for URI template requires a vendor to expand the URI. Run "composer require guzzlehttp/uri-template" or pass your own expander \Closure implementation.');
    }
}
