<?php

namespace Spiral\Tokenizer\Listener;

use Spiral\Tokenizer\Attribute\AbstractTarget;
use Spiral\Tokenizer\ClassesInterface;
use Spiral\Tokenizer\ScopedClassesInterface;
use Spiral\Tokenizer\Traits\TargetTrait;

/**
 * @internal
 */
final class ClassLocatorByTarget
{
    use TargetTrait;
    /**
     * @readonly
     * @var \Spiral\Tokenizer\ClassesInterface
     */
    private $classes;
    /**
     * @readonly
     * @var \Spiral\Tokenizer\ScopedClassesInterface
     */
    private $scopedClasses;
    /**
     * @param \Spiral\Tokenizer\ClassesInterface $classes
     * @param \Spiral\Tokenizer\ScopedClassesInterface $scopedClasses
     */
    public function __construct($classes, $scopedClasses)
    {
        $this->classes = $classes;
        $this->scopedClasses = $scopedClasses;
    }

    /**
     * @return class-string[]
     * @param \Spiral\Tokenizer\Attribute\AbstractTarget $target
     */
    public function getClasses($target)
    {
        return \iterator_to_array($target->filter($this->findClasses($target)));
    }

    /**
     * @return \ReflectionClass[]
     * @param \Spiral\Tokenizer\Attribute\AbstractTarget $target
     */
    private function findClasses($target)
    {
        $scope = $target->getScope();

        // If scope for listener attribute is defined, we should use scoped class locator
        return $scope !== null
            ? $this->scopedClasses->getScopedClasses($scope)
            : $this->classes->getClasses();
    }
}
