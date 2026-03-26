<?php

namespace Ramsey\Collection\Exception;

use RuntimeException;

/**
 * Thrown when attempting to operate on collections of differing types.
 */
class CollectionMismatchException extends RuntimeException implements CollectionException
{
}
