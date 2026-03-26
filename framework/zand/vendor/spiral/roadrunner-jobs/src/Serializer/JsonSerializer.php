<?php

namespace Spiral\RoadRunner\Jobs\Serializer;

use Spiral\RoadRunner\Jobs\Exception\SerializationException;

final class JsonSerializer implements SerializerInterface
{
    /**
     * {@inheritDoc}
     * @param mixed[] $payload
     */
    public function serialize($payload)
    {
        try {
            return \json_encode($payload, 0);
        } catch (\Throwable $e) {
            throw new SerializationException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     * @param string $payload
     */
    public function deserialize($payload)
    {
        try {
            return (array)\json_decode($payload, true, 512, 0);
        } catch (\Throwable $e) {
            throw new SerializationException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
