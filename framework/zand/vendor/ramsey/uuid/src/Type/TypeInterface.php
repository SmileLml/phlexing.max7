<?php

namespace Ramsey\Uuid\Type;

use JsonSerializable;
use Serializable;

/**
 * TypeInterface ensures consistency in typed values returned by ramsey/uuid
 *
 * @psalm-immutable
 */
interface TypeInterface extends JsonSerializable, Serializable
{
    public function toString();

    public function __toString();
}
