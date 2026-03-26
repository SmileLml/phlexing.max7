<?php

namespace Ramsey\Uuid\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * Thrown to indicate an exception occurred while dealing with DCE Security
 * (version 2) UUIDs
 */
class DceSecurityException extends PhpRuntimeException implements UuidExceptionInterface
{
}
