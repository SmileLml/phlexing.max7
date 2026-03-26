<?php

namespace Ramsey\Collection\Exception;

use InvalidArgumentException as PhpInvalidArgumentException;

/**
 * Thrown to indicate an argument is not of the expected type.
 */
class InvalidArgumentException extends PhpInvalidArgumentException implements CollectionException
{
}
