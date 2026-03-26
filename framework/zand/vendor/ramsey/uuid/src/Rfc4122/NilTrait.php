<?php

namespace Ramsey\Uuid\Rfc4122;

/**
 * Provides common functionality for nil UUIDs
 *
 * The nil UUID is special form of UUID that is specified to have all 128 bits
 * set to zero.
 *
 * @link https://tools.ietf.org/html/rfc4122#section-4.1.7 RFC 4122, § 4.1.7: Nil UUID
 *
 * @psalm-immutable
 */
trait NilTrait
{
    /**
     * Returns the bytes that comprise the fields
     */
    abstract public function getBytes();

    /**
     * Returns true if the byte string represents a nil UUID
     */
    public function isNil()
    {
        return $this->getBytes() === "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
    }
}
