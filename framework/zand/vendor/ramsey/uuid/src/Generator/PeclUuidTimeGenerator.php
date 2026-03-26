<?php

namespace Ramsey\Uuid\Generator;

use function uuid_create;
use function uuid_parse;

use const UUID_TYPE_TIME;

/**
 * PeclUuidTimeGenerator generates strings of binary data for time-base UUIDs,
 * using ext-uuid
 *
 * @link https://pecl.php.net/package/uuid ext-uuid
 */
class PeclUuidTimeGenerator implements TimeGeneratorInterface
{
    /**
     * @inheritDoc
     * @param int|null $clockSeq
     */
    public function generate($node = null, $clockSeq = null)
    {
        $uuid = uuid_create(UUID_TYPE_TIME);

        return uuid_parse($uuid);
    }
}
