<?php

namespace Ramsey\Uuid\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * Thrown to indicate that no suitable builder could be found
 */
class BuilderNotFoundException extends PhpRuntimeException implements UuidExceptionInterface
{
}
