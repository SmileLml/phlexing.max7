<?php

namespace Spiral\Tokenizer;

use Spiral\Core\Container\InjectorInterface;
use Spiral\Core\Exception\Container\InjectionException;

/**
 * Manages automatic container injections of class and invocation locators.
 *
 * @implements InjectorInterface<ClassLocator>
 */
final class ClassLocatorInjector implements InjectorInterface
{
    /**
     * @readonly
     * @var \Spiral\Tokenizer\Tokenizer
     */
    private $tokenizer;
    /**
     * @param \Spiral\Tokenizer\Tokenizer $tokenizer
     */
    public function __construct($tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @throws InjectionException
     * @param \ReflectionClass $class
     * @param string|null $context
     */
    public function createInjection(
        $class,
        $context = null
    ) {
        return $this->tokenizer->classLocator();
    }
}
