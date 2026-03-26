<?php

namespace Ramsey\Uuid;

use JsonSerializable;
use Ramsey\Uuid\Fields\FieldsInterface;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Serializable;
use Stringable;

/**
 * A UUID is a universally unique identifier adhering to an agreed-upon
 * representation format and standard for generation
 *
 * @psalm-immutable
 */
interface UuidInterface extends
    DeprecatedUuidInterface,
    JsonSerializable,
    Serializable,
    Stringable
{
    /**
     * Returns -1, 0, or 1 if the UUID is less than, equal to, or greater than
     * the other UUID
     *
     * The first of two UUIDs is greater than the second if the most
     * significant field in which the UUIDs differ is greater for the first
     * UUID.
     *
     * * Q. What's the value of being able to sort UUIDs?
     * * A. Use them as keys in a B-Tree or similar mapping.
     *
     * @param UuidInterface $other The UUID to compare
     *
     * @return int -1, 0, or 1 if the UUID is less than, equal to, or greater than $other
     */
    public function compareTo($other);

    /**
     * Returns true if the UUID is equal to the provided object
     *
     * The result is true if and only if the argument is not null, is a UUID
     * object, has the same variant, and contains the same value, bit for bit,
     * as the UUID.
     *
     * @param object|null $other An object to test for equality with this UUID
     *
     * @return bool True if the other object is equal to this UUID
     */
    public function equals($other);

    /**
     * Returns the binary string representation of the UUID
     *
     * @psalm-return non-empty-string
     */
    public function getBytes();

    /**
     * Returns the fields that comprise this UUID
     */
    public function getFields();

    /**
     * Returns the hexadecimal representation of the UUID
     */
    public function getHex();

    /**
     * Returns the integer representation of the UUID
     */
    public function getInteger();

    /**
     * Returns the string standard representation of the UUID as a URN
     *
     * @link http://en.wikipedia.org/wiki/Uniform_Resource_Name Uniform Resource Name
     * @link https://tools.ietf.org/html/rfc4122#section-3 RFC 4122, § 3: Namespace Registration Template
     */
    public function getUrn();

    /**
     * Returns the string standard representation of the UUID
     *
     * @psalm-return non-empty-string
     */
    public function toString();

    /**
     * Casts the UUID to the string standard representation
     *
     * @psalm-return non-empty-string
     */
    public function __toString();
}
