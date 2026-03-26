<?php

namespace Ramsey\Uuid\Codec;

use Ramsey\Uuid\UuidInterface;

/**
 * A codec encodes and decodes a UUID according to defined rules
 *
 * @psalm-immutable
 */
interface CodecInterface
{
    /**
     * Returns a hexadecimal string representation of a UuidInterface
     *
     * @param UuidInterface $uuid The UUID for which to create a hexadecimal
     *     string representation
     *
     * @return string Hexadecimal string representation of a UUID
     *
     * @psalm-return non-empty-string
     */
    public function encode($uuid);

    /**
     * Returns a binary string representation of a UuidInterface
     *
     * @param UuidInterface $uuid The UUID for which to create a binary string
     *     representation
     *
     * @return string Binary string representation of a UUID
     *
     * @psalm-return non-empty-string
     */
    public function encodeBinary($uuid);

    /**
     * Returns a UuidInterface derived from a hexadecimal string representation
     *
     * @param string $encodedUuid The hexadecimal string representation to
     *     convert into a UuidInterface instance
     *
     * @return UuidInterface An instance of a UUID decoded from a hexadecimal
     *     string representation
     */
    public function decode($encodedUuid);

    /**
     * Returns a UuidInterface derived from a binary string representation
     *
     * @param string $bytes The binary string representation to convert into a
     *     UuidInterface instance
     *
     * @return UuidInterface An instance of a UUID decoded from a binary string
     *     representation
     */
    public function decodeBytes($bytes);
}
