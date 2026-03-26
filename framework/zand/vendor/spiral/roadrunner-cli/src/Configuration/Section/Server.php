<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Server extends AbstractSection
{
    private const NAME = 'server';

    public function render()
    {
        return [
            self::NAME => [
                'command' => 'php app.php',
                'relay' => 'pipes'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
