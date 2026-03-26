<?php

namespace Ramsey\Collection\Exception;

use RuntimeException;

/**
 * Thrown when attempting to access an element that does not exist.
 */
class NoSuchElementException extends RuntimeException implements CollectionException
{
}
