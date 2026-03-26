<?php

namespace Spiral\Tokenizer;

final class ScopedClassLocator implements ScopedClassesInterface
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
     * @param object|string|null $target
     * @param string $scope
     */
    public function getScopedClasses($scope, $target = null)
    {
        return $this->tokenizer->scopedClassLocator($scope)->getClasses($target);
    }
}
