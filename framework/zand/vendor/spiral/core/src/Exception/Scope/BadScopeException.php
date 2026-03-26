<?php

namespace Spiral\Core\Exception\Scope;

/**
 * @method string getScope()
 */
final class BadScopeException extends ScopeException
{
    /**
     * @var string
     */
    protected $className;
    /**
     * @param string $scope
     * @param string $className
     */
    public function __construct($scope, $className)
    {
        $this->className = $className;
        parent::__construct($scope, \sprintf('Class `%s` can be resolved only in `%s` scope.', $className, $scope));
    }
}
