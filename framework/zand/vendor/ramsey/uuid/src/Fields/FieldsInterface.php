<?php

namespace Ramsey\Uuid\Fields;

use Serializable;

/**
 * UUIDs are comprised of unsigned integers, the bytes of which are separated
 * into fields and arranged in a particular layout defined by the specification
 * for the variant
 *
 * @psalm-immutable
 */
interface FieldsInterface extends Serializable
{
    /**
     * Returns the bytes that comprise the fields
     */
    public function getBytes();
}
