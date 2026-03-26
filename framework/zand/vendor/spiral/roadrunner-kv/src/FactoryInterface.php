<?php

namespace Spiral\RoadRunner\KeyValue;

use Spiral\RoadRunner\KeyValue\Serializer\SerializerAwareInterface;

interface FactoryInterface extends SerializerAwareInterface
{
    /**
     * Create a shared cache storage by its name.
     *
     * @param string $name
     * @return StorageInterface
     */
    public function select($name);
}
