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

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class InformationalChunk extends DataChunk
{
    /**
     * @var mixed[]
     */
    private $status;

    /**
     * @param int $statusCode
     * @param mixed[] $headers
     */
    public function __construct($statusCode, $headers)
    {
        $this->status = [$statusCode, $headers];
    }

    public function getInformationalStatus()
    {
        return $this->status;
    }
}
