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
final class NullOutputFormatterStyle implements OutputFormatterStyleInterface
{
    /**
     * @param string $text
     */
    public function apply($text)
    {
        return $text;
    }

    /**
     * @param string|null $color
     */
    public function setBackground($color = null)
    {
        if (1 > \func_num_args()) {
            trigger_deprecation('symfony/console', '6.2', 'Calling "%s()" without any arguments is deprecated, pass null explicitly instead.', __METHOD__);
        }
        // do nothing
    }

    /**
     * @param string|null $color
     */
    public function setForeground($color = null)
    {
        if (1 > \func_num_args()) {
            trigger_deprecation('symfony/console', '6.2', 'Calling "%s()" without any arguments is deprecated, pass null explicitly instead.', __METHOD__);
        }
        // do nothing
    }

    /**
     * @param string $option
     */
    public function setOption($option)
    {
        // do nothing
    }

    /**
     * @param mixed[] $options
     */
    public function setOptions($options)
    {
        // do nothing
    }

    /**
     * @param string $option
     */
    public function unsetOption($option)
    {
        // do nothing
    }
}
