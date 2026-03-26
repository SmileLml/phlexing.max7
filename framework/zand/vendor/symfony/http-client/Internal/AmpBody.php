<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient\Internal;

use Amp\ByteStream\InputStream;
use Amp\ByteStream\ResourceInputStream;
use Amp\Http\Client\RequestBody;
use Amp\Promise;
use Amp\Success;
use Symfony\Component\HttpClient\Exception\TransportException;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class AmpBody implements RequestBody, InputStream
{
    /**
     * @var \Amp\ByteStream\ResourceInputStream|\Closure|string
     */
    private $body;
    /**
     * @var mixed[]
     */
    private $info;
    /**
     * @var \Closure
     */
    private $onProgress;
    /**
     * @var int|null
     */
    private $offset = 0;
    /**
     * @var int
     */
    private $length = -1;
    /**
     * @var int|null
     */
    private $uploaded;

    /**
     * @param \Closure|resource|string $body
     * @param \Closure $onProgress
     */
    public function __construct($body, &$info, $onProgress)
    {
        $this->info = &$info;
        $this->onProgress = $onProgress;

        if (\is_resource($body)) {
            $this->offset = ftell($body);
            $this->length = fstat($body)['size'];
            $this->body = new ResourceInputStream($body);
        } elseif (\is_string($body)) {
            $this->length = \strlen($body);
            $this->body = $body;
        } else {
            $this->body = $body;
        }
    }

    public function createBodyStream()
    {
        if (null !== $this->uploaded) {
            $this->uploaded = null;

            if (\is_string($this->body)) {
                $this->offset = 0;
            } elseif ($this->body instanceof ResourceInputStream) {
                fseek($this->body->getResource(), $this->offset);
            }
        }

        return $this;
    }

    public function getHeaders()
    {
        return new Success([]);
    }

    public function getBodyLength()
    {
        return new Success($this->length - $this->offset);
    }

    public function read()
    {
        $this->info['size_upload'] += $this->uploaded;
        $this->uploaded = 0;
        ($this->onProgress)();

        $chunk = $this->doRead();
        $chunk->onResolve(function ($e, $data) {
            if (null !== $data) {
                $this->uploaded = \strlen($data);
            } else {
                $this->info['upload_content_length'] = $this->info['size_upload'];
            }
        });

        return $chunk;
    }

    /**
     * @param \Amp\Http\Client\RequestBody $body
     */
    public static function rewind($body)
    {
        if (!$body instanceof self) {
            return $body;
        }

        $body->uploaded = null;

        if ($body->body instanceof ResourceInputStream) {
            fseek($body->body->getResource(), $body->offset);

            return new $body($body->body, $body->info, $body->onProgress);
        }

        if (\is_string($body->body)) {
            $body->offset = 0;
        }

        return $body;
    }

    private function doRead()
    {
        if ($this->body instanceof ResourceInputStream) {
            return $this->body->read();
        }

        if (null === $this->offset || !$this->length) {
            return new Success();
        }

        if (\is_string($this->body)) {
            $this->offset = null;

            return new Success($this->body);
        }

        if ('' === $data = ($this->body)(16372)) {
            $this->offset = null;

            return new Success();
        }

        if (!\is_string($data)) {
            throw new TransportException(sprintf('Return value of the "body" option callback must be string, "%s" returned.', get_debug_type($data)));
        }

        return new Success($data);
    }
}
