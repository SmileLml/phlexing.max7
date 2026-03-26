<?php

namespace Spiral\RoadRunner\Console\Environment;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\Architecture\Factory;

/**
 * @psalm-type ArchitectureType = Architecture::ARCH_*
 */
final class Architecture
{
    /**
     * @var string
     */
    public const ARCH_X86_64 = 'amd64';
    /**
     * @var string
     */
    public const ARCH_ARM_64 = 'arm64';

    /**
     * @return ArchitectureType
     */
    public static function createFromGlobals()
    {
        return (new Factory())->createFromGlobals();
    }

    /**
     * @return array<string, ArchitectureType>
     */
    public static function all()
    {
        static $values;

        if ($values === null) {
            $values = Enum::values(self::class, 'ARCH_');
        }

        /** @psalm-var array<string, ArchitectureType> $values */
        return $values;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value)
    {
        return \in_array($value, self::all(), true);
    }
}
