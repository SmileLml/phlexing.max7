<?php

namespace Spiral\RoadRunner\KeyValue\Serializer;

trait SerializerAwareTrait
{
    /**
     * @var \Spiral\RoadRunner\KeyValue\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @param \Spiral\RoadRunner\KeyValue\Serializer\SerializerInterface $serializer
     */
    protected function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function withSerializer($serializer)
    {
        $self = clone $this;
        $self->setSerializer($serializer);

        return $self;
    }

    public function getSerializer()
    {
        return $this->serializer;
    }
}
