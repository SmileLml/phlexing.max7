<?php

namespace Ramsey\Uuid\Rfc4122;

use Ramsey\Uuid\Nonstandard\UuidV6 as NonstandardUuidV6;

/**
 * Reordered time, or version 6, UUIDs include timestamp, clock sequence, and
 * node values that are combined into a 128-bit unsigned integer
 *
 * @link https://datatracker.ietf.org/doc/html/draft-ietf-uuidrev-rfc4122bis-00#section-5.6 UUID Version 6
 *
 * @psalm-immutable
 */
final class UuidV6 extends NonstandardUuidV6 implements UuidInterface
{
}
