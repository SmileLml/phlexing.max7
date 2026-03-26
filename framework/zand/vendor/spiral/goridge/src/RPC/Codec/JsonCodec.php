<?php

namespace Spiral\Goridge\RPC\Codec;

use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;
use Spiral\Goridge\RPC\Exception\CodecException;

final class JsonCodec implements CodecInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIndex()
    {
        return Frame::CODEC_JSON;
    }

    /**
     * {@inheritDoc}
     */
    public function encode($payload)
    {
        try {
            $result = \json_encode($payload, 0);
        } catch (\JsonException $e) {
            throw new CodecException(\sprintf('Json encode: %s', $e->getMessage()), (int)$e->getCode(), $e);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     * @param string $payload
     */
    public function decode($payload, $options = null)
    {
        try {
            $flags = 0;

            if (\is_int($options)) {
                $flags |= $options;
            }

            return \json_decode($payload, true, 512, $flags);
        } catch (\JsonException $e) {
            throw new CodecException(\sprintf('Json decode: %s', $e->getMessage()), (int)$e->getCode(), $e);
        }
    }
}
