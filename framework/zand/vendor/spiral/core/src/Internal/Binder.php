<?php

namespace Spiral\Core\Internal;

use Psr\Container\ContainerInterface;
use Spiral\Core\Internal\Common\DestructorTrait;
use Spiral\Core\Internal\Common\Registry;
use Spiral\Core\Internal\Config\StateBinder;

/**
 * @internal
 */
final class Binder extends StateBinder
{
    use DestructorTrait;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @param \Spiral\Core\Internal\Common\Registry $constructor
     */
    public function __construct($constructor)
    {
        $constructor->set('binder', $this);

        $this->container = $constructor->get('container', ContainerInterface::class);
        parent::__construct($constructor->get('state', State::class));
    }

    /**
     * @param string $alias
     */
    public function hasInstance($alias)
    {
        if (!$this->container->has($alias)) {
            return false;
        }
        return parent::hasInstance($alias);
    }

    public function destruct()
    {
        unset($this->container);
    }
}
