<?php

namespace Spiral\Core\Internal\Factory;

/**
 * @template TClass of object
 */
final class Ctx
{
    /**
     * @readonly
     * @var string
     */
    public $alias;
    /**
     * @var class-string<TClass>
     */
    public $class;
    /**
     * @var string|null
     */
    public $parameter;
    /**
     * @var bool|null
     */
    public $singleton;
    /**
     * @var null|\ReflectionClass<TClass>
     */
    public $reflection;
    /**
     * @param class-string<TClass> $class
     * @param null|\ReflectionClass<TClass> $reflection
     * @param string $alias
     * @param string|null $parameter
     * @param bool|null $singleton
     */
    public function __construct($alias, $class, $parameter = null, $singleton = null, $reflection = null)
    {
        $this->alias = $alias;
        $this->class = $class;
        $this->parameter = $parameter;
        $this->singleton = $singleton;
        $this->reflection = $reflection;
    }
}
