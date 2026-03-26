<?php

namespace Spiral\Core\Internal;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Spiral\Core\Attribute\Finalize;
use Spiral\Core\Attribute\Scope as ScopeAttribute;
use Spiral\Core\Attribute\Singleton;
use Spiral\Core\BinderInterface;
use Spiral\Core\Container\Autowire;
use Spiral\Core\Container\InjectorInterface;
use Spiral\Core\Container\SingletonInterface;
use Spiral\Core\Exception\Container\AutowireException;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\Exception\Container\InjectionException;
use Spiral\Core\Exception\Container\NotCallableException;
use Spiral\Core\Exception\Container\NotFoundException;
use Spiral\Core\Exception\Resolver\ValidationException;
use Spiral\Core\Exception\Resolver\WrongTypeException;
use Spiral\Core\Exception\Scope\BadScopeException;
use Spiral\Core\FactoryInterface;
use Spiral\Core\Internal\Common\DestructorTrait;
use Spiral\Core\Internal\Common\Registry;
use Spiral\Core\Internal\Factory\Ctx;
use Spiral\Core\InvokerInterface;
use Spiral\Core\ResolverInterface;
use WeakReference;

/**
 * @internal
 */
final class Factory implements FactoryInterface
{
    use DestructorTrait;

    /**
     * @var \Spiral\Core\Internal\State
     */
    private $state;
    /**
     * @var \Spiral\Core\BinderInterface
     */
    private $binder;
    /**
     * @var \Spiral\Core\InvokerInterface
     */
    private $invoker;
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;
    /**
     * @var \Spiral\Core\ResolverInterface
     */
    private $resolver;
    /**
     * @var \Spiral\Core\Internal\Tracer
     */
    private $tracer;
    /**
     * @var \Spiral\Core\Internal\Scope
     */
    private $scope;

    /**
     * @param \Spiral\Core\Internal\Common\Registry $constructor
     */
    public function __construct($constructor)
    {
        $constructor->set('factory', $this);

        $this->state = $constructor->get('state', State::class);
        $this->binder = $constructor->get('binder', BinderInterface::class);
        $this->invoker = $constructor->get('invoker', InvokerInterface::class);
        $this->container = $constructor->get('container', ContainerInterface::class);
        $this->resolver = $constructor->get('resolver', ResolverInterface::class);
        $this->tracer = $constructor->get('tracer', Tracer::class);
        $this->scope = $constructor->get('scope', Scope::class);
    }

    /**
     * @param string|null $context Related to parameter caused injection if any.
     *
     * @throws \Throwable
     * @return mixed
     * @param string $alias
     * @param mixed[] $parameters
     */
    public function make($alias, $parameters = [], $context = null)
    {
        if (!isset($this->state->bindings[$alias])) {
            return $this->resolveWithoutBinding($alias, $parameters, $context);
        }

        $avoidCache = $parameters !== [];
        $binding = $this->state->bindings[$alias];
        try {
            $this->tracer->push(false);
            $this->tracer->push(true);

            if (\is_object($binding)) {
                if (get_class($binding) === WeakReference::class) {
                    return $this->resolveWeakReference($binding, $alias, $context, $parameters);
                }

                // When binding is instance, assuming singleton
                return $avoidCache
                    ? $this->createInstance(new Ctx($alias, get_class($binding), $context), $parameters)
                    : $binding;
            }

            $ctx = new Ctx($alias, $alias, $context);
            if (\is_string($binding)) {
                $ctx->class = $binding;
                return $binding === $alias
                    ? $this->autowire($ctx, $parameters)
                    //Binding is pointing to something else
                    : $this->make($binding, $parameters, $context);
            }

            if ($binding[1] === true) {
                $ctx->singleton = true;
            }
            unset($this->state->bindings[$alias]);
            try {
                return $binding[0] === $alias
                    ? $this->autowire($ctx, $parameters)
                    : $this->evaluateBinding($ctx, $binding[0], $parameters);
            } finally {
                $this->state->bindings[$alias] = $this->state->bindings[$alias] ?? $binding;
            }
        } finally {
            $this->tracer->pop(true);
            $this->tracer->pop(false);
        }
    }

    /**
     * @param \WeakReference $binding
     * @param string $alias
     * @param string|null $context
     * @param mixed[] $parameters
     */
    private function resolveWeakReference(
        $binding,
        $alias,
        $context,
        $parameters
    ) {
        $avoidCache = $parameters !== [];

        if (($avoidCache || $binding->get() === null) && \class_exists($alias)) {
            try {
                $this->tracer->push(false);

                $object = $this->createInstance(new Ctx($alias, $alias, $context), $parameters);
                if ($avoidCache) {
                    return $object;
                }
                $binding = $this->state->bindings[$alias] = WeakReference::create($object);
            } catch (\Throwable $exception) {
                throw new ContainerException(
                    $this->tracer->combineTraceMessage(
                        \sprintf('Can\'t resolve `%s`: can\'t instantiate `%s` from WeakReference binding.', $this->tracer->getRootAlias(), $alias)
                    )
                );
            } finally {
                $this->tracer->pop();
            }
        }

        return $binding->get();
    }

    /**
     * @return mixed
     * @param string $alias
     * @param mixed[] $parameters
     * @param string|null $context
     */
    private function resolveWithoutBinding($alias, $parameters = [], $context = null)
    {
        $parent = $this->scope->getParent();

        if ($parent !== null) {
            try {
                $this->tracer->push(false, ...[
                    'current scope' => $this->scope->getScopeName(),
                    'jump to parent scope' => $this->scope->getParentScope()->getScopeName(),
                ]);
                return $parent->make($alias, $parameters, $context);
            } catch (BadScopeException $e) {
                if ($this->scope->getScopeName() !== $e->getScope()) {
                    throw $e;
                }
            } catch (ContainerExceptionInterface $e) {
                switch (true) {
                    case $e instanceof NotFoundException:
                        $className = NotFoundException::class;
                        break;
                    default:
                        $className = ContainerException::class;
                        break;
                }
                throw new $className($this->tracer->combineTraceMessage(\sprintf('Can\'t resolve `%s`.', $alias)), previous: $e);
            } finally {
                $this->tracer->pop(false);
            }
        }

        $this->tracer->push(false);
        try {
            //No direct instructions how to construct class, make is automatically
            return $this->autowire(new Ctx($alias, $alias, $context), $parameters);
        } finally {
            $this->tracer->pop(false);
        }
    }

    /**
     * Automatically create class.
     * Object will be cached if the $arguments list is empty.
     *
     * @psalm-assert class-string $class
     *
     * @throws AutowireException
     * @throws \Throwable
     * @param \Spiral\Core\Internal\Factory\Ctx $ctx
     * @param mixed[] $arguments
     */
    private function autowire($ctx, $arguments)
    {
        /** @psalm-suppress NoValue, InvalidArrayOffset */
        if (!(\class_exists($ctx->class) || (
            \interface_exists($ctx->class)
                &&
                (isset($this->state->injectors[$ctx->class]) || $this->binder->hasInjector($ctx->class))
        ))
        ) {
            throw new NotFoundException($this->tracer->combineTraceMessage(\sprintf('Can\'t resolve `%s`: undefined class or binding `%s`.', $this->tracer->getRootAlias(), $ctx->class)));
        }

        // automatically create instance
        $instance = $this->createInstance($ctx, $arguments);

        // apply registration functions to created instance
        return $arguments === []
            ? $this->registerInstance($ctx, $instance)
            : $instance;
    }

    /**
     * @param mixed $target Value that was bound by user.
     *
     * @throws ContainerException
     * @throws \Throwable
     * @return mixed
     * @param \Spiral\Core\Internal\Factory\Ctx $ctx
     * @param mixed[] $arguments
     */
    private function evaluateBinding($ctx, $target, $arguments)
    {
        if (\is_string($target)) {
            // Reference
            $instance = $this->make($target, $arguments, $ctx->parameter);
        } else {
            if ($target instanceof Autowire) {
                $instance = $target->resolve($this, $arguments);
            } else {
                try {
                    $instance = $this->invoker->invoke($target, $arguments);
                } catch (NotCallableException $e) {
                    throw new ContainerException($this->tracer->combineTraceMessage(\sprintf('Invalid binding for `%s`.', $ctx->alias)), $e->getCode(), $e);
                }
            }

            // Check scope name
            if (\is_object($instance)) {
                $ctx->reflection = new \ReflectionClass($instance);
                $scopeName = (($_ = (method_exists($ctx->reflection, 'getAttributes') ? $ctx->reflection->getAttributes(ScopeAttribute::class) : [])[0] ?? null) ? $_->newInstance() : null)->name;
                if ($scopeName !== null && $scopeName !== $this->scope->getScopeName()) {
                    throw new BadScopeException($scopeName, get_class($instance));
                }
            }
        }
        return \is_object($instance) && $arguments === []
            ? $this->registerInstance($ctx, $instance)
            : $instance;
    }

    /**
     * Create instance of desired class.
     *
     * @template TObject of object
     *
     * @param Ctx<TObject> $ctx
     * @param array $parameters Constructor parameters.
     *
     * @return TObject
     *
     * @throws ContainerException
     * @throws \Throwable
     */
    private function createInstance($ctx, $parameters)
    {
        $class = $ctx->class;
        try {
            $ctx->reflection = $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new ContainerException($e->getMessage(), $e->getCode(), $e);
        }
        // Check scope name
        $scope = (($_ = (method_exists($reflection, 'getAttributes') ? $reflection->getAttributes(ScopeAttribute::class) : [])[0] ?? null) ? $_->newInstance() : null)->name;
        if ($scope !== null && $scope !== $this->scope->getScopeName()) {
            throw new BadScopeException($scope, $class);
        }
        //We have to construct class using external injector when we know exact context
        if ($parameters === [] && $this->binder->hasInjector($class)) {
            $injector = $this->state->injectors[$reflection->getName()];

            try {
                $injectorInstance = $this->container->get($injector);

                if (!$injectorInstance instanceof InjectorInterface) {
                    throw new InjectionException(
                        \sprintf(
                            "Class '%s' must be an instance of InjectorInterface for '%s'.",
                            get_class($injectorInstance),
                            $reflection->getName()
                        )
                    );
                }

                /**
                 * @var InjectorInterface<TObject> $injectorInstance
                 * @psalm-suppress RedundantCondition
                 */
                $instance = $injectorInstance->createInjection($reflection, $ctx->parameter);
                if (!$reflection->isInstance($instance)) {
                    throw new InjectionException(
                        \sprintf(
                            "Invalid injection response for '%s'.",
                            $reflection->getName()
                        )
                    );
                }

                return $instance;
            } finally {
                $this->state->injectors[$reflection->getName()] = $injector;
            }
        }
        if (!$reflection->isInstantiable()) {
            switch (true) {
                case $reflection->isEnum():
                    $itIs = 'Enum';
                    break;
                case $reflection->isAbstract():
                    $itIs = 'Abstract class';
                    break;
                default:
                    $itIs = 'Class';
                    break;
            }
            throw new ContainerException($this->tracer->combineTraceMessage(\sprintf('%s `%s` can not be constructed.', $itIs, $class)));
        }
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            try {
                $this->tracer->push(false);
                $this->tracer->push(true);
                $arguments = $this->resolver->resolveArguments($constructor, $parameters);
            } catch (ValidationException $e) {
                throw new ContainerException($this->tracer->combineTraceMessage(
                    \sprintf(
                        'Can\'t resolve `%s`. %s',
                        $this->tracer->getRootAlias(),
                        $e->getMessage()
                    )
                ));
            } finally {
                $this->tracer->pop(true);
                $this->tracer->pop(false);
            }
            try {
                // Using constructor with resolved arguments
                $this->tracer->push(false);
                $this->tracer->push(true);
                $instance = new $class(...$arguments);
            } catch (\TypeError $e) {
                throw new WrongTypeException($constructor, $e);
            } finally {
                $this->tracer->pop(true);
                $this->tracer->pop(false);
            }
        } else {
            // No constructor specified
            $instance = $reflection->newInstance();
        }
        return $instance;
    }

    /**
     * Register instance in container, might perform methods like auto-singletons, log populations
     * and etc.
     *
     * @template TObject of object
     *
     * @param TObject $instance Created object.
     * @param \ReflectionClass<TObject> $reflection
     *
     * @return TObject
     * @param \Spiral\Core\Internal\Factory\Ctx $ctx
     */
    private function registerInstance($ctx, $instance)
    {
        $ctx->reflection = $ctx->reflection ?? new \ReflectionClass($instance);

        //Declarative singletons
        if ($this->isSingleton($ctx)) {
            $this->state->bindings[$ctx->alias] = $instance;
        }

        // Register finalizer
        $finalizer = $this->getFinalizer($ctx, $instance);
        if ($finalizer !== null) {
            $this->state->finalizers[] = $finalizer;
        }

        return $instance;
    }

    /**
     * Check the class was configured as a singleton.
     * @param \Spiral\Core\Internal\Factory\Ctx $ctx
     */
    private function isSingleton($ctx)
    {
        if ($ctx->singleton === true) {
            return true;
        }

        /** @psalm-suppress RedundantCondition https://github.com/vimeo/psalm/issues/9489 */
        if ($ctx->reflection->implementsInterface(SingletonInterface::class)) {
            return true;
        }

        return (method_exists($ctx->reflection, 'getAttributes') ? $ctx->reflection->getAttributes(Singleton::class) : []) !== [];
    }

    /**
     * @param \Spiral\Core\Internal\Factory\Ctx $ctx
     * @param object $instance
     */
    private function getFinalizer($ctx, $instance)
    {
        /**
         * @psalm-suppress UnnecessaryVarAnnotation
         * @var Finalize|null $attribute
         */
        $attribute = ($_ = (method_exists($ctx->reflection, 'getAttributes') ? $ctx->reflection->getAttributes(Finalize::class) : [])[0] ?? null) ? $_->newInstance() : null;
        if ($attribute === null) {
            return null;
        }

        return [$instance, $attribute->method];
    }
}
