<?php

namespace Spiral\Core\Internal\Resolver;

use ReflectionFunctionAbstract;
use ReflectionParameter;
use Spiral\Core\Exception\Resolver\ResolvingException;

/**
 * @internal
 */
final class ResolvingState
{
    /**
     * @readonly
     * @var bool
     */
    public $modeNamed;

    /**
     * @psalm-var array<array-key, mixed>
     * @var mixed[]
     */
    private $resolvedValues = [];
    /**
     * @readonly
     * @var \ReflectionFunctionAbstract
     */
    public $reflection;
    /**
     * @var mixed[]
     */
    private $arguments;
    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param mixed[] $arguments
     */
    public function __construct($reflection, $arguments)
    {
        $this->reflection = $reflection;
        $this->arguments = $arguments;
        $this->modeNamed = $this->isNamedMode();
    }

    /**
     * @param mixed $value
     */
    public function addResolvedValue(&$value, string $key = null)
    {
        if ($key === null) {
            $this->resolvedValues[] = &$value;
        } else {
            $this->resolvedValues[$key] = &$value;
        }
    }

    public function resolveParameterByNameOrPosition(ReflectionParameter $parameter, bool $variadic)
    {
        $key = $this->modeNamed
            ? $parameter->getName()
            : $parameter->getPosition();

        if (!\array_key_exists($key, $this->arguments)) {
            return [];
        }
        $_val = &$this->arguments[$key];

        if ($variadic && \is_array($_val)) {
            // Save keys is possible
            $positional = true;
            $result = [];
            foreach ($_val as $key => &$item) {
                if (!$positional && \is_int($key)) {
                    throw new ResolvingException(
                        'Cannot use positional argument after named argument during unpacking named variadic argument.'
                    );
                }
                $positional = $positional && \is_int($key);
                if ($positional) {
                    $result[] = &$item;
                } else {
                    $result[$key] = &$item;
                }
            }
            return $result;
        }
        return [&$_val];
    }

    public function getResolvedValues()
    {
        return $this->resolvedValues;
    }

    private function isNamedMode()
    {
        $nums = 0;
        $strings = 0;
        foreach ($this->arguments as $key => $_) {
            if (\is_int($key)) {
                ++$nums;
            } else {
                ++$strings;
            }
        }

        switch (true) {
            case $nums === 0:
                return true;
            case $strings === 0:
                return false;
            default:
                throw new ResolvingException(
                    'You can not use both numeric and string keys for predefined arguments.'
                );
        }
    }
}
