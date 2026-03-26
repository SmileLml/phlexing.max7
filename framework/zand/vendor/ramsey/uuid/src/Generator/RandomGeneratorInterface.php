<?php

namespace Ramsey\Uuid\Generator;

/**
 * A random generator generates strings of random binary data
 */
interface RandomGeneratorInterface
{
    /**
     * Generates a string of randomized binary data
     *
     * @param int<1, max> $length The number of bytes of random binary data to generate
     *
     * @return string A binary string
     */
    public function generate($length);
}
