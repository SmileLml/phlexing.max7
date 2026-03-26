<?php

namespace Ramsey\Uuid\Type;

/**
 * NumberInterface ensures consistency in numeric values returned by ramsey/uuid
 *
 * @psalm-immutable
 */
interface NumberInterface extends TypeInterface
{
    /**
     * Returns true if this number is less than zero
     */
    public function isNegative();
}
