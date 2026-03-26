<?php

namespace Spiral\RoadRunner;

use JetBrains\PhpStorm\Immutable;

final class Payload
{
    /**
     * Execution payload (binary).
     *
     * @psalm-readonly
     * @var string
     */
    public $body = '';
    /**
     * Execution context (binary).
     *
     * @psalm-readonly
     * @var string
     */
    public $header = '';
    /**
     * End of stream.
     * The {@see true} value means the Payload block is last in the stream.
     *
     * @psalm-readonly
     * @var bool
     */
    public $eos = true;
    /**
     * @param string|null $body
     * @param string|null $header
     * @param bool $eos
     */
    public function __construct($body, $header = null, $eos = true)
    {
        $this->body = $body ?? '';
        $this->header = $header ?? '';
        $this->eos = $eos;
    }
}
