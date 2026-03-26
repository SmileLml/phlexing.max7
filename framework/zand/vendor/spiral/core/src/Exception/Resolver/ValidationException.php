<?php

namespace Spiral\Core\Exception\Resolver;

abstract class ValidationException extends ResolvingException
{
    abstract public function getParameter();
}
