<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Kv extends AbstractSection
{
    private const NAME = 'kv';

    public function render()
    {
        return [
            self::NAME => [
                'local' => [
                    'driver' => 'memory',
                    'config' => [
                        'interval' => 60
                    ]
                ],
//                'redis' => [
//                    'driver' => 'redis',
//                    'config' => [
//                        'addrs' => [
//                            'localhost:6379'
//                        ]
//                    ]
//                ]
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
