<?php

namespace Spiral\RoadRunner\KeyValue;

use Psr\SimpleCache\CacheInterface;
use Spiral\RoadRunner\KeyValue\Exception\InvalidArgumentException;

interface TtlAwareCacheInterface extends CacheInterface
{
    /**
     * @param string $key
     * @return \DateTimeInterface|null
     *
     * @throws InvalidArgumentException
     */
    public function getTtl($key);

    /**
     * @param iterable<string> $keys
     * @return iterable<string, \DateTimeInterface|null>
     *
     * @throws InvalidArgumentException
     */
    public function getMultipleTtl($keys = []);
}
