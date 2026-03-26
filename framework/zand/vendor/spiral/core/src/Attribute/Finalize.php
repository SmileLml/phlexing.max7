<?php

namespace Spiral\Core\Attribute;

/**
 * Define a finalize method for the class.
 *
 * @internal We are testing this feature, it may be changed in the future.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Finalize
{
    /**
     * @var string
     */
    public $method;
    /**
     * @param string $method
     */
    public function __construct($method)
    {
        $this->method = $method;
    }
}
