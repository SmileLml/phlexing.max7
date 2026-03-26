<?php

namespace Spiral\Core\Internal\Config;

use Spiral\Core\BinderInterface;
use Spiral\Core\Container\Autowire;
use Spiral\Core\Container\InjectableInterface;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\Internal\State;

/**
 * @psalm-import-type TResolver from BinderInterface
 * @internal
 */
class StateBinder implements BinderInterface
{
    /**
     * @readonly
     * @var \Spiral\Core\Internal\State
     */
    protected $state;
    /**
     * @param \Spiral\Core\Internal\State $state
     */
    public function __construct($state)
    {
        $this->state = $state;
    }
    /**
     * @param TResolver|object $resolver
     * @param string $alias
     */
    public function bind($alias, $resolver)
    {
        if (\is_array($resolver) || $resolver instanceof \Closure || $resolver instanceof Autowire) {
            // array means = execute me, false = not singleton
            $this->state->bindings[$alias] = [$resolver, false];

            return;
        }

        $this->state->bindings[$alias] = $resolver;
    }

    /**
     * @param TResolver|object $resolver
     * @param string $alias
     */
    public function bindSingleton($alias, $resolver)
    {
        if (\is_object($resolver) && !$resolver instanceof \Closure && !$resolver instanceof Autowire) {
            // direct binding to an instance
            $this->state->bindings[$alias] = $resolver;

            return;
        }

        $this->state->bindings[$alias] = [$resolver, true];
    }

    /**
     * @param string $alias
     */
    public function hasInstance($alias)
    {
        $bindings = &$this->state->bindings;

        while (\is_string($bindings[$alias] ?? null)) {
            //Checking alias tree
            $alias = $bindings[$alias];
        }

        return isset($bindings[$alias]) && \is_object($bindings[$alias]);
    }

    /**
     * @param string $alias
     */
    public function removeBinding($alias)
    {
        unset($this->state->bindings[$alias]);
    }

    /**
     * @param string $class
     * @param string $injector
     */
    public function bindInjector($class, $injector)
    {
        $this->state->injectors[$class] = $injector;
    }

    /**
     * @param string $class
     */
    public function removeInjector($class)
    {
        unset($this->state->injectors[$class]);
    }

    /**
     * @param string $class
     */
    public function hasInjector($class)
    {
        try {
            $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new ContainerException($e->getMessage(), $e->getCode(), $e);
        }

        if (\array_key_exists($class, $this->state->injectors)) {
            return $this->state->injectors[$class] !== null;
        }

        if (
            $reflection->implementsInterface(InjectableInterface::class)
            && $reflection->hasConstant('INJECTOR')
        ) {
            $this->state->injectors[$class] = $reflection->getConstant('INJECTOR');

            return true;
        }

        // check interfaces
        foreach ($this->state->injectors as $target => $injector) {
            if (
                \class_exists($target, true)
                && $reflection->isSubclassOf($target)
            ) {
                $this->state->injectors[$class] = $injector;

                return true;
            }

            if (
                \interface_exists($target, true)
                && $reflection->implementsInterface($target)
            ) {
                $this->state->injectors[$class] = $injector;

                return true;
            }
        }

        return false;
    }
}
