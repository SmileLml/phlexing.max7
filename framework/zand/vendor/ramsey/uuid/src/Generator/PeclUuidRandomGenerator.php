<?php

namespace Ramsey\Uuid\Generator;

use function uuid_create;
use function uuid_parse;

use const UUID_TYPE_RANDOM;

/**
 * PeclUuidRandomGenerator generates strings of random binary data using ext-uuid
 *
 * @link https://pecl.php.net/package/uuid ext-uuid
 */
class PeclUuidRandomGenerator implements RandomGeneratorInterface
{
    /**
     * @param int $length
     */
    public function generate($length)
    {
        $uuid = uuid_create(UUID_TYPE_RANDOM);

        return uuid_parse($uuid);
    }
}
