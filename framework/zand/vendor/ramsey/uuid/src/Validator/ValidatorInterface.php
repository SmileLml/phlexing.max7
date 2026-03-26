<?php

namespace Ramsey\Uuid\Validator;

/**
 * A validator validates a string as a proper UUID
 *
 * @psalm-immutable
 */
interface ValidatorInterface
{
    /**
     * Returns the regular expression pattern used by this validator
     *
     * @return string The regular expression pattern this validator uses
     *
     * @psalm-return non-empty-string
     */
    public function getPattern();

    /**
     * Returns true if the provided string represents a UUID
     *
     * @param string $uuid The string to validate as a UUID
     *
     * @return bool True if the string is a valid UUID, false otherwise
     */
    public function validate($uuid);
}
