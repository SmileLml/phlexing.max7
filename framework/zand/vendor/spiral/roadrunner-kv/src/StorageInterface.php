<?php

namespace Spiral\RoadRunner\KeyValue;

use Spiral\RoadRunner\KeyValue\Serializer\SerializerAwareInterface;

interface StorageInterface extends
    TtlAwareCacheInterface,
    SerializerAwareInterface
{
    /**
     * @return string
     */
    public function getName();
}
