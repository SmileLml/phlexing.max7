<?php

namespace Spiral\Core\Exception\Resolver;

use Spiral\Core\Exception\Traits\ClosureRendererTrait;

final class UnknownParameterException extends ValidationException
{
    use ClosureRendererTrait;
    /**
     * @readonly
     * @var string
     */
    private $parameter;

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param string $parameter
     */
    public function __construct(
        $reflection,
        $parameter
    ) {
        $this->parameter = $parameter;
        $pattern = "Unknown named parameter `{$parameter}` `%s` %s.";
        parent::__construct($this->renderFunctionAndParameter($reflection, $pattern));
    }

    public function getParameter()
    {
        return $this->parameter;
    }
}
