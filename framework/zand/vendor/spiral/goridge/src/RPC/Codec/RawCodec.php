<?php

namespace Spiral\Goridge\RPC\Codec;

use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;
use Spiral\Goridge\RPC\Exception\CodecException;

final class RawCodec implements CodecInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIndex()
    {
        return Frame::CODEC_RAW;
    }

    /**
     * {@inheritDoc}
     */
    public function encode($payload)
    {
        if (!is_string($payload)) {
            throw new CodecException(
                sprintf('Only string payloads can be send using RawCodec, %s given', gettype($payload))
            );
        }

        return $payload;
    }

    /**
     * {@inheritDoc}
     * @param string $payload
     */
    public function decode($payload, $options = null)
    {
        return $payload;
    }
}
