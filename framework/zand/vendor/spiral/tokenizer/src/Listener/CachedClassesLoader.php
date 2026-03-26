<?php

namespace Spiral\Tokenizer\Listener;

use Spiral\Attributes\ReaderInterface;
use Spiral\Boot\MemoryInterface;
use Spiral\Tokenizer\Attribute\AbstractTarget;
use Spiral\Tokenizer\TokenizationListenerInterface;

final class CachedClassesLoader implements ClassesLoaderInterface
{
    /**
     * @readonly
     * @var \Spiral\Attributes\ReaderInterface
     */
    private $reader;
    /**
     * @readonly
     * @var \Spiral\Boot\MemoryInterface
     */
    private $memory;
    /**
     * @readonly
     * @var \Spiral\Tokenizer\Listener\ClassLocatorByTarget
     */
    private $locator;
    /**
     * @readonly
     * @var \Spiral\Tokenizer\Listener\ListenerInvoker
     */
    private $invoker;
    /**
     * @readonly
     * @var bool
     */
    private $readCache = true;
    /**
     * @param \Spiral\Attributes\ReaderInterface $reader
     * @param \Spiral\Boot\MemoryInterface $memory
     * @param \Spiral\Tokenizer\Listener\ClassLocatorByTarget $locator
     * @param \Spiral\Tokenizer\Listener\ListenerInvoker $invoker
     * @param bool $readCache
     */
    public function __construct($reader, $memory, $locator, $invoker, $readCache = true)
    {
        $this->reader = $reader;
        $this->memory = $memory;
        $this->locator = $locator;
        $this->invoker = $invoker;
        $this->readCache = $readCache;
    }
    /**
     * @param \Spiral\Tokenizer\TokenizationListenerInterface $listener
     */
    public function loadClasses($listener)
    {
        $targets = \iterator_to_array($this->parseAttributes($listener));

        // If there are no targets, then listener can't be cached.
        if ($targets === []) {
            return false;
        }

        $listenerClasses = [];

        // We decided to load classes for each target separately.
        // It allows us to cache classes for each target separately and if we reuse the
        // same target in multiple listeners, we will not have to load classes for the same target.
        foreach ($targets as $target) {
            $cacheKey = (string)$target;

            $classes = $this->readCache ? $this->memory->loadData($cacheKey) : null;
            if ($classes === null) {
                $this->memory->saveData($cacheKey, $classes = $this->locator->getClasses($target));
            }

            $listenerClasses = \array_merge($listenerClasses, $classes);
        }

        $this->invoker->invoke($listener, \array_map(static function (string $class) {
            return new \ReflectionClass($class);
        }, \array_unique($listenerClasses)));

        return true;
    }

    /**
     * @param \Spiral\Tokenizer\TokenizationListenerInterface $listener
     */
    private function parseAttributes($listener)
    {
        $listener = new \ReflectionClass($listener);

        yield from $this->reader->getClassMetadata($listener, AbstractTarget::class);
    }
}
