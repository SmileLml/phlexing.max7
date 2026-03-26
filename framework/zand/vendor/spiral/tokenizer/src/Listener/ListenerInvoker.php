<?php

namespace Spiral\Tokenizer\Listener;

use Spiral\Tokenizer\TokenizationListenerInterface;

/**
 * @internal
 */
final class ListenerInvoker
{
    /**
     * @param iterable<\ReflectionClass> $classes
     */
    public function invoke(TokenizationListenerInterface $listener, iterable $classes)
    {
        foreach ($classes as $class) {
            $listener->listen($class);
        }

        $listener->finalize();
    }
}
