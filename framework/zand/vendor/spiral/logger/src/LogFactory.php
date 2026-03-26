<?php

namespace Spiral\Logger;

use Psr\Log\LoggerInterface;
use Spiral\Logger\Event\LogEvent;

/**
 * Routes log information to various listeners.
 */
final class LogFactory implements LogsInterface
{
    /**
     * @readonly
     * @var \Spiral\Logger\ListenerRegistryInterface
     */
    private $listenedRegistry;
    /**
     * @param \Spiral\Logger\ListenerRegistryInterface $listenedRegistry
     */
    public function __construct($listenedRegistry)
    {
        $this->listenedRegistry = $listenedRegistry;
    }

    /**
     * @param string $channel
     */
    public function getLogger($channel)
    {
        return new NullLogger([$this, 'log'], $channel);
    }

    /**
     * @param mixed $level
     * @param string $channel
     * @param string $message
     * @param mixed[] $context
     */
    public function log($channel, $level, $message, $context = [])
    {
        $e = new LogEvent(
            new \DateTime(),
            $channel,
            (string) $level,
            $message,
            $context
        );

        foreach ($this->listenedRegistry->getListeners() as $listener) {
            \call_user_func($listener, $e);
        }
    }
}
