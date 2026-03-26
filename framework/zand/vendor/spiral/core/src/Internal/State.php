<?php

namespace Spiral\Core\Internal;

use Spiral\Core\Container\Autowire;

/**
 * @psalm-type TResolver = class-string|non-empty-string|callable|array{class-string, non-empty-string}|Autowire
 *
 * @internal
 */
final class State
{
    /**
     * @var array<string, string|object|array{TResolver, bool}>
     */
    public $bindings = [];

    /**
     * List of classes responsible for handling specific instance or interface. Provides ability to
     * delegate container functionality.
     * @var mixed[]
     */
    public $injectors = [];

    /**
     * List of finalizers to be called on container scope destruction.
     * @var callable[]
     */
    public $finalizers = [];

    public function destruct()
    {
        $this->injectors = [];
        $this->bindings = [];
        $this->finalizers = [];
    }
}
