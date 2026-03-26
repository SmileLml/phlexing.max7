<?php

namespace Spiral\Goridge\RPC;

use Spiral\Goridge\Frame;
use Spiral\Goridge\Relay;
use Spiral\Goridge\RelayInterface;
use Spiral\Goridge\RPC\Codec\JsonCodec;
use Spiral\Goridge\RPC\Exception\RPCException;
use Spiral\Goridge\RPC\Exception\ServiceException;
use Spiral\RoadRunner\Environment;
use Spiral\RoadRunner\EnvironmentInterface;

class RPC implements RPCInterface
{
    /**
     * @var RelayInterface
     */
    private $relay;

    /**
     * @var CodecInterface
     */
    private $codec;

    /**
     * @var string|null
     */
    private $service;

    /**
     * @var positive-int
     */
    private static $seq = 1;

    /**
     * @param RelayInterface $relay
     * @param CodecInterface|null $codec
     */
    public function __construct($relay, $codec = null)
    {
        $this->relay = $relay;
        $this->codec = $codec ?? new JsonCodec();
    }

    /**
     * {@inheritDoc}
     * @psalm-pure
     * @param string $service
     */
    public function withServicePrefix($service)
    {
        /** @psalm-suppress ImpureVariable */
        $rpc = clone $this;
        $rpc->service = $service;

        return $rpc;
    }

    /**
     * {@inheritDoc}
     * @psalm-pure
     * @param \Spiral\Goridge\RPC\CodecInterface $codec
     */
    public function withCodec($codec)
    {
        /** @psalm-suppress ImpureVariable */
        $rpc = clone $this;
        $rpc->codec = $codec;

        return $rpc;
    }

    /**
     * {@inheritDoc}
     * @param string $method
     */
    public function call($method, $payload, $options = null)
    {
        $this->relay->send($this->packFrame($method, $payload));

        // wait for the frame confirmation
        $frame = $this->relay->waitFrame();

        if (\count($frame->options) !== 2) {
            throw new RPCException('Invalid RPC frame, options missing');
        }

        if ($frame->options[0] !== self::$seq) {
            throw new RPCException('Invalid RPC frame, sequence mismatch');
        }

        self::$seq++;

        return $this->decodeResponse($frame, $options);
    }

    /**
     * @param string $connection
     * @param CodecInterface|null $codec
     * @return RPCInterface
     */
    public static function create($connection, $codec = null)
    {
        $relay = Relay::create($connection);

        return new self($relay, $codec);
    }

    /**
     * @param EnvironmentInterface $env
     * @param CodecInterface|null $codec
     * @return RPCInterface
     *
     * @psalm-suppress UndefinedClass
     */
    public static function fromEnvironment($env, $codec = null)
    {
        /** @var string $address */
        $address = $env->getRPCAddress();
        return self::create($address, $codec);
    }

    /**
     * @param CodecInterface|null $codec
     * @return RPCInterface
     *
     * @psalm-suppress UndefinedClass
     */
    public static function fromGlobals($codec = null)
    {
        /** @var EnvironmentInterface $env */
        $env = Environment::fromGlobals();
        return self::fromEnvironment($env, $codec);
    }

    /**
     * @param Frame $frame
     * @param mixed|null $options
     * @return mixed
     *
     * @throws Exception\ServiceException
     */
    private function decodeResponse($frame, $options = null)
    {
        // exclude method name
        $body = \substr((string)$frame->payload, $frame->options[1]);

        if ($frame->hasFlag(Frame::ERROR)) {
            $name = $this->relay instanceof \Stringable ? (string)$this->relay : \get_class($this->relay);

            throw new ServiceException(\sprintf("Error '%s' on %s", $body, $name));
        }

        return $this->codec->decode($body, $options);
    }

    /**
     * @param string $method
     * @param mixed $payload
     * @return Frame
     */
    private function packFrame($method, $payload)
    {
        if ($this->service !== null) {
            $method = $this->service . '.' . \ucfirst($method);
        }

        $body = $method . $this->codec->encode($payload);
        return new Frame($body, [self::$seq, \strlen($method)], $this->codec->getIndex());
    }
}
