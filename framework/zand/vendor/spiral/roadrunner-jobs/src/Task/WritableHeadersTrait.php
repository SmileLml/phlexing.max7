<?php

namespace Spiral\RoadRunner\Jobs\Task;

/**
 * @mixin WritableHeadersInterface
 * @psalm-require-implements WritableHeadersInterface
 * @psalm-immutable
 */
trait WritableHeadersTrait
{
    use HeadersTrait;

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string|iterable<non-empty-string> $value
     * @psalm-return static
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @param string $name
     */
    public function withHeader($name, $value)
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        $value = \is_iterable($value) ? $value : [$value];

        $self = clone $this;
        $self->headers[$name] = [];

        foreach ($value as $item) {
            $self->headers[$name][] = $item;
        }

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string|iterable<non-empty-string> $value
     * @psalm-return static
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @param string $name
     */
    public function withAddedHeader($name, $value)
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        /** @var iterable<non-empty-string> $value */
        $value = \is_iterable($value) ? $value : [$value];

        /** @var array<non-empty-string> $headers */
        $headers = $this->headers[$name] ?? [];

        foreach ($value as $item) {
            $headers[] = $item;
        }

        return $this->withHeader($name, $headers);
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-return static
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @param string $name
     */
    public function withoutHeader($name)
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        if (!isset($this->headers[$name])) {
            return $this;
        }

        $self = clone $this;
        unset($self->headers[$name]);
        return $self;
    }
}
