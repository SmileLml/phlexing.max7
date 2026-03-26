<?php

namespace Ramsey\Uuid\Provider;

use Ramsey\Uuid\Rfc4122\UuidV2;
use Ramsey\Uuid\Type\Integer as IntegerObject;

/**
 * A DCE provider provides access to local domain identifiers for version 2,
 * DCE Security, UUIDs
 *
 * @see UuidV2
 */
interface DceSecurityProviderInterface
{
    /**
     * Returns a user identifier for the system
     *
     * @link https://en.wikipedia.org/wiki/User_identifier User identifier
     */
    public function getUid();

    /**
     * Returns a group identifier for the system
     *
     * @link https://en.wikipedia.org/wiki/Group_identifier Group identifier
     */
    public function getGid();
}
