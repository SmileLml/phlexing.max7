<?php

namespace Spiral\Core\Exception\Resolver;

use TypeError;

final class WrongTypeException extends ResolvingException
{
    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param \TypeError $error
     */
    public function __construct($reflection, $error)
    {
        $message = 'An argument resolved with wrong type: ';
        parent::__construct(
            $message . $error->getMessage(),
            $error->getCode(),
            $error
        );
    }
}
