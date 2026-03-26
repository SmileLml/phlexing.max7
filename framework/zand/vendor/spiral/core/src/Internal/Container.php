<?php

namespace Spiral\Core\Internal;

use Psr\Container\ContainerInterface;
use Spiral\Core\Container\Autowire;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\FactoryInterface;
use Spiral\Core\Internal\Common\DestructorTrait;
use Spiral\Core\Internal\Common\Registry;

/**
 * @internal
 */
final class Container implements ContainerInterface
{
    use DestructorTrait;

    /**
     * @var \Spiral\Core\Internal\State
     */
    private $state;
    /**
     * @var \Spiral\Core\FactoryInterface|\Spiral\Core\Internal\Factory
     */
    private $factory;

    /**
     * @param \Spiral\Core\Internal\Common\Registry $constructor
     */
    public function __construct($constructor)
    {
        $constructor->set('container', $this);

        $this->state = $constructor->get('state', State::class);
        $this->factory = $constructor->get('factory', FactoryInterface::class);
    }

    /**
     * Context parameter will be passed to class injectors, which makes possible to use this method
     * as:
     *
     * $this->container->get(DatabaseInterface::class, 'default');
     *
     * Attention, context ignored when outer container has instance by alias.
     *
     * @template T
     *
     * @param class-string<T>|string|Autowire $id
     * @param string|null $context Call context.
     *
     * @return class-string
     *
     * @throws ContainerException
     * @throws \Throwable
     */
    public function get($id, $context = null)
    {
        if ($id instanceof Autowire) {
            return $id->resolve($this->factory);
        }

        /** @psalm-suppress TooManyArguments */
        return $this->factory->make($id, [], $context);
    }

    /**
     * @param string $id
     */
    public function has($id)
    {
        return \array_key_exists($id, $this->state->bindings);
    }
}
