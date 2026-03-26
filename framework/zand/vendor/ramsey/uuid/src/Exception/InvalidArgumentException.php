<?php

namespace Ramsey\Uuid\Exception;

use InvalidArgumentException as PhpInvalidArgumentException;

/**
 * Thrown to indicate that the argument received is not valid
 */
class InvalidArgumentException extends PhpInvalidArgumentException implements UuidExceptionInterface
{
}
