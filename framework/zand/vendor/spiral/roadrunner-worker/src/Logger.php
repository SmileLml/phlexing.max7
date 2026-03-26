<?php

namespace Spiral\RoadRunner;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * {@inheritDoc}
     * @psalm-suppress RedundantConditionGivenDocblockType
     * @param mixed[] $context
     */
    public function log($level, $message, $context = [])
    {
        assert(\is_scalar($level), 'Invalid log level type');
        assert(\is_string($message), 'Invalid log message type');

        $this->write($this->format((string)$level, $message, $context));
    }

    /**
     * @param string $message
     */
    protected function write($message)
    {
        \file_put_contents('php://stderr', $message);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function format($level, $message, $context = [])
    {
        return \sprintf('[php %s] %s %s', $level, $message, $this->formatContext($context));
    }

    /**
     * @param array $context
     * @return string
     */
    protected function formatContext($context)
    {
        try {
            return \json_encode($context, 0);
        } catch (\JsonException $_) {
            return \print_r($context, true);
        }
    }
}
