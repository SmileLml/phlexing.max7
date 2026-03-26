<?php

namespace Spiral\Core\Exception\Scope;

/**
 * @method string getScope()
 */
final class NamedScopeDuplicationException extends ScopeException
{
    /**
     * @param string $scope
     */
    public function __construct($scope)
    {
        parent::__construct($scope, "Error on a scope allocation with the name `{$scope}`. A scope with the same name already exists.");
    }
}
