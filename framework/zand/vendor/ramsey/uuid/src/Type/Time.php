<?php

namespace Ramsey\Uuid\Type;

use Ramsey\Uuid\Exception\UnsupportedOperationException;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use ValueError;

use function json_decode;
use function json_encode;
use function sprintf;

/**
 * A value object representing a timestamp
 *
 * This class exists for type-safety purposes, to ensure that timestamps used
 * by ramsey/uuid are truly timestamp integers and not some other kind of string
 * or integer.
 *
 * @psalm-immutable
 */
final class Time implements TypeInterface
{
    /**
     * @var IntegerObject
     */
    private $seconds;
    /**
     * @var IntegerObject
     */
    private $microseconds;

    /**
     * @param float|int|string|IntegerObject $seconds
     * @param float|int|string|IntegerObject $microseconds
     */
    public function __construct($seconds, $microseconds = 0)
    {
        $this->seconds = new IntegerObject($seconds);
        $this->microseconds = new IntegerObject($microseconds);
    }

    public function getSeconds()
    {
        return $this->seconds;
    }

    public function getMicroseconds()
    {
        return $this->microseconds;
    }

    public function toString()
    {
        return $this->seconds->toString() . '.' . sprintf('%06s', $this->microseconds->toString());
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string[]
     */
    public function jsonSerialize()
    {
        return [
            'seconds' => $this->getSeconds()->toString(),
            'microseconds' => $this->getMicroseconds()->toString(),
        ];
    }

    public function serialize()
    {
        return (string) json_encode($this);
    }

    /**
     * @return array{seconds: string, microseconds: string}
     */
    public function __serialize()
    {
        return [
            'seconds' => $this->getSeconds()->toString(),
            'microseconds' => $this->getMicroseconds()->toString(),
        ];
    }

    /**
     * Constructs the object from a serialized string representation
     *
     * @param string $data The serialized string representation of the object
     *
     * @psalm-suppress UnusedMethodCall
     */
    public function unserialize(string $data)
    {
        /** @var array{seconds?: int|float|string, microseconds?: int|float|string} $time */
        $time = json_decode($data, true);

        if (!isset($time['seconds']) || !isset($time['microseconds'])) {
            throw new UnsupportedOperationException(
                'Attempted to unserialize an invalid value'
            );
        }

        $this->__construct($time['seconds'], $time['microseconds']);
    }

    /**
     * @param mixed[] $data
     */
    public function __unserialize($data)
    {
        // @codeCoverageIgnoreStart
        if (!isset($data['seconds']) || !isset($data['microseconds'])) {
            throw new ValueError(sprintf('%s(): Argument #1 ($data) is invalid', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        $this->__construct($data['seconds'], $data['microseconds']);
    }
}
