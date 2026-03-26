<?php

namespace Spiral\Core\Exception\Container;

/**
 * Unable to resolve argument value.
 */
class ArgumentException extends AutowireException
{
    /**
     * @var \ReflectionParameter
     */
    protected $parameter;
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $context;
    /**
     * @param \ReflectionParameter $parameter Parameter caused error.
     * @param \ReflectionFunctionAbstract $context Context method or constructor or function.
     */
    public function __construct(
        $parameter,
        $context
    ) {
        $this->parameter = $parameter;
        $this->context = $context;
        $name = $context->getName();
        if ($context instanceof \ReflectionMethod) {
            $name = $context->class . '::' . $name;
        }

        parent::__construct(\sprintf("Unable to resolve '%s' argument in '%s'", $parameter->name, $name));
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function getContext()
    {
        return $this->context;
    }
}
