<?php

namespace Ramsey\Uuid\Converter;

/**
 * A number converter converts UUIDs from hexadecimal characters into
 * representations of integers and vice versa
 *
 * @psalm-immutable
 */
interface NumberConverterInterface
{
    /**
     * Converts a hexadecimal number into an string integer representation of
     * the number
     *
     * The integer representation returned is a string representation of the
     * integer, to accommodate unsigned integers greater than PHP_INT_MAX.
     *
     * @param string $hex The hexadecimal string representation to convert
     *
     * @return string String representation of an integer
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public function fromHex($hex);

    /**
     * Converts a string integer representation into a hexadecimal string
     * representation of the number
     *
     * @param string $number A string integer representation to convert; this
     *     must be a numeric string to accommodate unsigned integers greater
     *     than PHP_INT_MAX.
     *
     * @return string Hexadecimal string
     *
     * @psalm-return non-empty-string
     *
     * @psalm-pure
     */
    public function toHex($number);
}
