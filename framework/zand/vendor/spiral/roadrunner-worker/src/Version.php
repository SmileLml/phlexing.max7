<?php

namespace Spiral\RoadRunner;

use Composer\InstalledVersions;

final class Version
{
    /**
     * @var string[]
     */
    public const PACKAGE_NAMES = [
        'spiral/roadrunner',
        'spiral/roadrunner-worker',
    ];

    /**
     * @var string
     */
    public const VERSION_FALLBACK = 'dev-master';

    /**
     * @return string
     */
    public static function current()
    {
        foreach (self::PACKAGE_NAMES as $name) {
            if (InstalledVersions::isInstalled($name)) {
                return \ltrim((string)InstalledVersions::getPrettyVersion($name), 'v');
            }
        }

        return self::VERSION_FALLBACK;
    }

    /**
     * @return string
     */
    public static function constraint()
    {
        $current = self::current();

        if (strpos($current, '.') !== false) {
            [$major] = \explode('.', $current);

            return \is_numeric($major) ? "$major.*" : '*';
        }

        return '*';
    }
}
