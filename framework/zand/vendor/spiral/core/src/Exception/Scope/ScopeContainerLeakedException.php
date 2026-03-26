<?php

namespace Spiral\Core\Exception\Scope;

final class ScopeContainerLeakedException extends ScopeException
{
    /**
     * @param array<int<0, max>, string|null> $parents
     * @param string|null $scope
     */
    public function __construct($scope, $parents)
    {
        $item0Unpacked = \array_reverse($parents);
        $scopes = \implode('->', \array_map(static function (?string $scope) : string {
            return $scope === null ? 'null' : "\"$scope\"";
        }, array_merge($item0Unpacked, [$scope])));
        parent::__construct($scope, \sprintf('Scoped container has been leaked. Scope: %s.', $scopes));
    }
}
