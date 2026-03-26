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

use Symfony\Component\Console\Formatter\NullOutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * NullOutput suppresses all output.
 *
 *     $output = new NullOutput();
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Tobias Schultze <http://tobion.de>
 */
class NullOutput implements OutputInterface
{
    /**
     * @var \Symfony\Component\Console\Formatter\NullOutputFormatter
     */
    private $formatter;

    /**
     * @return void
     * @param \Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter
     */
    public function setFormatter($formatter)
    {
        // do nothing
    }

    public function getFormatter()
    {
        // to comply with the interface we must return a OutputFormatterInterface
        return $this->formatter = $this->formatter ?? new NullOutputFormatter();
    }

    /**
     * @return void
     * @param bool $decorated
     */
    public function setDecorated($decorated)
    {
        // do nothing
    }

    public function isDecorated()
    {
        return false;
    }

    /**
     * @return void
     * @param int $level
     */
    public function setVerbosity($level)
    {
        // do nothing
    }

    public function getVerbosity()
    {
        return self::VERBOSITY_QUIET;
    }

    public function isQuiet()
    {
        return true;
    }

    public function isVerbose()
    {
        return false;
    }

    public function isVeryVerbose()
    {
        return false;
    }

    public function isDebug()
    {
        return false;
    }

    /**
     * @return void
     * @param string|mixed[] $messages
     * @param int $options
     */
    public function writeln($messages, $options = self::OUTPUT_NORMAL)
    {
        // do nothing
    }

    /**
     * @return void
     * @param string|mixed[] $messages
     * @param bool $newline
     * @param int $options
     */
    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
    {
        // do nothing
    }
}
