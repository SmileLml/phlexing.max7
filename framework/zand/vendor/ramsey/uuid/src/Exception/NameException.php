<?php

namespace Ramsey\Uuid\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * Thrown to indicate that an error occurred while attempting to hash a
 * namespace and name
 */
class NameException extends PhpRuntimeException implements UuidExceptionInterface
{
}
