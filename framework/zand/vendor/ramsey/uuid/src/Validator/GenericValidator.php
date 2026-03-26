<?php

namespace Ramsey\Uuid\Validator;

use Ramsey\Uuid\Uuid;

use function preg_match;
use function str_replace;

/**
 * GenericValidator validates strings as UUIDs of any variant
 *
 * @psalm-immutable
 */
final class GenericValidator implements ValidatorInterface
{
    /**
     * Regular expression pattern for matching a UUID of any variant.
     */
    private const VALID_PATTERN = '\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}\z';

    /**
     * @psalm-return non-empty-string
     * @psalm-suppress MoreSpecificReturnType we know that the retrieved `string` is never empty
     * @psalm-suppress LessSpecificReturnStatement we know that the retrieved `string` is never empty
     */
    public function getPattern()
    {
        return self::VALID_PATTERN;
    }

    /**
     * @param string $uuid
     */
    public function validate($uuid)
    {
        $uuid = str_replace(['urn:', 'uuid:', 'URN:', 'UUID:', '{', '}'], '', $uuid);

        return $uuid === Uuid::NIL || preg_match('/' . self::VALID_PATTERN . '/Dms', $uuid);
    }
}
