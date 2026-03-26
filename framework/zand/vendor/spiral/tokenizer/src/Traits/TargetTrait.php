<?php

namespace Spiral\Tokenizer\Traits;

trait TargetTrait
{
    /**
     * Get every class trait (including traits used in parents).
     *
     * @param class-string $class
     * @return string[]
     */
    protected function fetchTraits($class)
    {
        $traits = [];

        do {
            $traits = \array_merge(\class_uses($class), $traits);
            $class = \get_parent_class($class);
        } while ($class !== false);

        //Traits from traits
        foreach (\array_flip($traits) as $trait) {
            $traits = \array_merge(\class_uses($trait), $traits);
        }

        return \array_unique($traits);
    }
}
