<?php

namespace Ramsey\Uuid\Codec;

use Ramsey\Uuid\Guid\Guid;
use Ramsey\Uuid\UuidInterface;

use function bin2hex;
use function sprintf;
use function substr;

/**
 * GuidStringCodec encodes and decodes globally unique identifiers (GUID)
 *
 * @see Guid
 *
 * @psalm-immutable
 */
class GuidStringCodec extends StringCodec
{
    /**
     * @param \Ramsey\Uuid\UuidInterface $uuid
     */
    public function encode($uuid)
    {
        $hex = bin2hex($uuid->getFields()->getBytes());

        /** @var non-empty-string */
        return sprintf('%02s%02s%02s%02s-%02s%02s-%02s%02s-%04s-%012s', substr($hex, 6, 2), substr($hex, 4, 2), substr($hex, 2, 2), substr($hex, 0, 2), substr($hex, 10, 2), substr($hex, 8, 2), substr($hex, 14, 2), substr($hex, 12, 2), substr($hex, 16, 4), substr($hex, 20));
    }

    /**
     * @param string $encodedUuid
     */
    public function decode($encodedUuid)
    {
        $bytes = $this->getBytes($encodedUuid);

        return $this->getBuilder()->build($this, $this->swapBytes($bytes));
    }

    /**
     * @param string $bytes
     */
    public function decodeBytes($bytes)
    {
        // Specifically call parent::decode to preserve correct byte order
        return parent::decode(bin2hex($bytes));
    }

    /**
     * Swaps bytes according to the GUID rules
     * @param string $bytes
     */
    private function swapBytes($bytes)
    {
        return $bytes[3] . $bytes[2] . $bytes[1] . $bytes[0]
            . $bytes[5] . $bytes[4]
            . $bytes[7] . $bytes[6]
            . substr($bytes, 8);
    }
}
