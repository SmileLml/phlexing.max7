<?php

namespace Spiral\RoadRunner\KeyValue\Serializer;

use Spiral\RoadRunner\KeyValue\Exception\SerializationException;

class SodiumSerializer implements SerializerInterface
{
    /**
     * @var \Spiral\RoadRunner\KeyValue\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key The key is used to decrypt and encrypt values;
     *                    The key must be generated using {@see sodium_crypto_box_keypair()}.
     * @param \Spiral\RoadRunner\KeyValue\Serializer\SerializerInterface $serializer
     */
    public function __construct($serializer, $key)
    {
        $this->assertAvailable();

        $this->key = $key;
        $this->serializer = $serializer;
    }

    /**
     * @codeCoverageIgnore Reason: Ignore environment-aware assertions
     */
    private function assertAvailable()
    {
        if (! \function_exists('\\sodium_crypto_box_seal')) {
            throw new \LogicException('The "ext-sodium" PHP extension is not available');
        }
    }

    /**
     * @param mixed $value
     */
    public function serialize($value)
    {
        try {
            return \sodium_crypto_box_seal(
                $this->serializer->serialize($value),
                \sodium_crypto_box_publickey($this->key)
            );
        } catch (\SodiumException $e) {
            throw new SerializationException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @return mixed
     * @param string $value
     */
    public function unserialize($value)
    {
        try {
            $result = \sodium_crypto_box_seal_open($value, $this->key);

            if ($result === false) {
                throw new SerializationException(
                    'Can not decode the received data. Please make sure '.
                    'the encryption key matches the one used to encrypt this data'
                );
            }

            return $this->serializer->unserialize($result);
        } catch (\SodiumException $e) {
            throw new SerializationException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
