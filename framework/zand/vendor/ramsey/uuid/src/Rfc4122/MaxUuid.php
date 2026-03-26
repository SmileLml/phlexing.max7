<?php

namespace Ramsey\Uuid\Rfc4122;

use Ramsey\Uuid\Uuid;

/**
 * The max UUID is special form of UUID that is specified to have all 128 bits
 * set to one
 *
 * @psalm-immutable
 */
final class MaxUuid extends Uuid implements UuidInterface
{
}
