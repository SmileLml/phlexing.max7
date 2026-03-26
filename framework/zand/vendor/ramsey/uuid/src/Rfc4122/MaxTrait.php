<?php

namespace Ramsey\Uuid\Rfc4122;

/**
 * Provides common functionality for max UUIDs
 *
 * The max UUID is special form of UUID that is specified to have all 128 bits
 * set to one. It is the inverse of the nil UUID.
 *
 * @link https://datatracker.ietf.org/doc/html/draft-ietf-uuidrev-rfc4122bis-00#section-5.10 Max UUID
 *
 * @psalm-immutable
 */
trait MaxTrait
{
    /**
     * Returns the bytes that comprise the fields
     */
    abstract public function getBytes();

    /**
     * Returns true if the byte string represents a max UUID
     */
    public function isMax()
    {
        return $this->getBytes() === "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff";
    }
}
