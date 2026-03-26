<?php

namespace Ramsey\Uuid\Rfc4122;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Validator\ValidatorInterface;

use function preg_match;
use function str_replace;

/**
 * Rfc4122\Validator validates strings as UUIDs of the RFC 4122 variant
 *
 * @psalm-immutable
 */
final class Validator implements ValidatorInterface
{
    private const VALID_PATTERN = '\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-'
        . '[1-8][0-9A-Fa-f]{3}-[ABab89][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}\z';

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
        $uuid = strtolower($uuid);

        return $uuid === Uuid::NIL || $uuid === Uuid::MAX || preg_match('/' . self::VALID_PATTERN . '/Dms', $uuid);
    }
}
