<?php

namespace Spiral\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * Simply forwards debug messages into various locations.
 */
final class NullLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @readonly
     * @var \Closure
     */
    private $receptor;
    /**
     * @var string
     */
    private $channel;

    /**
     * @param callable $receptor
     * @param string $channel
     */
    public function __construct(
        $receptor,
        $channel
    ) {
        $this->channel = $channel;
        $this->receptor = \Closure::fromCallable($receptor);
    }

    /**
     * @param mixed $level
     * @param mixed[] $context
     */
    public function log($level, $message, $context = [])
    {
        \call_user_func($this->receptor, $this->channel, $level, $message, $context);
    }
}
