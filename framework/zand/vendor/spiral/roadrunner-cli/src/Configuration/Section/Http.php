<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Http extends AbstractSection
{
    private const NAME = 'http';

    public function render()
    {
        return [
            self::NAME => [
                'address' => '0.0.0.0:8080',
                'middleware' => [
                    'gzip',
                    'static'
                ],
                'static' => [
                    'dir' => 'public',
                    'forbid' => ['.php', '.htaccess'],
                ],
                'pool' => [
                    'num_workers' => 1,
                    'supervisor' => [
                        'max_worker_memory' => 100
                    ]
                ]
            ]
        ];
    }

    public function getRequired()
    {
        return [
            Server::class
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
