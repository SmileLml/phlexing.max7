<?php

namespace Spiral\Core\Internal;

use Spiral\Core\Exception\Scope\NamedScopeDuplicationException;

/**
 * @internal
 */
final class Scope
{
    /**
     * @var \Spiral\Core\Container|null
     */
    private $parent;
    /**
     * @var $this|null
     */
    private $parentScope;
    /**
     * @readonly
     * @var string|null
     */
    private $scopeName;
    /**
     * @param string|null $scopeName
     */
    public function __construct($scopeName = null)
    {
        $this->scopeName = $scopeName;
    }

    public function getScopeName()
    {
        return $this->scopeName;
    }

    /**
     * Link the current scope with its parent scope and container.
     *
     * @throws NamedScopeDuplicationException
     */
    public function setParent(\Spiral\Core\Container $parent, self $parentScope)
    {
        $this->parent = $parent;
        $this->parentScope = $parentScope;

        // Check a scope with the same name is not already registered
        if ($this->scopeName !== null) {
            $tmp = $this;
            while ($tmp->parentScope !== null) {
                $tmp = $tmp->parentScope;
                if ($tmp->scopeName === $this->scopeName) {
                    throw new NamedScopeDuplicationException($this->scopeName);
                }
            }
        }
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Return list of parent scope names.
     * The first element is the current scope name, and the next is the closest parent scope name...
     *
     * @return array<int<0, max>, string|null>
     */
    public function getParentScopeNames()
    {
        $result = [$this->scopeName];

        $parent = $this;
        while ($parent->parentScope !== null) {
            $parent = $parent->parentScope;
            $result[] = $parent->scopeName;
        }

        return $result;
    }

    public function getParentScope()
    {
        return $this->parentScope;
    }

    public function destruct()
    {
        $this->parent = null;
        $this->parentScope = null;
    }
}
