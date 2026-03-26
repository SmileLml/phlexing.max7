<?php

namespace Ramsey\Uuid;

use Ramsey\Uuid\Builder\FallbackBuilder;
use Ramsey\Uuid\Builder\UuidBuilderInterface;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Codec\GuidStringCodec;
use Ramsey\Uuid\Codec\StringCodec;
use Ramsey\Uuid\Converter\Number\GenericNumberConverter;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Converter\Time\GenericTimeConverter;
use Ramsey\Uuid\Converter\Time\PhpTimeConverter;
use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Generator\DceSecurityGenerator;
use Ramsey\Uuid\Generator\DceSecurityGeneratorInterface;
use Ramsey\Uuid\Generator\NameGeneratorFactory;
use Ramsey\Uuid\Generator\NameGeneratorInterface;
use Ramsey\Uuid\Generator\PeclUuidNameGenerator;
use Ramsey\Uuid\Generator\PeclUuidRandomGenerator;
use Ramsey\Uuid\Generator\PeclUuidTimeGenerator;
use Ramsey\Uuid\Generator\RandomGeneratorFactory;
use Ramsey\Uuid\Generator\RandomGeneratorInterface;
use Ramsey\Uuid\Generator\TimeGeneratorFactory;
use Ramsey\Uuid\Generator\TimeGeneratorInterface;
use Ramsey\Uuid\Generator\UnixTimeGenerator;
use Ramsey\Uuid\Guid\GuidBuilder;
use Ramsey\Uuid\Math\BrickMathCalculator;
use Ramsey\Uuid\Math\CalculatorInterface;
use Ramsey\Uuid\Nonstandard\UuidBuilder as NonstandardUuidBuilder;
use Ramsey\Uuid\Provider\Dce\SystemDceSecurityProvider;
use Ramsey\Uuid\Provider\DceSecurityProviderInterface;
use Ramsey\Uuid\Provider\Node\FallbackNodeProvider;
use Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Ramsey\Uuid\Provider\Node\SystemNodeProvider;
use Ramsey\Uuid\Provider\NodeProviderInterface;
use Ramsey\Uuid\Provider\Time\SystemTimeProvider;
use Ramsey\Uuid\Provider\TimeProviderInterface;
use Ramsey\Uuid\Rfc4122\UuidBuilder as Rfc4122UuidBuilder;
use Ramsey\Uuid\Validator\GenericValidator;
use Ramsey\Uuid\Validator\ValidatorInterface;

use const PHP_INT_SIZE;

/**
 * FeatureSet detects and exposes available features in the current environment
 *
 * A feature set is used by UuidFactory to determine the available features and
 * capabilities of the environment.
 */
class FeatureSet
{
    /**
     * @var \Ramsey\Uuid\Provider\TimeProviderInterface|null
     */
    private $timeProvider;
    /**
     * @var \Ramsey\Uuid\Math\CalculatorInterface
     */
    private $calculator;
    /**
     * @var \Ramsey\Uuid\Codec\CodecInterface
     */
    private $codec;
    /**
     * @var \Ramsey\Uuid\Generator\DceSecurityGeneratorInterface
     */
    private $dceSecurityGenerator;
    /**
     * @var \Ramsey\Uuid\Generator\NameGeneratorInterface
     */
    private $nameGenerator;
    /**
     * @var \Ramsey\Uuid\Provider\NodeProviderInterface
     */
    private $nodeProvider;
    /**
     * @var \Ramsey\Uuid\Converter\NumberConverterInterface
     */
    private $numberConverter;
    /**
     * @var \Ramsey\Uuid\Generator\RandomGeneratorInterface
     */
    private $randomGenerator;
    /**
     * @var \Ramsey\Uuid\Converter\TimeConverterInterface
     */
    private $timeConverter;
    /**
     * @var \Ramsey\Uuid\Generator\TimeGeneratorInterface
     */
    private $timeGenerator;
    /**
     * @var \Ramsey\Uuid\Generator\TimeGeneratorInterface
     */
    private $unixTimeGenerator;
    /**
     * @var \Ramsey\Uuid\Builder\UuidBuilderInterface
     */
    private $builder;
    /**
     * @var \Ramsey\Uuid\Validator\ValidatorInterface
     */
    private $validator;
    /**
     * @var bool
     */
    private $force32Bit = false;
    /**
     * @var bool
     */
    private $ignoreSystemNode = false;
    /**
     * @var bool
     */
    private $enablePecl = false;

    /**
     * @param bool $useGuids True build UUIDs using the GuidStringCodec
     * @param bool $force32Bit True to force the use of 32-bit functionality
     *     (primarily for testing purposes)
     * @param bool $forceNoBigNumber (obsolete)
     * @param bool $ignoreSystemNode True to disable attempts to check for the
     *     system node ID (primarily for testing purposes)
     * @param bool $enablePecl True to enable the use of the PeclUuidTimeGenerator
     *     to generate version 1 UUIDs
     */
    public function __construct(
        $useGuids = false,
        $force32Bit = false,
        $forceNoBigNumber = false,
        $ignoreSystemNode = false,
        $enablePecl = false
    ) {
        $this->force32Bit = $force32Bit;
        $this->ignoreSystemNode = $ignoreSystemNode;
        $this->enablePecl = $enablePecl;
        $this->randomGenerator = $this->buildRandomGenerator();
        $this->setCalculator(new BrickMathCalculator());
        $this->builder = $this->buildUuidBuilder($useGuids);
        $this->codec = $this->buildCodec($useGuids);
        $this->nodeProvider = $this->buildNodeProvider();
        $this->nameGenerator = $this->buildNameGenerator();
        $this->setTimeProvider(new SystemTimeProvider());
        $this->setDceSecurityProvider(new SystemDceSecurityProvider());
        $this->validator = new GenericValidator();

        assert($this->timeProvider !== null);
        $this->unixTimeGenerator = $this->buildUnixTimeGenerator();
    }

    /**
     * Returns the builder configured for this environment
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Returns the calculator configured for this environment
     */
    public function getCalculator()
    {
        return $this->calculator;
    }

    /**
     * Returns the codec configured for this environment
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * Returns the DCE Security generator configured for this environment
     */
    public function getDceSecurityGenerator()
    {
        return $this->dceSecurityGenerator;
    }

    /**
     * Returns the name generator configured for this environment
     */
    public function getNameGenerator()
    {
        return $this->nameGenerator;
    }

    /**
     * Returns the node provider configured for this environment
     */
    public function getNodeProvider()
    {
        return $this->nodeProvider;
    }

    /**
     * Returns the number converter configured for this environment
     */
    public function getNumberConverter()
    {
        return $this->numberConverter;
    }

    /**
     * Returns the random generator configured for this environment
     */
    public function getRandomGenerator()
    {
        return $this->randomGenerator;
    }

    /**
     * Returns the time converter configured for this environment
     */
    public function getTimeConverter()
    {
        return $this->timeConverter;
    }

    /**
     * Returns the time generator configured for this environment
     */
    public function getTimeGenerator()
    {
        return $this->timeGenerator;
    }

    /**
     * Returns the Unix Epoch time generator configured for this environment
     */
    public function getUnixTimeGenerator()
    {
        return $this->unixTimeGenerator;
    }

    /**
     * Returns the validator configured for this environment
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets the calculator to use in this environment
     * @param \Ramsey\Uuid\Math\CalculatorInterface $calculator
     */
    public function setCalculator($calculator)
    {
        $this->calculator = $calculator;
        $this->numberConverter = $this->buildNumberConverter($calculator);
        $this->timeConverter = $this->buildTimeConverter($calculator);

        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (isset($this->timeProvider)) {
            $this->timeGenerator = $this->buildTimeGenerator($this->timeProvider);
        }
    }

    /**
     * Sets the DCE Security provider to use in this environment
     * @param \Ramsey\Uuid\Provider\DceSecurityProviderInterface $dceSecurityProvider
     */
    public function setDceSecurityProvider($dceSecurityProvider)
    {
        $this->dceSecurityGenerator = $this->buildDceSecurityGenerator($dceSecurityProvider);
    }

    /**
     * Sets the node provider to use in this environment
     * @param \Ramsey\Uuid\Provider\NodeProviderInterface $nodeProvider
     */
    public function setNodeProvider($nodeProvider)
    {
        $this->nodeProvider = $nodeProvider;

        if (isset($this->timeProvider)) {
            $this->timeGenerator = $this->buildTimeGenerator($this->timeProvider);
        }
    }

    /**
     * Sets the time provider to use in this environment
     * @param \Ramsey\Uuid\Provider\TimeProviderInterface $timeProvider
     */
    public function setTimeProvider($timeProvider)
    {
        $this->timeProvider = $timeProvider;
        $this->timeGenerator = $this->buildTimeGenerator($timeProvider);
    }

    /**
     * Set the validator to use in this environment
     * @param \Ramsey\Uuid\Validator\ValidatorInterface $validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    /**
     * Returns a codec configured for this environment
     *
     * @param bool $useGuids Whether to build UUIDs using the GuidStringCodec
     */
    private function buildCodec($useGuids = false)
    {
        if ($useGuids) {
            return new GuidStringCodec($this->builder);
        }

        return new StringCodec($this->builder);
    }

    /**
     * Returns a DCE Security generator configured for this environment
     * @param \Ramsey\Uuid\Provider\DceSecurityProviderInterface $dceSecurityProvider
     */
    private function buildDceSecurityGenerator(
        $dceSecurityProvider
    ) {
        return new DceSecurityGenerator(
            $this->numberConverter,
            $this->timeGenerator,
            $dceSecurityProvider
        );
    }

    /**
     * Returns a node provider configured for this environment
     */
    private function buildNodeProvider()
    {
        if ($this->ignoreSystemNode) {
            return new RandomNodeProvider();
        }

        return new FallbackNodeProvider([
            new SystemNodeProvider(),
            new RandomNodeProvider(),
        ]);
    }

    /**
     * Returns a number converter configured for this environment
     * @param \Ramsey\Uuid\Math\CalculatorInterface $calculator
     */
    private function buildNumberConverter($calculator)
    {
        return new GenericNumberConverter($calculator);
    }

    /**
     * Returns a random generator configured for this environment
     */
    private function buildRandomGenerator()
    {
        if ($this->enablePecl) {
            return new PeclUuidRandomGenerator();
        }

        return (new RandomGeneratorFactory())->getGenerator();
    }

    /**
     * Returns a time generator configured for this environment
     *
     * @param TimeProviderInterface $timeProvider The time provider to use with
     *     the time generator
     */
    private function buildTimeGenerator($timeProvider)
    {
        if ($this->enablePecl) {
            return new PeclUuidTimeGenerator();
        }

        return (new TimeGeneratorFactory(
            $this->nodeProvider,
            $this->timeConverter,
            $timeProvider
        ))->getGenerator();
    }

    /**
     * Returns a Unix Epoch time generator configured for this environment
     */
    private function buildUnixTimeGenerator()
    {
        return new UnixTimeGenerator($this->randomGenerator);
    }

    /**
     * Returns a name generator configured for this environment
     */
    private function buildNameGenerator()
    {
        if ($this->enablePecl) {
            return new PeclUuidNameGenerator();
        }

        return (new NameGeneratorFactory())->getGenerator();
    }

    /**
     * Returns a time converter configured for this environment
     * @param \Ramsey\Uuid\Math\CalculatorInterface $calculator
     */
    private function buildTimeConverter($calculator)
    {
        $genericConverter = new GenericTimeConverter($calculator);

        if ($this->is64BitSystem()) {
            return new PhpTimeConverter($calculator, $genericConverter);
        }

        return $genericConverter;
    }

    /**
     * Returns a UUID builder configured for this environment
     *
     * @param bool $useGuids Whether to build UUIDs using the GuidStringCodec
     */
    private function buildUuidBuilder($useGuids = false)
    {
        if ($useGuids) {
            return new GuidBuilder($this->numberConverter, $this->timeConverter);
        }

        return new FallbackBuilder([
            new Rfc4122UuidBuilder($this->numberConverter, $this->timeConverter),
            new NonstandardUuidBuilder($this->numberConverter, $this->timeConverter),
        ]);
    }

    /**
     * Returns true if the PHP build is 64-bit
     */
    private function is64BitSystem()
    {
        return PHP_INT_SIZE === 8 && !$this->force32Bit;
    }
}
