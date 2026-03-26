<?php

namespace Spiral\Tokenizer\Listener;

use Spiral\Tokenizer\TokenizationListenerInterface;

interface ClassesLoaderInterface
{
    /**
     * Load classes for a given listener from cache.
     * Return true if classes found for a given listener and loaded.
     * If loader returns false, listener will be notified about all classes.
     * @param \Spiral\Tokenizer\TokenizationListenerInterface $listener
     */
    public function loadClasses($listener);
}
