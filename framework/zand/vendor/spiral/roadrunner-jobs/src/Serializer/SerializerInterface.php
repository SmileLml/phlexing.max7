<?php

namespace Spiral\RoadRunner\Jobs\Serializer;

use Spiral\RoadRunner\Jobs\Exception\SerializationException;

/**
 * Serializes job payloads.
 *
 * Please note that this implementation (including the interface) may change in
 * the future. Use a serializer implementation change only as a last resort.
 */
interface SerializerInterface
{
    /**
     * Serializes payload.
     *
     * @param array $payload
     * @return string
     * @throws SerializationException
     */
    public function serialize($payload);

    /**
     * Deserializes payload.
     *
     * @param string $payload
     * @return array
     * @throws SerializationException
     */
    public function deserialize($payload);
}
