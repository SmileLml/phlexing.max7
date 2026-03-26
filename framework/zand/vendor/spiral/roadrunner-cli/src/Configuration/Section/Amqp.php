<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Amqp extends AbstractSection
{
    private const NAME = 'amqp';

    public function render()
    {
        return [
            self::NAME => [
                'addr' => 'amqp://guest:guest@127.0.0.1:5672/'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
