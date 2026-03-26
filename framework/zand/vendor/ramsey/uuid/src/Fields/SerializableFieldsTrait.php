<?php

namespace Ramsey\Uuid\Fields;

use ValueError;

use function base64_decode;
use function sprintf;
use function strlen;

/**
 * Provides common serialization functionality to fields
 *
 * @psalm-immutable
 */
trait SerializableFieldsTrait
{
    /**
     * @param string $bytes The bytes that comprise the fields
     */
    abstract public function __construct($bytes);

    /**
     * Returns the bytes that comprise the fields
     */
    abstract public function getBytes();

    /**
     * Returns a string representation of object
     */
    public function serialize()
    {
        return $this->getBytes();
    }

    /**
     * @return array{bytes: string}
     */
    public function __serialize()
    {
        return ['bytes' => $this->getBytes()];
    }

    /**
     * Constructs the object from a serialized string representation
     *
     * @param string $data The serialized string representation of the object
     *
     * @psalm-suppress UnusedMethodCall
     */
    public function unserialize($data)
    {
        if (strlen($data) === 16) {
            $this->__construct($data);
        } else {
            $this->__construct(base64_decode($data));
        }
    }

    /**
     * @param mixed[] $data
     *
     * @psalm-suppress UnusedMethodCall
     */
    public function __unserialize($data)
    {
        // @codeCoverageIgnoreStart
        if (!isset($data['bytes'])) {
            throw new ValueError(sprintf('%s(): Argument #1 ($data) is invalid', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        $this->unserialize($data['bytes']);
    }
}
