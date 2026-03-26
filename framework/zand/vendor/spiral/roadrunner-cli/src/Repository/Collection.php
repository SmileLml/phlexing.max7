<?php

namespace Spiral\RoadRunner\Console\Repository;

/**
 * @internal Collection is an internal library class, please do not use it in your code.
 * @psalm-internal Spiral\RoadRunner\Console
 *
 * @template T
 *
 * @template-implements \IteratorAggregate<array-key, T>
 */
abstract class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var array<T>
     */
    protected $items;

    /**
     * @param array<T> $items
     */
    final public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * @param mixed|iterable|\Closure $items
     * @return static
     */
    public static function create($items)
    {
        switch (true) {
            case $items instanceof static:
                return $items;

            case $items instanceof \Traversable:
                $items = \iterator_to_array($items);

            case \is_array($items):
                return new static($items);

            case $items instanceof \Closure:
                return static::from($items);

            default:
                throw new \InvalidArgumentException(
                    \sprintf('Unsupported iterable type %s', \get_debug_type($items))
                );
        }
    }

    /**
     * @param \Closure $generator
     * @return static
     */
    public static function from($generator)
    {
        return static::create($generator());
    }

    /**
     * @param callable(T): bool $filter
     * @return $this
     */
    public function filter($filter)
    {
        return new static(\array_filter($this->items, ($filter === null ? function ($v, $k) : bool {
            return !empty($v);
        } : $filter) === null ? function ($v, $k) : bool {
            return !empty($v);
        } : ($filter === null ? function ($v, $k) : bool {
            return !empty($v);
        } : $filter), ($filter === null ? function ($v, $k) : bool {
            return !empty($v);
        } : $filter) === null ? ARRAY_FILTER_USE_BOTH : ($filter === null ? ARRAY_FILTER_USE_BOTH : 0)));
    }

    /**
     * @param callable(T): mixed $map
     * @return $this
     */
    public function map($map)
    {
        return new static(\array_map($map, $this->items));
    }

    /**
     * @param callable(T): bool $filter
     * @return $this
     *
     * @psalm-suppress MissingClosureParamType
     * @psalm-suppress MixedArgument
     */
    public function except($filter)
    {
        $callback = static function (...$args) use ($filter) : bool {
            return ! $filter(...$args);
        };

        return new static(\array_filter($this->items, $callback));
    }

    /**
     * @param null|callable(T): bool $filter
     * @return T|null
     */
    public function first($filter = null)
    {
        $self = $filter === null ? $this : $this->filter($filter);

        return $self->items === [] ? null : \reset($self->items);
    }

    /**
     * @param callable(): T $otherwise
     * @param null|callable(T): bool $filter
     * @return T
     */
    public function firstOr($otherwise, $filter = null)
    {
        return $this->first($filter) ?? $otherwise();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return \count($this->items);
    }

    /**
     * @param callable $then
     * @return $this
     */
    public function whenEmpty($then)
    {
        if ($this->empty()) {
            $then();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function empty()
    {
        return $this->items === [];
    }

    /**
     * @return array<T>
     */
    public function toArray()
    {
        return \array_values($this->items);
    }
}
