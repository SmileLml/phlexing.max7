<?php

namespace Ramsey\Uuid;

use DateTimeInterface;
use Ramsey\Uuid\Builder\UuidBuilderInterface;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Generator\DceSecurityGeneratorInterface;
use Ramsey\Uuid\Generator\DefaultTimeGenerator;
use Ramsey\Uuid\Generator\NameGeneratorInterface;
use Ramsey\Uuid\Generator\RandomGeneratorInterface;
use Ramsey\Uuid\Generator\TimeGeneratorInterface;
use Ramsey\Uuid\Generator\UnixTimeGenerator;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Provider\NodeProviderInterface;
use Ramsey\Uuid\Provider\Time\FixedTimeProvider;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\Type\Time;
use Ramsey\Uuid\Validator\ValidatorInterface;

use function bin2hex;
use function hex2bin;
use function pack;
use function str_pad;
use function strtolower;
use function substr;
use function substr_replace;
use function unpack;

use const STR_PAD_LEFT;

class UuidFactory implements UuidFactoryInterface
{
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
    private $uuidBuilder;
    /**
     * @var \Ramsey\Uuid\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @var bool whether the feature set was provided from outside, or we can
     *     operate under "default" assumptions
     */
    private $isDefaultFeatureSet;

    /**
     * @param FeatureSet|null $features A set of available features in the current environment
     */
    public function __construct($features = null)
    {
        $this->isDefaultFeatureSet = $features === null;

        $features = $features ?: new FeatureSet();

        $this->codec = $features->getCodec();
        $this->dceSecurityGenerator = $features->getDceSecurityGenerator();
        $this->nameGenerator = $features->getNameGenerator();
        $this->nodeProvider = $features->getNodeProvider();
        $this->numberConverter = $features->getNumberConverter();
        $this->randomGenerator = $features->getRandomGenerator();
        $this->timeConverter = $features->getTimeConverter();
        $this->timeGenerator = $features->getTimeGenerator();
        $this->uuidBuilder = $features->getBuilder();
        $this->validator = $features->getValidator();
        $this->unixTimeGenerator = $features->getUnixTimeGenerator();
    }

    /**
     * Returns the codec used by this factory
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * Sets the codec to use for this factory
     *
     * @param CodecInterface $codec A UUID encoder-decoder
     */
    public function setCodec($codec)
    {
        $this->isDefaultFeatureSet = false;

        $this->codec = $codec;
    }

    /**
     * Returns the name generator used by this factory
     */
    public function getNameGenerator()
    {
        return $this->nameGenerator;
    }

    /**
     * Sets the name generator to use for this factory
     *
     * @param NameGeneratorInterface $nameGenerator A generator to generate
     *     binary data, based on a namespace and name
     */
    public function setNameGenerator($nameGenerator)
    {
        $this->isDefaultFeatureSet = false;

        $this->nameGenerator = $nameGenerator;
    }

    /**
     * Returns the node provider used by this factory
     */
    public function getNodeProvider()
    {
        return $this->nodeProvider;
    }

    /**
     * Returns the random generator used by this factory
     */
    public function getRandomGenerator()
    {
        return $this->randomGenerator;
    }

    /**
     * Returns the time generator used by this factory
     */
    public function getTimeGenerator()
    {
        return $this->timeGenerator;
    }

    /**
     * Sets the time generator to use for this factory
     *
     * @param TimeGeneratorInterface $generator A generator to generate binary
     *     data, based on the time
     */
    public function setTimeGenerator($generator)
    {
        $this->isDefaultFeatureSet = false;

        $this->timeGenerator = $generator;
    }

    /**
     * Returns the DCE Security generator used by this factory
     */
    public function getDceSecurityGenerator()
    {
        return $this->dceSecurityGenerator;
    }

    /**
     * Sets the DCE Security generator to use for this factory
     *
     * @param DceSecurityGeneratorInterface $generator A generator to generate
     *     binary data, based on a local domain and local identifier
     */
    public function setDceSecurityGenerator($generator)
    {
        $this->isDefaultFeatureSet = false;

        $this->dceSecurityGenerator = $generator;
    }

    /**
     * Returns the number converter used by this factory
     */
    public function getNumberConverter()
    {
        return $this->numberConverter;
    }

    /**
     * Sets the random generator to use for this factory
     *
     * @param RandomGeneratorInterface $generator A generator to generate binary
     *     data, based on some random input
     */
    public function setRandomGenerator($generator)
    {
        $this->isDefaultFeatureSet = false;

        $this->randomGenerator = $generator;
    }

    /**
     * Sets the number converter to use for this factory
     *
     * @param NumberConverterInterface $converter A converter to use for working
     *     with large integers (i.e. integers greater than PHP_INT_MAX)
     */
    public function setNumberConverter($converter)
    {
        $this->isDefaultFeatureSet = false;

        $this->numberConverter = $converter;
    }

    /**
     * Returns the UUID builder used by this factory
     */
    public function getUuidBuilder()
    {
        return $this->uuidBuilder;
    }

    /**
     * Sets the UUID builder to use for this factory
     *
     * @param UuidBuilderInterface $builder A builder for constructing instances
     *     of UuidInterface
     */
    public function setUuidBuilder($builder)
    {
        $this->isDefaultFeatureSet = false;

        $this->uuidBuilder = $builder;
    }

    /**
     * @psalm-mutation-free
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets the validator to use for this factory
     *
     * @param ValidatorInterface $validator A validator to use for validating
     *     whether a string is a valid UUID
     */
    public function setValidator($validator)
    {
        $this->isDefaultFeatureSet = false;

        $this->validator = $validator;
    }

    /**
     * @psalm-pure
     * @param string $bytes
     */
    public function fromBytes($bytes)
    {
        return $this->codec->decodeBytes($bytes);
    }

    /**
     * @psalm-pure
     * @param string $uuid
     */
    public function fromString($uuid)
    {
        $uuid = strtolower($uuid);

        return $this->codec->decode($uuid);
    }

    /**
     * @psalm-pure
     * @param string $integer
     */
    public function fromInteger($integer)
    {
        $hex = $this->numberConverter->toHex($integer);
        $hex = str_pad($hex, 32, '0', STR_PAD_LEFT);

        return $this->fromString($hex);
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param \Ramsey\Uuid\Type\Hexadecimal|null $node
     * @param int|null $clockSeq
     */
    public function fromDateTime(
        $dateTime,
        $node = null,
        $clockSeq = null
    ) {
        $timeProvider = new FixedTimeProvider(
            new Time($dateTime->format('U'), $dateTime->format('u'))
        );

        $timeGenerator = new DefaultTimeGenerator(
            $this->nodeProvider,
            $this->timeConverter,
            $timeProvider
        );

        $nodeHex = $node ? $node->toString() : null;

        $bytes = $timeGenerator->generate($nodeHex, $clockSeq);

        return $this->uuidFromBytesAndVersion($bytes, Uuid::UUID_TYPE_TIME);
    }

    /**
     * @psalm-pure
     * @param \Ramsey\Uuid\Type\Hexadecimal $hex
     */
    public function fromHexadecimal($hex)
    {
        return $this->codec->decode($hex->__toString());
    }

    /**
     * @inheritDoc
     * @param int|null $clockSeq
     */
    public function uuid1($node = null, $clockSeq = null)
    {
        $bytes = $this->timeGenerator->generate($node, $clockSeq);

        return $this->uuidFromBytesAndVersion($bytes, Uuid::UUID_TYPE_TIME);
    }

    /**
     * @param int $localDomain
     * @param IntegerObject|null $localIdentifier
     * @param \Ramsey\Uuid\Type\Hexadecimal|null $node
     * @param int|null $clockSeq
     */
    public function uuid2(
        $localDomain,
        $localIdentifier = null,
        $node = null,
        $clockSeq = null
    ) {
        $bytes = $this->dceSecurityGenerator->generate(
            $localDomain,
            $localIdentifier,
            $node,
            $clockSeq
        );

        return $this->uuidFromBytesAndVersion($bytes, Uuid::UUID_TYPE_DCE_SECURITY);
    }

    /**
     * @inheritDoc
     * @psalm-pure
     * @param string $name
     */
    public function uuid3($ns, $name)
    {
        return $this->uuidFromNsAndName($ns, $name, Uuid::UUID_TYPE_HASH_MD5, 'md5');
    }

    public function uuid4()
    {
        $bytes = $this->randomGenerator->generate(16);

        return $this->uuidFromBytesAndVersion($bytes, Uuid::UUID_TYPE_RANDOM);
    }

    /**
     * @inheritDoc
     * @psalm-pure
     * @param string $name
     */
    public function uuid5($ns, $name)
    {
        return $this->uuidFromNsAndName($ns, $name, Uuid::UUID_TYPE_HASH_SHA1, 'sha1');
    }

    /**
     * @param \Ramsey\Uuid\Type\Hexadecimal|null $node
     * @param int|null $clockSeq
     */
    public function uuid6($node = null, $clockSeq = null)
    {
        $nodeHex = $node ? $node->toString() : null;
        $bytes = $this->timeGenerator->generate($nodeHex, $clockSeq);

        // Rearrange the bytes, according to the UUID version 6 specification.
        $v6 = $bytes[6] . $bytes[7] . $bytes[4] . $bytes[5]
            . $bytes[0] . $bytes[1] . $bytes[2] . $bytes[3];
        $v6 = bin2hex($v6);

        // Drop the first four bits, while adding an empty four bits for the
        // version field. This allows us to reconstruct the correct time from
        // the bytes of this UUID.
        $v6Bytes = hex2bin(substr($v6, 1, 12) . '0' . substr($v6, -3));
        $v6Bytes .= substr($bytes, 8);

        return $this->uuidFromBytesAndVersion($v6Bytes, Uuid::UUID_TYPE_REORDERED_TIME);
    }

    /**
     * Returns a version 7 (Unix Epoch time) UUID
     *
     * @param DateTimeInterface|null $dateTime An optional date/time from which
     *     to create the version 7 UUID. If not provided, the UUID is generated
     *     using the current date/time.
     *
     * @return UuidInterface A UuidInterface instance that represents a
     *     version 7 UUID
     */
    public function uuid7($dateTime = null)
    {
        assert($this->unixTimeGenerator instanceof UnixTimeGenerator);
        $bytes = $this->unixTimeGenerator->generate(null, null, $dateTime);

        return $this->uuidFromBytesAndVersion($bytes, Uuid::UUID_TYPE_UNIX_TIME);
    }

    /**
     * Returns a version 8 (Custom) UUID
     *
     * The bytes provided may contain any value according to your application's
     * needs. Be aware, however, that other applications may not understand the
     * semantics of the value.
     *
     * @param string $bytes A 16-byte octet string. This is an open blob
     *     of data that you may fill with 128 bits of information. Be aware,
     *     however, bits 48 through 51 will be replaced with the UUID version
     *     field, and bits 64 and 65 will be replaced with the UUID variant. You
     *     MUST NOT rely on these bits for your application needs.
     *
     * @return UuidInterface A UuidInterface instance that represents a
     *     version 8 UUID
     */
    public function uuid8($bytes)
    {
        return $this->uuidFromBytesAndVersion($bytes, Uuid::UUID_TYPE_CUSTOM);
    }

    /**
     * Returns a Uuid created from the provided byte string
     *
     * Uses the configured builder and codec and the provided byte string to
     * construct a Uuid object.
     *
     * @param string $bytes The byte string from which to construct a UUID
     *
     * @return UuidInterface An instance of UuidInterface, created from the
     *     provided bytes
     *
     * @psalm-pure
     */
    public function uuid($bytes)
    {
        /** @psalm-suppress ImpurePropertyFetch */
        return $this->uuidBuilder->build($this->codec, $bytes);
    }

    /**
     * Returns a version 3 or 5 namespaced Uuid
     *
     * @param string|UuidInterface $ns The namespace (must be a valid UUID)
     * @param string $name The name to hash together with the namespace
     * @param int $version The version of UUID to create (3 or 5)
     * @param string $hashAlgorithm The hashing algorithm to use when hashing
     *     together the namespace and name
     *
     * @return UuidInterface An instance of UuidInterface, created by hashing
     *     together the provided namespace and name
     *
     * @psalm-pure
     */
    private function uuidFromNsAndName(
        $ns,
        $name,
        $version,
        $hashAlgorithm
    ) {
        if (!($ns instanceof UuidInterface)) {
            $ns = $this->fromString($ns);
        }

        $bytes = $this->nameGenerator->generate($ns, $name, $hashAlgorithm);

        return $this->uuidFromBytesAndVersion(substr($bytes, 0, 16), $version);
    }

    /**
     * Returns an RFC 4122 variant Uuid, created from the provided bytes and version
     *
     * @param string $bytes The byte string to convert to a UUID
     * @param int $version The RFC 4122 version to apply to the UUID
     *
     * @return UuidInterface An instance of UuidInterface, created from the
     *     byte string and version
     *
     * @psalm-pure
     */
    private function uuidFromBytesAndVersion($bytes, $version)
    {
        /** @var array $unpackedTime */
        $unpackedTime = unpack('n*', substr($bytes, 6, 2));
        $timeHi = (int) $unpackedTime[1];
        $timeHiAndVersion = pack('n*', BinaryUtils::applyVersion($timeHi, $version));

        /** @var array $unpackedClockSeq */
        $unpackedClockSeq = unpack('n*', substr($bytes, 8, 2));
        $clockSeqHi = (int) $unpackedClockSeq[1];
        $clockSeqHiAndReserved = pack('n*', BinaryUtils::applyVariant($clockSeqHi));

        $bytes = substr_replace($bytes, $timeHiAndVersion, 6, 2);
        $bytes = substr_replace($bytes, $clockSeqHiAndReserved, 8, 2);

        if ($this->isDefaultFeatureSet) {
            return LazyUuidFromString::fromBytes($bytes);
        }

        /** @psalm-suppress ImpureVariable */
        return $this->uuid($bytes);
    }
}
