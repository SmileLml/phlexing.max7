<?php

namespace Brick\Math\Exception;

use Brick\Math\BigInteger;

/**
 * Exception thrown when an integer overflow occurs.
 */
class IntegerOverflowException extends MathException
{
    /**
     * @psalm-pure
     * @param \Brick\Math\BigInteger $value
     */
    public static function toIntOverflow($value)
    {
        $message = '%s is out of range %d to %d and cannot be represented as an integer.';

        return new self(\sprintf($message, (string) $value, PHP_INT_MIN, PHP_INT_MAX));
    }
}
