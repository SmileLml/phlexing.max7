<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Output;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * A BufferedOutput that keeps only the last N chars.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class TrimmedBufferOutput extends Output
{
    /**
     * @var int
     */
    private $maxLength;
    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @param int $maxLength
     * @param int|null $verbosity
     * @param bool $decorated
     * @param \Symfony\Component\Console\Formatter\OutputFormatterInterface|null $formatter
     */
    public function __construct($maxLength, $verbosity = self::VERBOSITY_NORMAL, $decorated = false, $formatter = null)
    {
        if ($maxLength <= 0) {
            throw new InvalidArgumentException(sprintf('"%s()" expects a strictly positive maxLength. Got %d.', __METHOD__, $maxLength));
        }

        parent::__construct($verbosity, $decorated, $formatter);
        $this->maxLength = $maxLength;
    }

    /**
     * Empties buffer and returns its content.
     */
    public function fetch()
    {
        $content = $this->buffer;
        $this->buffer = '';

        return $content;
    }

    /**
     * @return void
     * @param string $message
     * @param bool $newline
     */
    protected function doWrite($message, $newline)
    {
        $this->buffer .= $message;

        if ($newline) {
            $this->buffer .= \PHP_EOL;
        }

        $this->buffer = substr($this->buffer, 0 - $this->maxLength);
    }
}
