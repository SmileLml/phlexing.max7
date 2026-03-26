<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Boltdb extends AbstractSection
{
    private const NAME = 'boltdb';

    public function render()
    {
        return [
            self::NAME => [
                'permissions' => 0777
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
