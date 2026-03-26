<?php

namespace Ramsey\Collection\Exception;

use OutOfBoundsException as PhpOutOfBoundsException;

/**
 * Thrown when attempting to access an element out of the range of the collection.
 */
class OutOfBoundsException extends PhpOutOfBoundsException implements CollectionException
{
}
