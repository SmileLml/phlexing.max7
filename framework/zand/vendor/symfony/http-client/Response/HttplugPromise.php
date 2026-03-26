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

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface as GuzzlePromiseInterface;
use Http\Promise\Promise as HttplugPromiseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
final class HttplugPromise implements HttplugPromiseInterface
{
    /**
     * @var GuzzlePromiseInterface
     */
    private $promise;

    /**
     * @param GuzzlePromiseInterface $promise
     */
    public function __construct($promise)
    {
        $this->promise = $promise;
    }

    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        return new self($this->promise->then(
            $this->wrapThenCallback($onFulfilled),
            $this->wrapThenCallback($onRejected)
        ));
    }

    public function cancel()
    {
        $this->promise->cancel();
    }

    public function getState()
    {
        return $this->promise->getState();
    }

    /**
     * @return Psr7ResponseInterface|mixed
     */
    public function wait($unwrap = true)
    {
        $result = $this->promise->wait($unwrap);

        while ($result instanceof HttplugPromiseInterface || $result instanceof GuzzlePromiseInterface) {
            $result = $result->wait($unwrap);
        }

        return $result;
    }

    /**
     * @param callable|null $callback
     */
    private function wrapThenCallback($callback)
    {
        if (null === $callback) {
            return null;
        }

        return static function ($value) use ($callback) {
            return Create::promiseFor($callback($value));
        };
    }
}
