<?php

namespace Spiral\Core\Exception\Resolver;

use Spiral\Core\Exception\Traits\ClosureRendererTrait;

final class PositionalArgumentException extends ValidationException
{
    use ClosureRendererTrait;
    /**
     * @readonly
     * @var int
     */
    private $position;

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param int $position
     */
    public function __construct(
        $reflection,
        $position
    ) {
        $this->position = $position;
        $pattern = 'Cannot use positional argument after named argument `%s` %s.';
        parent::__construct($this->renderFunctionAndParameter($reflection, $pattern));
    }

    public function getParameter()
    {
        return '#' . $this->position;
    }
}
