<?php

namespace Spiral\Tokenizer;

/**
 * It contains all listeners that will be noticed about found classes by a class locator.
 */
interface TokenizerListenerRegistryInterface
{
    /**
     * @param \Spiral\Tokenizer\TokenizationListenerInterface $listener
     */
    public function addListener($listener);
}
