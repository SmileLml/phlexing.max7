<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Iterator;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class LazyIterator implements \IteratorAggregate
{
    /**
     * @var \Closure
     */
    private $iteratorFactory;

    /**
     * @param callable $iteratorFactory
     */
    public function __construct($iteratorFactory)
    {
        $this->iteratorFactory = \Closure::fromCallable($iteratorFactory);
    }

    public function getIterator()
    {
        yield from ($this->iteratorFactory)();
    }
}
