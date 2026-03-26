<?php

namespace Ramsey\Uuid\Generator;

use Ramsey\Uuid\Exception\RandomSourceException;
use Throwable;

/**
 * RandomBytesGenerator generates strings of random binary data using the
 * built-in `random_bytes()` PHP function
 *
 * @link http://php.net/random_bytes random_bytes()
 */
class RandomBytesGenerator implements RandomGeneratorInterface
{
    /**
     * @throws RandomSourceException if random_bytes() throws an exception/error
     *
     * @inheritDoc
     * @param int $length
     */
    public function generate($length)
    {
        try {
            return random_bytes($length);
        } catch (Throwable $exception) {
            throw new RandomSourceException(
                $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }
    }
}
