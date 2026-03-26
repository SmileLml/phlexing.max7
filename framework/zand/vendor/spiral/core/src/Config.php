<?php

namespace Spiral\Core;

use IteratorAggregate;
use Psr\Container\ContainerInterface;
use Spiral\Core\Internal\Binder;
use Spiral\Core\Internal\Container;
use Spiral\Core\Internal\Factory;
use Spiral\Core\Internal\Invoker;
use Spiral\Core\Internal\Resolver;
use Spiral\Core\Internal\Scope;
use Spiral\Core\Internal\State;
use Spiral\Core\Internal\Tracer;
use Traversable;

/**
 * Container configuration that will be used not only in the root container but also in all child containers.
 * The {@see self::$scopedBindings} property is internal and common for all containers.
 * By the reason you can access to bindings for any scope from any container.
 *
 * @implements IteratorAggregate<
 *     non-empty-string,
 *     class-string<State>|class-string<ResolverInterface>|class-string<FactoryInterface>|class-string<ContainerInterface>|class-string<BinderInterface>|class-string<InvokerInterface>|class-string<Tracer>|class-string<Scope>
 * >
 */
class Config implements IteratorAggregate
{
    /** @var class-string<Scope>
     * @readonly */
    public $scope;
    /**
     * @readonly
     * @var \Spiral\Core\Internal\Config\StateStorage
     */
    public $scopedBindings;
    /**
     * @var bool
     */
    private $rootLocked = true;
    /**
     * @var class-string<State>
     * @readonly
     */
    public $state = State::class;
    /**
     * @var class-string<ResolverInterface>
     * @readonly
     */
    public $resolver = Resolver::class;
    /**
     * @var class-string<FactoryInterface>
     * @readonly
     */
    public $factory = Factory::class;
    /**
     * @var class-string<ContainerInterface>
     * @readonly
     */
    public $container = Container::class;
    /**
     * @var class-string<BinderInterface>
     * @readonly
     */
    public $binder = Binder::class;
    /**
     * @var class-string<InvokerInterface>
     * @readonly
     */
    public $invoker = Invoker::class;
    /**
     * @var class-string<Tracer>
     * @readonly
     */
    public $tracer = Tracer::class;
    /**
     * @param class-string<State> $state
     * @param class-string<ResolverInterface> $resolver
     * @param class-string<FactoryInterface> $factory
     * @param class-string<ContainerInterface> $container
     * @param class-string<BinderInterface> $binder
     * @param class-string<InvokerInterface> $invoker
     * @param class-string<Tracer> $tracer
     */
    public function __construct($state = State::class, $resolver = Resolver::class, $factory = Factory::class, $container = Container::class, $binder = Binder::class, $invoker = Invoker::class, $tracer = Tracer::class)
    {
        $this->state = $state;
        $this->resolver = $resolver;
        $this->factory = $factory;
        $this->container = $container;
        $this->binder = $binder;
        $this->invoker = $invoker;
        $this->tracer = $tracer;
        $this->scope = Scope::class;
        $this->scopedBindings = new Internal\Config\StateStorage();
    }

    public function getIterator()
    {
        yield 'state' => $this->state;
        yield 'resolver' => $this->resolver;
        yield 'factory' => $this->factory;
        yield 'container' => $this->container;
        yield 'binder' => $this->binder;
        yield 'invoker' => $this->invoker;
        yield 'tracer' => $this->tracer;
        yield 'scope' => $this->scope;
    }

    /**
     * Mutex lock for root container.
     * First run of the method will return {@see true}, all subsequent calls will return {@see false}.
     * The parent container must call the method once and before any child container.
     */
    public function lockRoot()
    {
        try {
            return $this->rootLocked;
        } finally {
            $this->rootLocked = false;
        }
    }
}
