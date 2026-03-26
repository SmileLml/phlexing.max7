<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Formatter;

/**
 * @author Tien Xuan Vo <tien.xuan.vo@gmail.com>
 */
final class NullOutputFormatter implements OutputFormatterInterface
{
    /**
     * @var \Symfony\Component\Console\Formatter\NullOutputFormatterStyle
     */
    private $style;

    /**
     * @param string|null $message
     */
    public function format($message)
    {
        return null;
    }

    /**
     * @param string $name
     */
    public function getStyle($name)
    {
        // to comply with the interface we must return a OutputFormatterStyleInterface
        return $this->style = $this->style ?? new NullOutputFormatterStyle();
    }

    /**
     * @param string $name
     */
    public function hasStyle($name)
    {
        return false;
    }

    public function isDecorated()
    {
        return false;
    }

    /**
     * @param bool $decorated
     */
    public function setDecorated($decorated)
    {
        // do nothing
    }

    /**
     * @param string $name
     * @param \Symfony\Component\Console\Formatter\OutputFormatterStyleInterface $style
     */
    public function setStyle($name, $style)
    {
        // do nothing
    }
}
