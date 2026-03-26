<?php

namespace Ramsey\Uuid\Nonstandard;

use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Uuid as BaseUuid;

/**
 * Nonstandard\Uuid is a UUID that doesn't conform to RFC 4122
 *
 * @psalm-immutable
 */
final class Uuid extends BaseUuid
{
    /**
     * @param \Ramsey\Uuid\Nonstandard\Fields $fields
     * @param \Ramsey\Uuid\Converter\NumberConverterInterface $numberConverter
     * @param \Ramsey\Uuid\Codec\CodecInterface $codec
     * @param \Ramsey\Uuid\Converter\TimeConverterInterface $timeConverter
     */
    public function __construct(
        $fields,
        $numberConverter,
        $codec,
        $timeConverter
    ) {
        parent::__construct($fields, $numberConverter, $codec, $timeConverter);
    }
}
