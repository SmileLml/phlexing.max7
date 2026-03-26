<?php

namespace Ramsey\Uuid\Exception;

use LogicException as PhpLogicException;

/**
 * Thrown to indicate that the requested operation is not supported
 */
class UnsupportedOperationException extends PhpLogicException implements UuidExceptionInterface
{
}
