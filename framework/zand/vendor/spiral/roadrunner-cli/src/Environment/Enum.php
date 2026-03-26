<?php

namespace Spiral\RoadRunner\Console\Environment;

/**
 * @internal Enum is an internal library class, please do not use it in your code.
 * @psalm-internal Spiral\RoadRunner\Console\Environment
 */
final class Enum
{
    /**
     * @param class-string $class
     * @param string $prefix
     * @return array<string, string|int>
     */
    public static function values(string $class, string $prefix)
    {
        $result = [];

        try {
            $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            return [];
        }

        /** @psalm-var int|string $value */
        foreach ($reflection->getConstants() as $name => $value) {
            if (strncmp($name, $prefix, strlen($prefix)) === 0) {
                $result[$name] = $value;
            }
        }

        return $result;
    }
}
