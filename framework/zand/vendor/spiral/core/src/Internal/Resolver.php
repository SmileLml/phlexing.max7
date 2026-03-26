<?php

namespace Spiral\Core\Internal;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionFunctionAbstract as ContextFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Spiral\Core\Container\Autowire;
use Spiral\Core\Exception\Resolver\ArgumentResolvingException;
use Spiral\Core\Exception\Resolver\InvalidArgumentException;
use Spiral\Core\Exception\Resolver\MissingRequiredArgumentException;
use Spiral\Core\Exception\Resolver\PositionalArgumentException;
use Spiral\Core\Exception\Resolver\ResolvingException;
use Spiral\Core\Exception\Resolver\UnknownParameterException;
use Spiral\Core\Exception\Resolver\UnsupportedTypeException;
use Spiral\Core\FactoryInterface;
use Spiral\Core\Internal\Common\DestructorTrait;
use Spiral\Core\Internal\Common\Registry;
use Spiral\Core\Internal\Resolver\ResolvingState;
use Spiral\Core\ResolverInterface;
use Throwable;

/**
 * @internal
 */
final class Resolver implements ResolverInterface
{
    use DestructorTrait;

    /**
     * @var \Spiral\Core\FactoryInterface
     */
    private $factory;
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @param \Spiral\Core\Internal\Common\Registry $constructor
     */
    public function __construct($constructor)
    {
        $constructor->set('resolver', $this);

        $this->factory = $constructor->get('factory', FactoryInterface::class);
        $this->container = $constructor->get('container', ContainerInterface::class);
    }

    /**
     * @param ContextFunction $reflection
     * @param mixed[] $parameters
     * @param bool $validate
     */
    public function resolveArguments($reflection, $parameters = [], $validate = true)
    {
        $state = new ResolvingState($reflection, $parameters);
        foreach ($reflection->getParameters() as $parameter) {
            $this->resolveParameter($parameter, $state, $validate)
            or
            throw new ArgumentResolvingException($reflection, $parameter->getName());
        }
        return $state->getResolvedValues();
    }

    /**
     * @param ContextFunction $reflection
     * @param mixed[] $arguments
     */
    public function validateArguments($reflection, $arguments = [])
    {
        $positional = true;
        $variadic = false;
        $parameters = $reflection->getParameters();
        if (\count($parameters) === 0) {
            return;
        }

        $parameter = null;
        while (\count($parameters) > 0 || \count($arguments) > 0) {
            // get related argument value
            $key = \key($arguments);

            // For a variadic parameter it's no sense - named or positional argument will be sent
            // But you can't send positional argument after named in any case
            if (\is_int($key) && !$positional) {
                throw new PositionalArgumentException($reflection, $key);
            }

            $positional = $positional && \is_int($key);

            if (!$variadic) {
                $parameter = \array_shift($parameters);
                $variadic = (($parameter2 = $parameter) ? $parameter2->isVariadic() : null) ?? false;
            }

            if ($parameter === null) {
                throw new UnknownParameterException($reflection, $key);
            }
            $name = $parameter->getName();

            if (($positional || $variadic) && $key !== null) {
                /** @psalm-suppress ReferenceReusedFromConfusingScope */
                $value = \array_shift($arguments);
            } elseif ($key === null || !\array_key_exists($name, $arguments)) {
                if ($parameter->isOptional()) {
                    continue;
                }
                throw new MissingRequiredArgumentException($reflection, $name);
            } else {
                $value = &$arguments[$name];
                unset($arguments[$name]);
            }

            if (!$this->validateValueToParameter($parameter, $value)) {
                throw new InvalidArgumentException($reflection, $name);
            }
        }
    }

    /**
     * @param mixed $value
     * @param \ReflectionParameter $parameter
     */
    private function validateValueToParameter($parameter, $value)
    {
        if (!$parameter->hasType() || ($parameter->allowsNull() && $value === null)) {
            return true;
        }
        $type = $parameter->getType();

        switch (true) {
            case $type instanceof ReflectionNamedType:
                [$or, $types] = [true, [$type]];
                break;
            case $type instanceof ReflectionUnionType:
                [$or, $types] = [true, $type->getTypes()];
                break;
            case $type instanceof ReflectionIntersectionType:
                [$or, $types] = [false, $type->getTypes()];
                break;
        }

        foreach ($types as $t) {
            \assert($t instanceof ReflectionNamedType);
            if (!$this->validateValueNamedType($t, $value)) {
                // If it is TypeIntersection
                if ($or) {
                    continue;
                }
                return false;
            }
            // If it is not type intersection then we can skip that value after first successful check
            if ($or) {
                return true;
            }
        }
        return !$or;
    }

    /**
     * Validate the value have the same type that in the $type.
     * This method doesn't resolve cases with nullable type and {@see null} value.
     * @param mixed $value
     * @param \ReflectionNamedType $type
     */
    private function validateValueNamedType($type, $value)
    {
        $name = $type->getName();

        if ($type->isBuiltin()) {
            switch ($name) {
                case 'mixed':
                    return true;
                case 'string':
                    return \is_string($value);
                case 'int':
                    return \is_int($value);
                case 'bool':
                    return \is_bool($value);
                case 'array':
                    return \is_array($value);
                case 'callable':
                    return \is_callable($value);
                case 'iterable':
                    return \is_iterable($value);
                case 'float':
                    return \is_float($value);
                case 'object':
                    return \is_object($value);
                case 'true':
                    return $value === true;
                case 'false':
                    return $value === false;
                default:
                    return false;
            }
        }

        return $value instanceof $name;
    }

    /**
     * @return bool {@see true} if argument was resolved.
     *
     * @throws ResolvingException
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface
     * @param \ReflectionParameter $parameter
     * @param \Spiral\Core\Internal\Resolver\ResolvingState $state
     * @param bool $validate
     */
    private function resolveParameter($parameter, $state, $validate)
    {
        $isVariadic = $parameter->isVariadic();
        $hasType = $parameter->hasType();

        // Try to resolve parameter by name
        $res = $state->resolveParameterByNameOrPosition($parameter, $isVariadic);
        if ($res !== [] || $isVariadic) {
            // validate
            if ($isVariadic) {
                foreach ($res as $k => &$v) {
                    $this->processArgument($state, $v, $validate ? $parameter : null, $k);
                }
            } else {
                $this->processArgument($state, $res[0], $validate ? $parameter : null);
            }

            return true;
        }

        $error = null;
        if ($hasType) {
            /** @var ReflectionIntersectionType|ReflectionUnionType|ReflectionNamedType $reflectionType */
            $reflectionType = $parameter->getType();

            if ($reflectionType instanceof ReflectionIntersectionType) {
                throw new UnsupportedTypeException($parameter->getDeclaringFunction(), $parameter->getName());
            }

            $types = $reflectionType instanceof ReflectionNamedType ? [$reflectionType] : $reflectionType->getTypes();
            foreach ($types as $namedType) {
                try {
                    if ($this->resolveNamedType($state, $parameter, $namedType, $validate)) {
                        return true;
                    }
                } catch (Throwable $e) {
                    $error = $e;
                }
            }
        }

        if ($parameter->isDefaultValueAvailable()) {
            $argument = $parameter->getDefaultValue();
            $this->processArgument($state, $argument);
            return true;
        }

        if ($hasType && $parameter->allowsNull()) {
            $argument = null;
            $this->processArgument($state, $argument);
            return true;
        }

        if ($error === null) {
            return false;
        }

        // Throw NotFoundExceptionInterface
        throw $error;
    }

    /**
     * Resolve single named type.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @return bool {@see true} if argument was resolved.
     * @param \Spiral\Core\Internal\Resolver\ResolvingState $state
     * @param \ReflectionParameter $parameter
     * @param \ReflectionNamedType $typeRef
     * @param bool $validate
     */
    private function resolveNamedType(
        $state,
        $parameter,
        $typeRef,
        $validate
    ) {
        return !$typeRef->isBuiltin() && $this->resolveObjectParameter($state, $typeRef->getName(), $parameter->getName(), $validate ? $parameter : null);
    }

    /**
     * Resolve argument by class name and context.
     *
     * @psalm-param class-string $class
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @return bool {@see true} if argument resolved.
     * @param \Spiral\Core\Internal\Resolver\ResolvingState $state
     * @param string $class
     * @param string $context
     * @param \ReflectionParameter|null $validateWith
     */
    private function resolveObjectParameter($state, $class, $context, $validateWith = null)
    {
        /** @psalm-suppress TooManyArguments */
        $argument = $this->container->get($class, $context);
        $this->processArgument($state, $argument, $validateWith);
        return true;
    }

    /**
     * Arguments processing. {@see Autowire} object will be resolved.
     *
     * @param mixed $value Resolved value.
     * @param ReflectionParameter|null $validateWith Should be passed when the value should be validated.
     *        Must be set for when value is user's argument.
     * @param int|string $key Only {@see string} values will be preserved.
     * @param \Spiral\Core\Internal\Resolver\ResolvingState $state
     */
    private function processArgument(
        $state,
        &$value,
        $validateWith = null,
        $key = null
    ) {
        // Resolve Autowire objects
        if ($value instanceof Autowire) {
            $value = $value->resolve($this->factory);
        }

        // Validation
        if ($validateWith !== null && !$this->validateValueToParameter($validateWith, $value)) {
            throw new InvalidArgumentException(
                $validateWith->getDeclaringFunction(),
                $validateWith->getName()
            );
        }

        $state->addResolvedValue($value, \is_string($key) ? $key : null);
    }
}
