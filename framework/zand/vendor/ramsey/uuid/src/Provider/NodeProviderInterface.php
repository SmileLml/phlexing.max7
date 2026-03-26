<?php

namespace Ramsey\Uuid\Provider;

use Ramsey\Uuid\Type\Hexadecimal;

/**
 * A node provider retrieves or generates a node ID
 */
interface NodeProviderInterface
{
    /**
     * Returns a node ID
     *
     * @return Hexadecimal The node ID as a hexadecimal string
     */
    public function getNode();
}
