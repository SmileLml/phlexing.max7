<?php

namespace Ramsey\Uuid\Rfc4122;

use Ramsey\Uuid\Uuid;

/**
 * Provides common functionality for handling the version, as defined by RFC 4122
 *
 * @psalm-immutable
 */
trait VersionTrait
{
    /**
     * Returns the version
     */
    abstract public function getVersion();

    /**
     * Returns true if these fields represent a max UUID
     */
    abstract public function isMax();

    /**
     * Returns true if these fields represent a nil UUID
     */
    abstract public function isNil();

    /**
     * Returns true if the version matches one of those defined by RFC 4122
     *
     * @return bool True if the UUID version is valid, false otherwise
     */
    private function isCorrectVersion()
    {
        if ($this->isNil() || $this->isMax()) {
            return true;
        }

        switch ($this->getVersion()) {
            case Uuid::UUID_TYPE_TIME:
            case Uuid::UUID_TYPE_DCE_SECURITY:
            case Uuid::UUID_TYPE_HASH_MD5:
            case Uuid::UUID_TYPE_RANDOM:
            case Uuid::UUID_TYPE_HASH_SHA1:
            case Uuid::UUID_TYPE_REORDERED_TIME:
            case Uuid::UUID_TYPE_UNIX_TIME:
            case Uuid::UUID_TYPE_CUSTOM:
                return true;
            default:
                return false;
        }
    }
}
