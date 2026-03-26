<?php

namespace Ramsey\Collection;

use Closure;
use Ramsey\Collection\Exception\CollectionMismatchException;
use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\InvalidPropertyOrMethod;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\Exception\UnsupportedOperationException;
use Ramsey\Collection\Tool\TypeTrait;
use Ramsey\Collection\Tool\ValueExtractorTrait;
use Ramsey\Collection\Tool\ValueToStringTrait;

use function array_filter;
use function array_key_first;
use function array_key_last;
use function array_map;
use function array_merge;
use function array_reduce;
use function array_search;
use function array_udiff;
use function array_uintersect;
use function in_array;
use function is_int;
use function is_object;
use function spl_object_id;
use function sprintf;
use function usort;

/**
 * This class provides a basic implementation of `CollectionInterface`, to
 * minimize the effort required to implement this interface
 *
 * @template T
 * @extends AbstractArray<T>
 * @implements CollectionInterface<T>
 */
abstract class AbstractCollection extends AbstractArray implements CollectionInterface
{
    use TypeTrait;
    use ValueToStringTrait;
    use ValueExtractorTrait;

    /**
     * @throws InvalidArgumentException if $element is of the wrong type.
     * @param mixed $element
     */
    public function add($element)
    {
        $this[] = $element;

        return true;
    }

    /**
     * @param mixed $element
     * @param bool $strict
     */
    public function contains($element, $strict = true)
    {
        return in_array($element, $this->data, $strict);
    }

    /**
     * @throws InvalidArgumentException if $element is of the wrong type.
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new InvalidArgumentException('Value must be of type ' . $this->getType() . '; value is '
            . $this->toolValueToString($value));
        }

        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * @param mixed $element
     */
    public function remove($element)
    {
        if (($position = array_search($element, $this->data, true)) !== false) {
            unset($this[$position]);

            return true;
        }

        return false;
    }

    /**
     * @throws InvalidPropertyOrMethod if the $propertyOrMethod does not exist
     *     on the elements in this collection.
     * @throws UnsupportedOperationException if unable to call column() on this
     *     collection.
     *
     * @inheritDoc
     * @param string $propertyOrMethod
     */
    public function column($propertyOrMethod)
    {
        $temp = [];

        foreach ($this->data as $item) {
            /** @psalm-suppress MixedAssignment */
            $temp[] = $this->extractValue($item, $propertyOrMethod);
        }

        return $temp;
    }

    /**
     * @return T
     *
     * @throws NoSuchElementException if this collection is empty.
     */
    public function first()
    {
        reset($this->data);
        $firstIndex = key($this->data);

        if ($firstIndex === null) {
            throw new NoSuchElementException('Can\'t determine first item. Collection is empty');
        }

        return $this->data[$firstIndex];
    }

    /**
     * @return T
     *
     * @throws NoSuchElementException if this collection is empty.
     */
    public function last()
    {
        end($this->data);
        $lastIndex = key($this->data);

        if ($lastIndex === null) {
            throw new NoSuchElementException('Can\'t determine last item. Collection is empty');
        }

        return $this->data[$lastIndex];
    }

    /**
     * @return CollectionInterface<T>
     *
     * @throws InvalidPropertyOrMethod if the $propertyOrMethod does not exist
     *     on the elements in this collection.
     * @throws UnsupportedOperationException if unable to call sort() on this
     *     collection.
     * @param string $order
     * @param string|null $propertyOrMethod
     */
    public function sort($propertyOrMethod = null, $order = Sort::Ascending)
    {
        $collection = clone $this;

        usort(
            $collection->data,
            /**
             * @param T $a
             * @param T $b
             */
            function ($a, $b) use ($propertyOrMethod, $order): int {
                /** @var mixed $aValue */
                $aValue = $this->extractValue($a, $propertyOrMethod);

                /** @var mixed $bValue */
                $bValue = $this->extractValue($b, $propertyOrMethod);

                return ($aValue <=> $bValue) * ($order === Sort::Descending ? -1 : 1);
            }
        );

        return $collection;
    }

    /**
     * @param callable(T): bool $callback A callable to use for filtering elements.
     *
     * @return CollectionInterface<T>
     */
    public function filter($callback)
    {
        $collection = clone $this;
        $collection->data = array_merge([], array_filter($collection->data, ($callback === null ? function ($v, $k) : bool {
            return !empty($v);
        } : $callback) === null ? function ($v, $k) : bool {
            return !empty($v);
        } : ($callback === null ? function ($v, $k) : bool {
            return !empty($v);
        } : $callback), ($callback === null ? function ($v, $k) : bool {
            return !empty($v);
        } : $callback) === null ? ARRAY_FILTER_USE_BOTH : ($callback === null ? ARRAY_FILTER_USE_BOTH : 0)));

        return $collection;
    }

    /**
     * @return CollectionInterface<T>
     *
     * @throws InvalidPropertyOrMethod if the $propertyOrMethod does not exist
     *     on the elements in this collection.
     * @throws UnsupportedOperationException if unable to call where() on this
     *     collection.
     * @param mixed $value
     * @param string|null $propertyOrMethod
     */
    public function where($propertyOrMethod, $value)
    {
        return $this->filter(
            /**
             * @param T $item
             */
            function ($item) use ($propertyOrMethod, $value): bool {
                /** @var mixed $accessorValue */
                $accessorValue = $this->extractValue($item, $propertyOrMethod);

                return $accessorValue === $value;
            }
        );
    }

    /**
     * @param callable(T): TCallbackReturn $callback A callable to apply to each
     *     item of the collection.
     *
     * @return CollectionInterface<TCallbackReturn>
     *
     * @template TCallbackReturn
     */
    public function map($callback)
    {
        /** @var Collection<TCallbackReturn> */
        return new Collection('mixed', array_map($callback, $this->data));
    }

    /**
     * @param callable(TCarry, T): TCarry $callback A callable to apply to each
     *     item of the collection to reduce it to a single value.
     * @param mixed $initial This is the initial value provided to the callback.
     *
     * @return TCarry
     *
     * @template TCarry
     */
    public function reduce($callback, $initial)
    {
        /** @var TCarry */
        return array_reduce($this->data, $callback, $initial);
    }

    /**
     * @param CollectionInterface<T> $other The collection to check for divergent
     *     items.
     *
     * @return CollectionInterface<T>
     *
     * @throws CollectionMismatchException if the compared collections are of
     *     differing types.
     */
    public function diff($other)
    {
        $this->compareCollectionTypes($other);

        $diffAtoB = array_udiff($this->data, $other->toArray(), $this->getComparator());
        $diffBtoA = array_udiff($other->toArray(), $this->data, $this->getComparator());

        /** @var array<array-key, T> $diff */
        $diff = array_merge($diffAtoB, $diffBtoA);

        $collection = clone $this;
        $collection->data = $diff;

        return $collection;
    }

    /**
     * @param CollectionInterface<T> $other The collection to check for
     *     intersecting items.
     *
     * @return CollectionInterface<T>
     *
     * @throws CollectionMismatchException if the compared collections are of
     *     differing types.
     */
    public function intersect($other)
    {
        $this->compareCollectionTypes($other);

        /** @var array<array-key, T> $intersect */
        $intersect = array_uintersect($this->data, $other->toArray(), $this->getComparator());

        $collection = clone $this;
        $collection->data = $intersect;

        return $collection;
    }

    /**
     * @param CollectionInterface<T> ...$collections The collections to merge.
     *
     * @return CollectionInterface<T>
     *
     * @throws CollectionMismatchException if unable to merge any of the given
     *     collections or items within the given collections due to type
     *     mismatch errors.
     */
    public function merge(...$collections)
    {
        $mergedCollection = clone $this;

        foreach ($collections as $index => $collection) {
            if (!$collection instanceof static) {
                throw new CollectionMismatchException(sprintf('Collection with index %d must be of type %s', $index, static::class));
            }

            // When using generics (Collection.php, Set.php, etc),
            // we also need to make sure that the internal types match each other
            if ($this->getUniformType($collection) !== $this->getUniformType($this)) {
                throw new CollectionMismatchException(sprintf('Collection items in collection with index %d must be of type %s', $index, $this->getType()));
            }

            foreach ($collection as $key => $value) {
                if (is_int($key)) {
                    $mergedCollection[] = $value;
                } else {
                    $mergedCollection[$key] = $value;
                }
            }
        }

        return $mergedCollection;
    }

    /**
     * @param CollectionInterface<T> $other
     *
     * @throws CollectionMismatchException
     */
    private function compareCollectionTypes($other)
    {
        if (!$other instanceof static) {
            throw new CollectionMismatchException('Collection must be of type ' . static::class);
        }

        // When using generics (Collection.php, Set.php, etc),
        // we also need to make sure that the internal types match each other
        if ($this->getUniformType($other) !== $this->getUniformType($this)) {
            throw new CollectionMismatchException('Collection items must be of type ' . $this->getType());
        }
    }

    private function getComparator()
    {
        return /**
             * @param T $a
             * @param T $b
             */
            function ($a, $b): int {
                // If the two values are object, we convert them to unique scalars.
                // If the collection contains mixed values (unlikely) where some are objects
                // and some are not, we leave them as they are.
                // The comparator should still work and the result of $a < $b should
                // be consistent but unpredictable since not documented.
                if (is_object($a) && is_object($b)) {
                    $a = spl_object_id($a);
                    $b = spl_object_id($b);
                }

                return $a === $b ? 0 : ($a < $b ? 1 : -1);
            };
    }

    /**
     * @param CollectionInterface<mixed> $collection
     */
    private function getUniformType($collection)
    {
        switch ($collection->getType()) {
            case 'integer':
                return 'int';
            case 'boolean':
                return 'bool';
            case 'double':
                return 'float';
            default:
                return $collection->getType();
        }
    }
}
