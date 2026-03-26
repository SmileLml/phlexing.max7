<?php

namespace Ramsey\Collection;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Exception\NoSuchElementException;
use Ramsey\Collection\Tool\TypeTrait;
use Ramsey\Collection\Tool\ValueToStringTrait;

use function array_key_first;

/**
 * This class provides a basic implementation of `QueueInterface`, to minimize
 * the effort required to implement this interface.
 *
 * @template T
 * @extends AbstractArray<T>
 * @implements QueueInterface<T>
 */
class Queue extends AbstractArray implements QueueInterface
{
    use TypeTrait;
    use ValueToStringTrait;
    /**
     * @var string
     * @readonly
     */
    private $queueType;

    /**
     * Constructs a queue object of the specified type, optionally with the
     * specified data.
     *
     * @param string $queueType The type or class name associated with this queue.
     * @param array<array-key, T> $data The initial items to store in the queue.
     */
    public function __construct($queueType, $data = [])
    {
        $this->queueType = $queueType;
        parent::__construct($data);
    }

    /**
     * {@inheritDoc}
     *
     * Since arbitrary offsets may not be manipulated in a queue, this method
     * serves only to fulfill the `ArrayAccess` interface requirements. It is
     * invoked by other operations when adding values to the queue.
     *
     * @throws InvalidArgumentException if $value is of the wrong type.
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new InvalidArgumentException('Value must be of type ' . $this->getType() . '; value is '
            . $this->toolValueToString($value));
        }

        $this->data[] = $value;
    }

    /**
     * @throws InvalidArgumentException if $value is of the wrong type.
     * @param mixed $element
     */
    public function add($element)
    {
        $this[] = $element;

        return true;
    }

    /**
     * @return T
     *
     * @throws NoSuchElementException if this queue is empty.
     */
    public function element()
    {
        if ($this->peek() !== null) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }
        return $this->peek();
    }

    /**
     * @param mixed $element
     */
    public function offer($element)
    {
        try {
            return $this->add($element);
        } catch (InvalidArgumentException $exception) {
            return false;
        }
    }

    /**
     * @return T | null
     */
    public function peek()
    {
        reset($this->data);
        $index = key($this->data);

        if ($index === null) {
            return null;
        }

        return $this[$index];
    }

    /**
     * @return T | null
     */
    public function poll()
    {
        reset($this->data);
        $index = key($this->data);

        if ($index === null) {
            return null;
        }

        $head = $this[$index];
        unset($this[$index]);

        return $head;
    }

    /**
     * @return T
     *
     * @throws NoSuchElementException if this queue is empty.
     */
    public function remove()
    {
        if ($this->poll() !== null) {
            throw new NoSuchElementException('Can\'t return element from Queue. Queue is empty.');
        }
        return $this->poll();
    }

    public function getType()
    {
        return $this->queueType;
    }
}
