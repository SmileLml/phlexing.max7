<?php

namespace Spiral\Logger;

use Psr\Log\LoggerInterface;

/**
 * LogsInterface is generic log factory interface.
 */
interface LogsInterface
{
    /**
     * Get pre-configured logger instance.
     * @param string $channel
     */
    public function getLogger($channel);
}
