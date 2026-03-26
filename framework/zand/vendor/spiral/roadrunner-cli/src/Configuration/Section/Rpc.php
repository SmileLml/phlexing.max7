<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Rpc extends AbstractSection
{
    private const NAME = 'rpc';

    public function render()
    {
        return [
            self::NAME => [
                'listen' => 'tcp://127.0.0.1:6001'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
