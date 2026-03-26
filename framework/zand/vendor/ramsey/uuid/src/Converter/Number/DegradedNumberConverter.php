<?php

namespace Ramsey\Uuid\Converter\Number;

/**
 * @deprecated DegradedNumberConverter is no longer necessary for converting
 *     numbers on 32-bit systems. Transition to {@see GenericNumberConverter}.
 *
 * @psalm-immutable
 */
class DegradedNumberConverter extends BigNumberConverter
{
}
