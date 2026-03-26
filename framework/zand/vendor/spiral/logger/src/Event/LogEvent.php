<?php

namespace Spiral\Logger\Event;

final class LogEvent
{
    /**
     * @readonly
     * @var \DateTimeInterface
     */
    private $time;
    /**
     * @readonly
     * @var string
     */
    private $channel;
    /**
     * @readonly
     * @var string
     */
    private $level;
    /**
     * @readonly
     * @var string
     */
    private $message;
    /**
     * @readonly
     * @var mixed[]
     */
    private $context = [];
    /**
     * @param \DateTimeInterface $time
     * @param string $channel
     * @param string $level
     * @param string $message
     * @param mixed[] $context
     */
    public function __construct($time, $channel, $level, $message, $context = [])
    {
        $this->time = $time;
        $this->channel = $channel;
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getContext()
    {
        return $this->context;
    }
}
