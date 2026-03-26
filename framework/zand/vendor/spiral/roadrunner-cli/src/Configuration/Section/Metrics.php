<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Metrics extends AbstractSection
{
    private const NAME = 'metrics';

    public function render()
    {
        return [
            self::NAME => [
                'address' => '127.0.0.1:2112'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
