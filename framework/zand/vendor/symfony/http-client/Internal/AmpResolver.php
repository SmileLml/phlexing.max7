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

use Amp\Dns;
use Amp\Dns\Record;
use Amp\Promise;
use Amp\Success;

/**
 * Handles local overrides for the DNS resolver.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class AmpResolver implements Dns\Resolver
{
    /**
     * @var mixed[]
     */
    private $dnsMap;

    /**
     * @param mixed[] $dnsMap
     */
    public function __construct(&$dnsMap)
    {
        $this->dnsMap = &$dnsMap;
    }

    /**
     * @param string $name
     * @param int|null $typeRestriction
     */
    public function resolve($name, $typeRestriction = null)
    {
        if (!isset($this->dnsMap[$name]) || !\in_array($typeRestriction, [Record::A, null], true)) {
            return Dns\resolver()->resolve($name, $typeRestriction);
        }

        return new Success([new Record($this->dnsMap[$name], Record::A, null)]);
    }

    /**
     * @param string $name
     * @param int $type
     */
    public function query($name, $type)
    {
        if (!isset($this->dnsMap[$name]) || Record::A !== $type) {
            return Dns\resolver()->query($name, $type);
        }

        return new Success([new Record($this->dnsMap[$name], Record::A, null)]);
    }
}
