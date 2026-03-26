<?php

namespace Spiral\Core;

use Fiber;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Scope class provides ability to enable or disable global container access within specific access scope.
 *
 * @internal
 */
final class ContainerScope
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    private static $container;

    /**
     * Returns currently active container scope if any.
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * Invokes given closure or function withing global IoC scope.
     *
     * @throws Throwable
     * @return mixed
     */
    public static function runScope(ContainerInterface $container, callable $scope)
    {
        [$previous, self::$container] = [self::$container, $container];

        try {
            if (Fiber::getCurrent() === null) {
                return $scope(self::$container);
            }

            // Wrap scope into fiber
            $fiber = new Fiber(static function () use ($scope) {
                return $scope(self::$container);
            });
            $value = $fiber->start();
            while (!$fiber->isTerminated()) {
                self::$container = $previous;
                $resume = Fiber::suspend($value);
                self::$container = $container;
                $value = $fiber->resume($resume);
            }
            return $fiber->getReturn();
        } finally {
            self::$container = $previous;
        }
    }
}
