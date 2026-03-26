<?php

namespace Spiral\RoadRunner\KeyValue\Exception;

use Psr\SimpleCache\InvalidArgumentException as InvalidArgumentExceptionInterface;

class InvalidArgumentException extends KeyValueException implements
    InvalidArgumentExceptionInterface
{
}
