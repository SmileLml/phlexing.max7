<?php

namespace Spiral\RoadRunner\Jobs\Task;

/**
 * @mixin ProvidesHeadersInterface
 * @psalm-require-implements ProvidesHeadersInterface
 * @psalm-immutable
 */
trait HeadersTrait
{
    /**
     * @var array<non-empty-string, array<string>>
     */
    protected $headers = [];

    /**
     * {@inheritDoc}
     *
     * @psalm-return array<non-empty-string, array<string>>
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name Header field name.
     * @psalm-return bool
     * @param string $name
     */
    public function hasHeader($name)
    {
        return isset($this->headers[$name]) && \count($this->headers[$name]) > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-return array<string>
     * @param string $name
     */
    public function getHeader($name)
    {
        return $this->headers[$name] ?? [];
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-return string
     * @param string $name
     */
    public function getHeaderLine($name)
    {
        return \implode(',', $this->getHeader($name));
    }
}
