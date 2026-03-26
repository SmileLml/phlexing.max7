<?php

namespace Ramsey\Uuid\Generator;

/**
 * NameGeneratorFactory retrieves a default name generator, based on the
 * environment
 */
class NameGeneratorFactory
{
    /**
     * Returns a default name generator, based on the current environment
     */
    public function getGenerator()
    {
        return new DefaultNameGenerator();
    }
}
