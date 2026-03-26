<?php

namespace Spiral\RoadRunner\KeyValue\Serializer;

use Spiral\RoadRunner\KeyValue\Exception\SerializationException;

interface SerializerInterface
{
    /**
     * @throws SerializationException
     * @param mixed $value
     */
    public function serialize($value);

    /**
     * @throws SerializationException
     * @return mixed
     * @param string $value
     */
    public function unserialize($value);
}
