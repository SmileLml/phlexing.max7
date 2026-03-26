<?php

namespace Ramsey\Uuid\Nonstandard;

use Ramsey\Uuid\Exception\InvalidArgumentException;
use Ramsey\Uuid\Fields\SerializableFieldsTrait;
use Ramsey\Uuid\Rfc4122\FieldsInterface;
use Ramsey\Uuid\Rfc4122\VariantTrait;
use Ramsey\Uuid\Type\Hexadecimal;

use function bin2hex;
use function dechex;
use function hexdec;
use function sprintf;
use function str_pad;
use function strlen;
use function substr;

use const STR_PAD_LEFT;

/**
 * Nonstandard UUID fields do not conform to the RFC 4122 standard
 *
 * Since some systems may create nonstandard UUIDs, this implements the
 * Rfc4122\FieldsInterface, so that functionality of a nonstandard UUID is not
 * degraded, in the event these UUIDs are expected to contain RFC 4122 fields.
 *
 * Internally, this class represents the fields together as a 16-byte binary
 * string.
 *
 * @psalm-immutable
 */
final class Fields implements FieldsInterface
{
    use SerializableFieldsTrait;
    use VariantTrait;
    /**
     * @var string
     */
    private $bytes;

    /**
     * @param string $bytes A 16-byte binary string representation of a UUID
     *
     * @throws InvalidArgumentException if the byte string is not exactly 16 bytes
     */
    public function __construct($bytes)
    {
        $this->bytes = $bytes;
        if (strlen($this->bytes) !== 16) {
            throw new InvalidArgumentException(
                'The byte string must be 16 bytes long; '
                . 'received ' . strlen($this->bytes) . ' bytes'
            );
        }
    }

    public function getBytes()
    {
        return $this->bytes;
    }

    public function getClockSeq()
    {
        $clockSeq = hexdec(bin2hex(substr($this->bytes, 8, 2))) & 0x3fff;

        return new Hexadecimal(str_pad(dechex($clockSeq), 4, '0', STR_PAD_LEFT));
    }

    public function getClockSeqHiAndReserved()
    {
        return new Hexadecimal(bin2hex(substr($this->bytes, 8, 1)));
    }

    public function getClockSeqLow()
    {
        return new Hexadecimal(bin2hex(substr($this->bytes, 9, 1)));
    }

    public function getNode()
    {
        return new Hexadecimal(bin2hex(substr($this->bytes, 10)));
    }

    public function getTimeHiAndVersion()
    {
        return new Hexadecimal(bin2hex(substr($this->bytes, 6, 2)));
    }

    public function getTimeLow()
    {
        return new Hexadecimal(bin2hex(substr($this->bytes, 0, 4)));
    }

    public function getTimeMid()
    {
        return new Hexadecimal(bin2hex(substr($this->bytes, 4, 2)));
    }

    public function getTimestamp()
    {
        return new Hexadecimal(sprintf(
            '%03x%04s%08s',
            hexdec($this->getTimeHiAndVersion()->toString()) & 0x0fff,
            $this->getTimeMid()->toString(),
            $this->getTimeLow()->toString()
        ));
    }

    public function getVersion()
    {
        return null;
    }

    public function isNil()
    {
        return false;
    }

    public function isMax()
    {
        return false;
    }
}
