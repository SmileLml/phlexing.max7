<?php

namespace Ramsey\Collection;

use ArrayIterator;
use Traversable;

use function count;

/**
 * This class provides a basic implementation of `ArrayInterface`, to minimize
 * the effort required to implement this interface.
 *
 * @template T
 * @implements ArrayInterface<T>
 */
abstract class AbstractArray implements ArrayInterface
{
    /**
     * The items of this array.
     *
     * @var array<array-key, T>
     */
    protected $data = [];

    /**
     * Constructs a new array object.
     *
     * @param array<array-key, T> $data The initial items to add to this array.
     */
    public function __construct($data = [])
    {
        // Invoke offsetSet() for each value added; in this way, sub-classes
        // may provide additional logic about values added to the array object.
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }

    /**
     * Returns an iterator for this array.
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php IteratorAggregate::getIterator()
     *
     * @return Traversable<array-key, T>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Returns `true` if the given offset exists in this array.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php ArrayAccess::offsetExists()
     *
     * @param mixed $offset The offset to check.
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns the value at the specified offset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php ArrayAccess::offsetGet()
     *
     * @param mixed $offset The offset for which a value should be returned.
     *
     * @return T the value stored at the offset, or null if the offset
     *     does not exist.
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
    * Sets the given value to the given offset in the array.
    *
    * @link http://php.net/manual/en/arrayaccess.offsetset.php ArrayAccess::offsetSet()
    *
     * @param mixed $offset The offset to set. If `null`, the value
        may be set at a numerically-indexed offset.
     * @param mixed $value The value to set at the given offset.
    */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Removes the given offset and its value from the array.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php ArrayAccess::offsetUnset()
     *
     * @param mixed $offset The offset to remove from the array.
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Returns data suitable for PHP serialization.
     *
     * @link https://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.serialize
     * @link https://www.php.net/serialize
     *
     * @return array<array-key, T>
     */
    public function __serialize()
    {
        return $this->data;
    }

    /**
     * Adds unserialized data to the object.
     *
     * @param array<array-key, T> $data
     */
    public function __unserialize($data)
    {
        $this->data = $data;
    }

    /**
     * Returns the number of items in this array.
     *
     * @link http://php.net/manual/en/countable.count.php Countable::count()
     */
    public function count()
    {
        return count($this->data);
    }

    public function clear()
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->data;
    }

    public function isEmpty()
    {
        return $this->data === [];
    }
}
