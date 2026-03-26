<?php

namespace Ramsey\Uuid\Converter\Time;

/**
 * @deprecated DegradedTimeConverter is no longer necessary for converting
 *     time on 32-bit systems. Transition to {@see GenericTimeConverter}.
 *
 * @psalm-immutable
 */
class DegradedTimeConverter extends BigNumberTimeConverter
{
}
