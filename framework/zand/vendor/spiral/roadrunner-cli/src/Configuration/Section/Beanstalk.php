<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Beanstalk extends AbstractSection
{
    private const NAME = 'beanstalk';

    public function render()
    {
        return [
            self::NAME => [
                'addr' => 'tcp://127.0.0.1:11300',
                'timeout' => '10s'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
