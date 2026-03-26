<?php

namespace Ramsey\Uuid\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * Thrown to indicate that the bytes being operated on are invalid in some way
 */
class InvalidBytesException extends PhpRuntimeException implements UuidExceptionInterface
{
}
