<?php

namespace Ramsey\Collection\Exception;

use RuntimeException;

/**
 * Thrown when attempting to evaluate a property, method, or array key
 * that doesn't exist on an element or cannot otherwise be evaluated in the
 * current context.
 */
class InvalidPropertyOrMethod extends RuntimeException implements CollectionException
{
}
