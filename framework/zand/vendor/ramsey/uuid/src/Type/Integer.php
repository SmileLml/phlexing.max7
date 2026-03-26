<?php

namespace Ramsey\Uuid\Type;

use Ramsey\Uuid\Exception\InvalidArgumentException;
use ValueError;

use function assert;
use function is_numeric;
use function preg_match;
use function sprintf;
use function substr;

/**
 * A value object representing an integer
 *
 * This class exists for type-safety purposes, to ensure that integers
 * returned from ramsey/uuid methods as strings are truly integers and not some
 * other kind of string.
 *
 * To support large integers beyond PHP_INT_MAX and PHP_INT_MIN on both 64-bit
 * and 32-bit systems, we store the integers as strings.
 *
 * @psalm-immutable
 */
final class Integer implements NumberInterface
{
    /**
     * @psalm-var numeric-string
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $isNegative = false;

    /**
     * @param float|int|string|$this $value
     */
    public function __construct($value)
    {
        $this->value = $value instanceof self ? (string) $value : $this->prepareValue($value);
    }

    public function isNegative()
    {
        return $this->isNegative;
    }

    /**
     * @psalm-return numeric-string
     */
    public function toString()
    {
        return $this->value;
    }

    /**
     * @psalm-return numeric-string
     */
    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize()
    {
        return $this->toString();
    }

    public function serialize()
    {
        return $this->toString();
    }

    /**
     * @return array{string: string}
     */
    public function __serialize()
    {
        return ['string' => $this->toString()];
    }

    /**
     * Constructs the object from a serialized string representation
     *
     * @param string $data The serialized string representation of the object
     *
     * @psalm-suppress UnusedMethodCall
     */
    public function unserialize(string $data)
    {
        $this->__construct($data);
    }

    /**
     * @param mixed[] $data
     */
    public function __unserialize($data)
    {
        // @codeCoverageIgnoreStart
        if (!isset($data['string'])) {
            throw new ValueError(sprintf('%s(): Argument #1 ($data) is invalid', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        $this->unserialize($data['string']);
    }

    /**
     * @return numeric-string
     * @param float|int|string $value
     */
    private function prepareValue($value)
    {
        $value = (string) $value;
        $sign = '+';

        // If the value contains a sign, remove it for digit pattern check.
        if (strncmp($value, '-', strlen('-')) === 0 || strncmp($value, '+', strlen('+')) === 0) {
            $sign = substr($value, 0, 1);
            $value = substr($value, 1);
        }

        if (!preg_match('/^\d+$/', $value)) {
            throw new InvalidArgumentException(
                'Value must be a signed integer or a string containing only '
                . 'digits 0-9 and, optionally, a sign (+ or -)'
            );
        }

        // Trim any leading zeros.
        $value = ltrim($value, '0');

        // Set to zero if the string is empty after trimming zeros.
        if ($value === '') {
            $value = '0';
        }

        // Add the negative sign back to the value.
        if ($sign === '-' && $value !== '0') {
            $value = $sign . $value;

            /** @psalm-suppress InaccessibleProperty */
            $this->isNegative = true;
        }

        assert(is_numeric($value));

        return $value;
    }
}
