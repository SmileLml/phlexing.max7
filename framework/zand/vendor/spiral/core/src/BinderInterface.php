<?php

namespace Spiral\Core;

use Spiral\Core\Container\InjectorInterface;

/**
 * Manages container bindings.
 *
 * @psalm-type TResolver = class-string|non-empty-string|object|callable|array{class-string, non-empty-string}
 */
interface BinderInterface
{
    /**
     * Bind value resolver to container alias. Resolver can be class name (will be constructed
     * every method call), function array or Closure (executed every call). Only object resolvers
     * supported by this method.
     *
     * @param TResolver $resolver
     * @param string $alias
     */
    public function bind($alias, $resolver);

    /**
     * Bind value resolver to container alias to be executed as cached. Resolver can be class name
     * (will be constructed only once), function array or Closure (executed only once call).
     *
     * @param TResolver $resolver Can be result object or
     *        the same special callable value like the $target parameter in the {@see InvokerInterface::invoke()} method
     * @param string $alias
     */
    public function bindSingleton($alias, $resolver);

    /**
     * Check if alias points to constructed instance (singleton).
     * @param string $alias
     */
    public function hasInstance($alias);

    /**
     * @param string $alias
     */
    public function removeBinding($alias);

    /**
     * Bind class or class interface to the injector source (InjectorInterface).
     *
     * @template TClass of object
     *
     * @param class-string<TClass> $class
     * @param class-string<InjectorInterface<TClass>> $injector
     */
    public function bindInjector($class, $injector);

    /**
     * @param class-string $class
     */
    public function removeInjector($class);

    /**
     * Check if class points to injector.
     *
     * @param class-string $class
     */
    public function hasInjector($class);
}
