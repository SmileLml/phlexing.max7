<?php

namespace Spiral\Core\Internal\Common;

use Spiral\Core\Config;

/**
 * @internal
 */
final class Registry
{
    /**
     * @var \Spiral\Core\Config
     */
    private $config;
    /**
     * @var array<string, object>
     */
    private $objects = [];
    /**
     * @param array<string, object> $objects
     * @param \Spiral\Core\Config $config
     */
    public function __construct($config, $objects = [])
    {
        $this->config = $config;
        $this->objects = $objects;
    }
    public function set(string $name, object $value)
    {
        $this->objects[$name] = $value;
    }

    /**
     * @template T
     *
     * @param class-string<T> $interface
     *
     * @return T
     */
    public function get(string $name, string $interface)
    {
        $className = $this->config->$name;
        $result = $this->objects[$name] ?? new $className($this);
        \assert($result instanceof $interface);
        return $result;
    }
}
