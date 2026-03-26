<?php

namespace Ramsey\Uuid\Generator;

use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Provider\NodeProviderInterface;
use Ramsey\Uuid\Provider\TimeProviderInterface;

/**
 * TimeGeneratorFactory retrieves a default time generator, based on the
 * environment
 */
class TimeGeneratorFactory
{
    /**
     * @var \Ramsey\Uuid\Provider\NodeProviderInterface
     */
    private $nodeProvider;
    /**
     * @var \Ramsey\Uuid\Converter\TimeConverterInterface
     */
    private $timeConverter;
    /**
     * @var \Ramsey\Uuid\Provider\TimeProviderInterface
     */
    private $timeProvider;
    /**
     * @param \Ramsey\Uuid\Provider\NodeProviderInterface $nodeProvider
     * @param \Ramsey\Uuid\Converter\TimeConverterInterface $timeConverter
     * @param \Ramsey\Uuid\Provider\TimeProviderInterface $timeProvider
     */
    public function __construct($nodeProvider, $timeConverter, $timeProvider)
    {
        $this->nodeProvider = $nodeProvider;
        $this->timeConverter = $timeConverter;
        $this->timeProvider = $timeProvider;
    }

    /**
     * Returns a default time generator, based on the current environment
     */
    public function getGenerator()
    {
        return new DefaultTimeGenerator(
            $this->nodeProvider,
            $this->timeConverter,
            $this->timeProvider
        );
    }
}
