<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Websockets extends AbstractSection
{
    private const NAME = 'websockets';

    public function render()
    {
        return [
            self::NAME => [
                'broker' => 'default-redis',
                'allowed_origin' => '*',
                'path' => '/ws'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
