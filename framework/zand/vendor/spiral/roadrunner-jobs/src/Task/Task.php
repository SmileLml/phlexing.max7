<?php

namespace Spiral\RoadRunner\Jobs\Task;

/**
 * @psalm-immutable
 * @psalm-allow-private-mutation
 */
abstract class Task implements TaskInterface
{
    use HeadersTrait;

    /**
     * @var non-empty-string
     */
    protected $name;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @param string $name
     * @param array $payload
     * @param array<non-empty-string, array<string>> $headers
     */
    public function __construct($name, $payload = [], $headers = [])
    {
        assert($name !== '', 'Precondition [job !== ""] failed');

        $this->name = $name;
        $this->payload = $payload;
        $this->headers = $headers;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($key, $default = null)
    {
        // Note: The following code "$this->payload[$key] ?? $default" will
        // work faster, but it will not work correctly if the key contains
        // a NULL value.
        return $this->hasValue($key) ? $this->payload[$key] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function hasValue($key)
    {
        // Array lookup optimization: Op ISSET_ISEMPTY_VAR faster than direct
        // array_key_exists function execution.
        return isset($this->payload[$key]) || \array_key_exists($key, $this->payload);
    }
}
