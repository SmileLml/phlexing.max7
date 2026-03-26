<?php

namespace Spiral\RoadRunner;

use Psr\Log\LoggerInterface;
use Spiral\Goridge\Exception\GoridgeException;
use Spiral\Goridge\Exception\TransportException;
use Spiral\Goridge\Frame;
use Spiral\Goridge\Relay;
use Spiral\Goridge\RelayInterface;
use Spiral\RoadRunner\Exception\RoadRunnerException;
use Spiral\RoadRunner\Internal\StdoutHandler;

/**
 * Accepts connection from RoadRunner server over given Goridge relay.
 *
 * <code>
 * $worker = Worker::create();
 *
 * while ($receivedPayload = $worker->waitPayload()) {
 *      $worker->respond(new Payload("DONE", json_encode($context)));
 * }
 * </code>
 */
class Worker implements WorkerInterface
{
    /**
     * @var int
     */
    private const JSON_ENCODE_FLAGS = \JSON_PRESERVE_ZERO_FRACTION;

    /**
     * @var int
     */
    private const JSON_DECODE_FLAGS = 0;

    /**
     * @var RelayInterface
     */
    private $relay;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param RelayInterface $relay
     * @param bool $interceptSideEffects
     */
    public function __construct($relay, $interceptSideEffects = true)
    {
        $this->relay = $relay;
        $this->logger = new Logger();

        if ($interceptSideEffects) {
            StdoutHandler::register();
        }
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritDoc}
     */
    public function waitPayload()
    {
        $frame = $this->relay->waitFrame();

        $payload = $frame->payload ?? '';

        if ($frame->hasFlag(Frame::CONTROL)) {
            $continue = $this->handleControl($payload);

            return $continue ? $this->waitPayload() : null;
        }

        return new Payload(
            \substr($payload, $frame->options[0]),
            \substr($payload, 0, $frame->options[0])
        );
    }

    /**
     * {@inheritDoc}
     * @param \Spiral\RoadRunner\Payload $payload
     */
    public function respond($payload)
    {
        $this->send($payload->body, $payload->header, $payload->eos);
    }

    /**
     * {@inheritDoc}
     * @param string $error
     */
    public function error($error)
    {
        $frame = new Frame($error, [], Frame::ERROR);

        $this->sendFrame($frame);
    }

    /**
     * {@inheritDoc}
     */
    public function stop()
    {
        $this->send('', $this->encode(['stop' => true]));
    }

    /**
     * @param bool $eos End of stream
     * @param string $body
     * @param string $header
     */
    private function send($body = '', $header = '', $eos = true)
    {
        $frame = new Frame($header . $body, [\strlen($header)]);

        if (!$eos) {
            $frame->byte10 = Frame::BYTE10_STREAM;
        }

        $this->sendFrame($frame);
    }

    /**
     * @param Frame $frame
     */
    private function sendFrame($frame)
    {
        try {
            $this->relay->send($frame);
        } catch (GoridgeException $e) {
            throw new TransportException($e->getMessage(), (int)$e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new RoadRunnerException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * Return true if continue.
     *
     * @param string $header
     * @return bool
     *
     * @throws RoadRunnerException
     */
    private function handleControl($header)
    {
        try {
            $command = $this->decode($header);
        } catch (\JsonException $e) {
            throw new RoadRunnerException('Invalid task header, JSON payload is expected: ' . $e->getMessage());
        }

        switch (true) {
            case !empty($command['pid']):
                $frame = new Frame($this->encode(['pid' => \getmypid()]), [], Frame::CONTROL);
                $this->sendFrame($frame);
                return true;

            case !empty($command['stop']):
                return false;

            default:
                throw new RoadRunnerException('Invalid task header, undefined control package');
        }
    }

    /**
     * @param string $json
     * @return array
     * @throws \JsonException
     */
    private function decode($json)
    {
        $result = \json_decode($json, true, 512, self::JSON_DECODE_FLAGS);

        if (! \is_array($result)) {
            throw new \JsonException('Json message must be an array or object');
        }

        return $result;
    }

    /**
     * @param array $payload
     * @return string
     */
    private function encode($payload)
    {
        return \json_encode($payload, self::JSON_ENCODE_FLAGS);
    }

    /**
     * Create a new RoadRunner {@see Worker} using global
     * environment ({@see Environment}) configuration.
     *
     * @param bool $interceptSideEffects
     * @return self
     */
    public static function create($interceptSideEffects = true)
    {
        return static::createFromEnvironment(Environment::fromGlobals(), $interceptSideEffects);
    }

    /**
     * Create a new RoadRunner {@see Worker} using passed environment
     * configuration.
     *
     * @param EnvironmentInterface $env
     * @param bool $interceptSideEffects
     * @return self
     */
    public static function createFromEnvironment($env, $interceptSideEffects = true)
    {
        return new self(Relay::create($env->getRelayAddress()), $interceptSideEffects);
    }
}
