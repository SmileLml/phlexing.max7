<?php

namespace Ramsey\Collection\Map;

/**
 * A `TypedMapInterface` represents a map of elements where key and value are
 * typed.
 *
 * @template K of array-key
 * @template T
 * @extends MapInterface<K, T>
 */
interface TypedMapInterface extends MapInterface
{
    /**
     * Return the type used on the key.
     */
    public function getKeyType();

    /**
     * Return the type forced on the values.
     */
    public function getValueType();
}
