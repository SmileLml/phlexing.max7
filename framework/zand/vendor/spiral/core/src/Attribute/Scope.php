<?php

namespace Spiral\Core\Attribute;

/**
 * Set a scope in which the dependency can be resolved.
 *
 * @internal We are testing this feature, it may be changed in the future.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Scope
{
    /**
     * @var string
     */
    public $name;
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}
