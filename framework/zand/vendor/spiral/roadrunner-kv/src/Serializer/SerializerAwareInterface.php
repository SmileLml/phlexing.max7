<?php

namespace Spiral\RoadRunner\KeyValue\Serializer;

interface SerializerAwareInterface
{
    /**
     * @return $this
     * @param \Spiral\RoadRunner\KeyValue\Serializer\SerializerInterface $serializer
     */
    public function withSerializer($serializer);

    public function getSerializer();
}
