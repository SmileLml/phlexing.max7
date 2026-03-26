<?php

namespace Spiral\RoadRunner\Console\Environment\OperatingSystem;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\OperatingSystem;

/**
 * @internal Factory is an internal library class, please do not use it in your code.
 * @psalm-internal Spiral\RoadRunner\Console\Environment
 *
 * @psalm-import-type OperatingSystemType from OperatingSystem
 */
class Factory
{
    /**
     * @var string
     */
    private const ERROR_UNKNOWN_OS = 'Current OS (%s) may not be supported';

    /**
     * @return OperatingSystemType
     */
    public function createFromGlobals()
    {
        switch (\PHP_OS_FAMILY) {
            case 'Windows':
                return OperatingSystem::OS_WINDOWS;

            case 'BSD':
                return OperatingSystem::OS_BSD;

            case 'Darwin':
                return OperatingSystem::OS_DARWIN;

            case 'Linux':
                // TODO Test this case (not sure if they are correct)
                return strpos(\PHP_OS, 'Alpine') !== false
                    ? OperatingSystem::OS_ALPINE
                    : OperatingSystem::OS_LINUX
                ;

            default:
                throw new \OutOfRangeException(\sprintf(self::ERROR_UNKNOWN_OS, \PHP_OS_FAMILY));
        }
    }
}
