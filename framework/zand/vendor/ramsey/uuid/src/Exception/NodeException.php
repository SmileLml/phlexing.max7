<?php

namespace Ramsey\Uuid\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * Thrown to indicate that attempting to fetch or create a node ID encountered an error
 */
class NodeException extends PhpRuntimeException implements UuidExceptionInterface
{
}
