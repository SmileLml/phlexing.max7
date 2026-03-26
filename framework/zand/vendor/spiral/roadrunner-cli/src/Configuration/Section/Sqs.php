<?php

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Sqs extends AbstractSection
{
    private const NAME = 'sqs';

    public function render()
    {
        return [
            self::NAME => [
                'key' => 'api-key',
                'secret' => 'api-secret',
                'region' => 'us-west-1',
                'session_token' => 'test',
                'endpoint' => 'http://127.0.0.1:9324'
            ]
        ];
    }

    public static function getShortName()
    {
        return self::NAME;
    }
}
