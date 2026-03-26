<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Nats extends AbstractSection
{
    private const NAME = 'nats';

    public function render()
    {
        return [
            self::NAME => [
                'addr' => 'demo.nats.io'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
