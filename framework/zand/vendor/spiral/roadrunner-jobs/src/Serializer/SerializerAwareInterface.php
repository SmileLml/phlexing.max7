<?php

namespace Spiral\RoadRunner\Jobs\Serializer;

interface SerializerAwareInterface
{
    /**
     * Returns the {@see SerializerInterface} from the current implementation.
     *
     * @return SerializerInterface
     */
    public function getSerializer();

    /**
     * Updates the {@see SerializerInterface} in the current implementation.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new {@see SerializerInterface} implementation.
     *
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function withSerializer($serializer);
}
