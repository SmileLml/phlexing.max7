<?php

namespace Spiral\RoadRunner\Console\Environment;

final class Environment
{
    /**
     * @param string $key
     * @param string|null $default
     * @param array $variables
     * @return string|null
     *
     * @psalm-return ($default is string ? string : string|null)
     */
    public static function get(string $key, string $default = null, array $variables = [])
    {
        /** @var mixed $result */
        $result = $variables[$key] ?? $_ENV[$key] ?? $_SERVER[$key] ?? null;

        if (\is_string($result)) {
            return $result;
        }

        return $default;
    }
}
