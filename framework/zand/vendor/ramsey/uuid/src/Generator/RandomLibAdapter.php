<?php

namespace Ramsey\Uuid\Generator;

use RandomLib\Factory;
use RandomLib\Generator;

/**
 * RandomLibAdapter generates strings of random binary data using the
 * paragonie/random-lib library
 *
 * @deprecated This class will be removed in 5.0.0. Use the default
 *     RandomBytesGenerator or implement your own generator that implements
 *     RandomGeneratorInterface.
 *
 * @link https://packagist.org/packages/paragonie/random-lib paragonie/random-lib
 */
class RandomLibAdapter implements RandomGeneratorInterface
{
    /**
     * @var \RandomLib\Generator
     */
    private $generator;

    /**
     * Constructs a RandomLibAdapter
     *
     * By default, if no Generator is passed in, this creates a high-strength
     * generator to use when generating random binary data.
     *
     * @param \RandomLib\Generator|null $generator The generator to use when generating binary data
     */
    public function __construct($generator = null)
    {
        if ($generator === null) {
            $factory = new Factory();
            $generator = $factory->getHighStrengthGenerator();
        }

        $this->generator = $generator;
    }

    /**
     * @param int $length
     */
    public function generate($length)
    {
        return $this->generator->generate($length);
    }
}
