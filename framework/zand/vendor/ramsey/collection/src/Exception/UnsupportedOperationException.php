<?php

namespace Ramsey\Collection\Exception;

use RuntimeException;

/**
 * Thrown to indicate that the requested operation is not supported.
 */
class UnsupportedOperationException extends RuntimeException implements CollectionException
{
}
