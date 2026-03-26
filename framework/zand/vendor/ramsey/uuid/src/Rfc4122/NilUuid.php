<?php

namespace Ramsey\Uuid\Rfc4122;

use Ramsey\Uuid\Uuid;

/**
 * The nil UUID is special form of UUID that is specified to have all 128 bits
 * set to zero
 *
 * @psalm-immutable
 */
final class NilUuid extends Uuid implements UuidInterface
{
}
