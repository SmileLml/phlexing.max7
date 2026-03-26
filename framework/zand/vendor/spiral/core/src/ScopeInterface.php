<?php

namespace Spiral\Core;

/**
 * Provides ability to run code withing isolated IoC scope.
 *
 * @deprecated
 */
interface ScopeInterface
{
    /**
     * Invokes given closure or function withing specific IoC scope.
     *
     * Example:
     *
     * $container->run(['actor' => new Actor()], function() use($container) {
     *    dump($container->get('actor'));
     * });
     *
     * @throws \Throwable
     * @return mixed
     * @param mixed[] $bindings
     * @param callable $scope
     */
    public function runScope($bindings, $scope);
}
