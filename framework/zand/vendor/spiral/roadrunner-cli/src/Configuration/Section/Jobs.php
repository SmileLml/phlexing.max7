<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Jobs extends AbstractSection
{
    private const NAME = 'jobs';

    public function render()
    {
        return [
            self::NAME => [
                'pool' => [
                    'num_workers' => 2,
                    'max_worker_memory' => 100
                ],
                'consume' => []
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
