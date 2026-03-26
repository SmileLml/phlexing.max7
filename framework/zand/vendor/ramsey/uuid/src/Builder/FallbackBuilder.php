<?php

namespace Ramsey\Uuid\Builder;

use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Exception\BuilderNotFoundException;
use Ramsey\Uuid\Exception\UnableToBuildUuidException;
use Ramsey\Uuid\UuidInterface;

/**
 * FallbackBuilder builds a UUID by stepping through a list of UUID builders
 * until a UUID can be constructed without exceptions
 *
 * @psalm-immutable
 */
class FallbackBuilder implements UuidBuilderInterface
{
    /**
     * @var iterable<UuidBuilderInterface>
     */
    private $builders;
    /**
     * @param iterable<UuidBuilderInterface> $builders An array of UUID builders
     */
    public function __construct($builders)
    {
        $this->builders = $builders;
    }

    /**
     * Builds and returns a UuidInterface instance using the first builder that
     * succeeds
     *
     * @param CodecInterface $codec The codec to use for building this instance
     * @param string $bytes The byte string from which to construct a UUID
     *
     * @return UuidInterface an instance of a UUID object
     *
     * @psalm-pure
     */
    public function build($codec, $bytes)
    {
        $lastBuilderException = null;

        foreach ($this->builders as $builder) {
            try {
                return $builder->build($codec, $bytes);
            } catch (UnableToBuildUuidException $exception) {
                $lastBuilderException = $exception;

                continue;
            }
        }

        throw new BuilderNotFoundException(
            'Could not find a suitable builder for the provided codec and fields',
            0,
            $lastBuilderException
        );
    }
}
