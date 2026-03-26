<?php

namespace Ramsey\Uuid;

/**
 * @deprecated DegradedUuid is no longer necessary to represent UUIDs on 32-bit
 *     systems. Transition typehints to {@see UuidInterface}.
 *
 * @psalm-immutable
 */
class DegradedUuid extends Uuid
{
}
