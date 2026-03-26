<?php

namespace Spiral\Tokenizer\Attribute;

abstract class AbstractTarget
{
    /**
     * @var non-empty-string|null
     * @readonly
     */
    public $scope;
    /**
     * @param string|null $scope
     */
    public function __construct($scope = null)
    {
        $this->scope = $scope;
    }
    /**
     * Generates a unique string for this target to be used as cache key.
     * @return non-empty-string
     */
    public function __toString()
    {
        return \md5(\print_r($this, true));
    }
    /**
     * Filter given classes and return only those that should be listened.
     * @param \ReflectionClass[] $classes
     * @return \Iterator<class-string>
     */
    abstract public function filter($classes);
    /**
     * Get scope for class locator. If scope is not set, all classes will be listened.
     * @return non-empty-string|null
     */
    public function getScope()
    {
        return $this->scope;
    }
}
