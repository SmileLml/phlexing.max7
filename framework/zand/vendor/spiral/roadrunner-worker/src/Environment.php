<?php

namespace Spiral\RoadRunner;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Environment\Mode;

/**
 * @psalm-import-type ModeType from Mode
 * @psalm-type EnvironmentVariables = array {
 *      RR_MODE?:   ModeType|string,
 *      RR_RELAY?:  string,
 *      RR_RPC?:    string,
 * }
 * @see Mode
 */
class Environment implements EnvironmentInterface
{
    /**
     * @var EnvironmentVariables
     */
    private $env;

    /**
     * @param mixed[] $env
     */
    public function __construct($env = [])
    {
        $this->env = $env;
    }

    /**
     * {@inheritDoc}
     */
    public function getMode()
    {
        return $this->get('RR_MODE', '');
    }

    /**
     * {@inheritDoc}
     */
    public function getRelayAddress()
    {
        return $this->get('RR_RELAY', 'pipes');
    }

    /**
     * {@inheritDoc}
     */
    public function getRPCAddress()
    {
        return $this->get('RR_RPC', 'tcp://127.0.0.1:6001');
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    private function get($name, $default = '')
    {
        if (isset($this->env[$name]) || \array_key_exists($name, $this->env)) {
            /** @psalm-suppress RedundantCastGivenDocblockType */
            return (string)$this->env[$name];
        }

        return $default;
    }

    /**
     * @return self
     */
    public static function fromGlobals()
    {
        /** @var array<string, string> $env */
        $env = \array_merge($_ENV, $_SERVER);

        return new self($env);
    }
}
