<?php

namespace Ramsey\Uuid\Builder;

use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * A UUID builder builds instances of UuidInterface
 *
 * @psalm-immutable
 */
interface UuidBuilderInterface
{
    /**
     * Builds and returns a UuidInterface
     *
     * @param CodecInterface $codec The codec to use for building this UuidInterface instance
     * @param string $bytes The byte string from which to construct a UUID
     *
     * @return UuidInterface Implementations may choose to return more specific
     *     instances of UUIDs that implement UuidInterface
     *
     * @psalm-pure
     */
    public function build($codec, $bytes);
}
