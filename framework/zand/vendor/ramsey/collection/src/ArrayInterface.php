<?php

namespace Ramsey\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * `ArrayInterface` provides traversable array functionality to data types.
 *
 * @template T
 * @extends ArrayAccess<array-key, T>
 * @extends IteratorAggregate<array-key, T>
 */
interface ArrayInterface extends
    ArrayAccess,
    Countable,
    IteratorAggregate
{
    /**
     * Removes all items from this array.
     */
    public function clear();

    /**
     * Returns a native PHP array representation of this array object.
     *
     * @return array<array-key, T>
     */
    public function toArray();

    /**
     * Returns `true` if this array is empty.
     */
    public function isEmpty();
}
