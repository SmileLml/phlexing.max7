<?php

namespace Spiral\Core\Container;

use Spiral\Core\Exception\Container\AutowireException;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\FactoryInterface;

/**
 * Provides ability to delegate option to container.
 *
 * @template TObject of object
 */
final class Autowire
{
    /** @var null|TObject */
    private $target;
    /**
     * @var non-empty-string|class-string<TObject>
     * @readonly
     */
    private $alias;
    /**
     * @readonly
     * @var mixed[]
     */
    private $parameters = [];

    /**
     * Autowire constructor.
     *
     * @param string $alias
     * @param mixed[] $parameters
     */
    public function __construct($alias, $parameters = [])
    {
        $this->alias = $alias;
        $this->parameters = $parameters;
    }

    /**
     * @return $this
     * @param mixed[] $anArray
     */
    public static function __set_state($anArray)
    {
        return new self($anArray['alias'], $anArray['parameters']);
    }

    /**
     * Init the autowire based on string or array definition.
     *
     * @throws AutowireException
     * @param mixed $definition
     */
    public static function wire($definition)
    {
        if ($definition instanceof self) {
            return $definition;
        }

        if (\is_string($definition)) {
            return new self($definition);
        }

        if (\is_array($definition) && isset($definition['class'])) {
            return new self(
                $definition['class'],
                $definition['options'] ?? $definition['params'] ?? []
            );
        }

        if (\is_object($definition)) {
            $autowire = new self(get_class($definition), []);
            $autowire->target = $definition;
            return $autowire;
        }

        throw new AutowireException('Invalid autowire definition.');
    }

    /**
     * @param array $parameters Context specific parameters (always prior to declared ones).
     * @return TObject
     *
     * @throws AutowireException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     */
    public function resolve(FactoryInterface $factory, array $parameters = [])
    {
        return $this->target ?? $factory->make($this->alias, \array_merge($this->parameters, $parameters));
    }
}
