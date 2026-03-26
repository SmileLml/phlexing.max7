<?php

namespace Ramsey\Uuid\Generator;

use Ramsey\Uuid\UuidInterface;

/**
 * A name generator generates strings of binary data created by hashing together
 * a namespace with a name, according to a hashing algorithm
 */
interface NameGeneratorInterface
{
    /**
     * Generate a binary string from a namespace and name hashed together with
     * the specified hashing algorithm
     *
     * @param UuidInterface $ns The namespace
     * @param string $name The name to use for creating a UUID
     * @param string $hashAlgorithm The hashing algorithm to use
     *
     * @return string A binary string
     *
     * @psalm-pure
     */
    public function generate($ns, $name, $hashAlgorithm);
}
