<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient\Chunk;

use Symfony\Contracts\HttpClient\ChunkInterface;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class DataChunk implements ChunkInterface
{
    /**
     * @var int
     */
    private $offset = 0;
    /**
     * @var string
     */
    private $content = '';

    /**
     * @param int $offset
     * @param string $content
     */
    public function __construct($offset = 0, $content = '')
    {
        $this->offset = $offset;
        $this->content = $content;
    }

    public function isTimeout()
    {
        return false;
    }

    public function isFirst()
    {
        return false;
    }

    public function isLast()
    {
        return false;
    }

    public function getInformationalStatus()
    {
        return null;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getError()
    {
        return null;
    }
}
