<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient\Internal;

use Symfony\Component\HttpClient\Response\CurlResponse;

/**
 * A pushed response with its request headers.
 *
 * @author Alexander M. Turek <me@derrabus.de>
 *
 * @internal
 */
final class PushedResponse
{
    /**
     * @var \Symfony\Component\HttpClient\Response\CurlResponse
     */
    public $response;

    /** @var string[] */
    public $requestHeaders;

    /**
     * @var mixed[]
     */
    public $parentOptions = [];

    public $handle;

    /**
     * @param \Symfony\Component\HttpClient\Response\CurlResponse $response
     * @param mixed[] $requestHeaders
     * @param mixed[] $parentOptions
     */
    public function __construct($response, $requestHeaders, $parentOptions, $handle)
    {
        $this->response = $response;
        $this->requestHeaders = $requestHeaders;
        $this->parentOptions = $parentOptions;
        $this->handle = $handle;
    }
}
