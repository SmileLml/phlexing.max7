<?php

namespace Ramsey\Uuid\Generator;

use Ramsey\Uuid\Exception\NameException;
use Ramsey\Uuid\UuidInterface;
use ValueError;

use function hash;

/**
 * DefaultNameGenerator generates strings of binary data based on a namespace,
 * name, and hashing algorithm
 */
class DefaultNameGenerator implements NameGeneratorInterface
{
    /** @psalm-pure
     * @param \Ramsey\Uuid\UuidInterface $ns
     * @param string $name
     * @param string $hashAlgorithm */
    public function generate($ns, $name, $hashAlgorithm)
    {
        try {
            /** @var string|bool $bytes */
            $bytes = @hash($hashAlgorithm, $ns->getBytes() . $name, true);
        } catch (ValueError $e) {
            $bytes = false; // keep same behavior than PHP 7
        }

        if ($bytes === false) {
            throw new NameException(sprintf(
                'Unable to hash namespace and name with algorithm \'%s\'',
                $hashAlgorithm
            ));
        }

        return (string) $bytes;
    }
}
