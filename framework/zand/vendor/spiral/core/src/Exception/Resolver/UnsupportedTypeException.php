<?php

namespace Spiral\Core\Exception\Resolver;

use Spiral\Core\Exception\Traits\ClosureRendererTrait;

final class UnsupportedTypeException extends ResolvingException
{
    use ClosureRendererTrait;

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param string $parameter
     */
    public function __construct($reflection, $parameter)
    {
        $pattern = "Can not resolve unsupported type of the `{$parameter}` parameter in `%s` %s.";
        parent::__construct($this->renderFunctionAndParameter($reflection, $pattern));
    }
}
