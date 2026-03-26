<?php

namespace Ramsey\Collection\Map;

use Ramsey\Collection\Exception\InvalidArgumentException;
use Ramsey\Collection\Tool\TypeTrait;
use Ramsey\Collection\Tool\ValueToStringTrait;

/**
 * This class provides a basic implementation of `TypedMapInterface`, to
 * minimize the effort required to implement this interface.
 *
 * @template K of array-key
 * @template T
 * @extends AbstractMap<K, T>
 * @implements TypedMapInterface<K, T>
 */
abstract class AbstractTypedMap extends AbstractMap implements TypedMapInterface
{
    use TypeTrait;
    use ValueToStringTrait;

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @inheritDoc
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function offsetSet($offset, $value)
    {
        if ($this->checkType($this->getKeyType(), $offset) === false) {
            throw new InvalidArgumentException('Key must be of type ' . $this->getKeyType() . '; key is '
            . $this->toolValueToString($offset));
        }

        if ($this->checkType($this->getValueType(), $value) === false) {
            throw new InvalidArgumentException('Value must be of type ' . $this->getValueType() . '; value is '
            . $this->toolValueToString($value));
        }

        parent::offsetSet($offset, $value);
    }
}
