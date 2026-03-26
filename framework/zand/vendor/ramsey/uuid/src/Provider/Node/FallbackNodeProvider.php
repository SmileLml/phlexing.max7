<?php

namespace Ramsey\Uuid\Provider\Node;

use Ramsey\Uuid\Exception\NodeException;
use Ramsey\Uuid\Provider\NodeProviderInterface;
use Ramsey\Uuid\Type\Hexadecimal;

/**
 * FallbackNodeProvider retrieves the system node ID by stepping through a list
 * of providers until a node ID can be obtained
 */
class FallbackNodeProvider implements NodeProviderInterface
{
    /**
     * @var iterable<NodeProviderInterface>
     */
    private $providers;
    /**
     * @param iterable<NodeProviderInterface> $providers Array of node providers
     */
    public function __construct($providers)
    {
        $this->providers = $providers;
    }

    public function getNode()
    {
        $lastProviderException = null;

        foreach ($this->providers as $provider) {
            try {
                return $provider->getNode();
            } catch (NodeException $exception) {
                $lastProviderException = $exception;

                continue;
            }
        }

        throw new NodeException(
            'Unable to find a suitable node provider',
            0,
            $lastProviderException
        );
    }
}
