<?php

namespace Spiral\Core\Exception\Traits;

use ReflectionFunction;
use ReflectionNamedType;
use ReflectionUnionType;

trait ClosureRendererTrait
{
    /**
     * @param string $pattern String that contains method and fileAndLine markers
     * @param \ReflectionFunctionAbstract $reflection
     */
    protected function renderFunctionAndParameter(
        $reflection,
        $pattern
    ) {
        $function = $reflection->getName();
        /** @var class-string|null $class */
        $class = $reflection->class ?? null;

        switch (true) {
            case $class !== null:
                $method = "{$class}::{$function}";
                break;
            case $reflection->isClosure():
                $method = $this->renderClosureSignature($reflection);
                break;
            default:
                $method = $function;
                break;
        }

        $fileName = $reflection->getFileName();
        $line = $reflection->getStartLine();

        $fileAndLine = '';
        if (!empty($fileName)) {
            $fileAndLine = "in \"$fileName\" at line $line";
        }

        return \sprintf($pattern, $method, $fileAndLine);
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     */
    private function renderClosureSignature($reflection)
    {
        $closureParameters = [];

        foreach ($reflection->getParameters() as $parameter) {
            /** @var ReflectionNamedType|ReflectionUnionType|null $type */
            $type = $parameter->getType();
            $parameterString = \sprintf(
                '%s %s%s$%s',
                // type
                (string) $type,
                // reference
                $parameter->isPassedByReference() ? '&' : '',
                // variadic
                $parameter->isVariadic() ? '...' : '',
                $parameter->getName()
            );
            if ($parameter->isDefaultValueAvailable()) {
                $default = $parameter->getDefaultValue();
                switch (true) {
                    case \is_object($default):
                    case $parameter->isDefaultValueConstant():
                    default:
                }
            }
            $closureParameters[] = \ltrim($parameterString);
        }
        $static = $reflection->isStatic() ? 'static ' : '';
        return "{$static}function (" . \implode(', ', $closureParameters) . ')';
    }
}
