<?php

namespace Brick\Math\Exception;

/**
 * Exception thrown when a division by zero occurs.
 */
class DivisionByZeroException extends MathException
{
    /**
     * @psalm-pure
     */
    public static function divisionByZero()
    {
        return new self('Division by zero.');
    }

    /**
     * @psalm-pure
     */
    public static function modulusMustNotBeZero()
    {
        return new self('The modulus must not be zero.');
    }

    /**
     * @psalm-pure
     */
    public static function denominatorMustNotBeZero()
    {
        return new self('The denominator of a rational number cannot be zero.');
    }
}
