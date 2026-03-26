<?php

namespace Spiral\RoadRunner\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\RoadRunner\WorkerAwareInterface;

interface PSR7WorkerInterface extends WorkerAwareInterface
{
    /**
     * @return ServerRequestInterface|null
     */
    public function waitRequest();

    /**
     * Send response to the application server.
     *
     * @param ResponseInterface $response
     */
    public function respond($response);
}
