<?php

namespace Ramsey\Uuid\Lazy;

use DateTimeInterface;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Exception\UnsupportedOperationException;
use Ramsey\Uuid\Fields\FieldsInterface;
use Ramsey\Uuid\Rfc4122\UuidV1;
use Ramsey\Uuid\Rfc4122\UuidV6;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;
use ValueError;

use function assert;
use function bin2hex;
use function hex2bin;
use function sprintf;
use function str_replace;
use function substr;

/**
 * Lazy version of a UUID: its format has not been determined yet, so it is mostly only usable for string/bytes
 * conversion. This object optimizes instantiation, serialization and string conversion time, at the cost of
 * increased overhead for more advanced UUID operations.
 *
 * @internal this type is used internally for performance reasons, and is not supposed to be directly referenced
 *           in consumer libraries.
 *
 * @psalm-immutable
 *
 * Note: the {@see FieldsInterface} does not declare methods that deprecated API
 *        relies upon: the API has been ported from the {@see \Ramsey\Uuid\Uuid} definition,
 *        and is deprecated anyway.
 * Note: the deprecated API from {@see \Ramsey\Uuid\Uuid} is in use here (on purpose): it will be removed
 *       once the deprecated API is gone from this class too.
 *
 * @psalm-suppress UndefinedInterfaceMethod
 * @psalm-suppress DeprecatedMethod
 */
final class LazyUuidFromString implements UuidInterface
{
    public const VALID_REGEX = '/\A[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\z/ms';

    /**
     * @var \Ramsey\Uuid\UuidInterface|null
     */
    private $unwrapped;
    /**
     * @var string
     */
    private $uuid;

    /**
     * @psalm-param non-empty-string $uuid
     * @param string $uuid
     */
    public function __construct($uuid)
    {
        $this->uuid = $uuid;
    }

    /** @psalm-pure
     * @param string $bytes */
    public static function fromBytes($bytes)
    {
        $base16Uuid = bin2hex($bytes);

        return new self(
            substr($base16Uuid, 0, 8)
            . '-'
            . substr($base16Uuid, 8, 4)
            . '-'
            . substr($base16Uuid, 12, 4)
            . '-'
            . substr($base16Uuid, 16, 4)
            . '-'
            . substr($base16Uuid, 20, 12)
        );
    }

    public function serialize()
    {
        return $this->uuid;
    }

    /**
     * @return array{string: string}
     *
     * @psalm-return array{string: non-empty-string}
     */
    public function __serialize()
    {
        return ['string' => $this->uuid];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $data
     *
     * @psalm-param non-empty-string $data
     */
    public function unserialize(string $data)
    {
        $this->uuid = $data;
    }

    /**
     * @param mixed[] $data
     *
     * @psalm-param array{string?: non-empty-string} $data
     * @psalm-suppress UnusedMethodCall
     */
    public function __unserialize($data)
    {
        // @codeCoverageIgnoreStart
        if (!isset($data['string'])) {
            throw new ValueError(sprintf('%s(): Argument #1 ($data) is invalid', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        $this->unserialize($data['string']);
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getNumberConverter()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getNumberConverter();
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function getFieldsHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getFieldsHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getClockSeqHiAndReservedHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getClockSeqHiAndReservedHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getClockSeqLowHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getClockSeqLowHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getClockSequenceHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getClockSequenceHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getDateTime()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getDateTime();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getLeastSignificantBitsHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getLeastSignificantBitsHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getMostSignificantBitsHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getMostSignificantBitsHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getNodeHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getNodeHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getTimeHiAndVersionHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getTimeHiAndVersionHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getTimeLowHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getTimeLowHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getTimeMidHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getTimeMidHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getTimestampHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getTimestampHex();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getUrn()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getUrn();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getVariant()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getVariant();
    }

    /** @psalm-suppress DeprecatedMethod */
    public function getVersion()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getVersion();
    }

    /**
     * @param \Ramsey\Uuid\UuidInterface $other
     */
    public function compareTo($other)
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->compareTo($other);
    }

    /**
     * @param object|null $other
     */
    public function equals($other)
    {
        if (! $other instanceof UuidInterface) {
            return false;
        }

        return $this->uuid === $other->toString();
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement we know that {@see self::$uuid} is a non-empty string, so
     *                                             we know that {@see hex2bin} will retrieve a non-empty string too.
     */
    public function getBytes()
    {
        /** @phpstan-ignore-next-line PHPStan complains that this is not a non-empty-string. */
        return (string) hex2bin(str_replace('-', '', $this->uuid));
    }

    public function getFields()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getFields();
    }

    public function getHex()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getHex();
    }

    public function getInteger()
    {
        return ($this->unwrapped ?? $this->unwrap())
            ->getInteger();
    }

    public function toString()
    {
        return $this->uuid;
    }

    public function __toString()
    {
        return $this->uuid;
    }

    public function jsonSerialize()
    {
        return $this->uuid;
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getClockSeqHiAndReserved()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getClockSeqHiAndReserved()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getClockSeqHiAndReserved()
                    ->toString()
            );
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getClockSeqLow()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getClockSeqLow()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getClockSeqLow()
                    ->toString()
            );
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getClockSeq()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getClockSequence()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getClockSeq()
                    ->toString()
            );
    }

    /**
     * @deprecated This method will be removed in 5.0.0. There is no direct
     *     alternative, but the same information may be obtained by splitting
     *     in half the value returned by {@see UuidInterface::getHex()}.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getLeastSignificantBits()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(substr($instance->getHex()->toString(), 16));
    }

    /**
     * @deprecated This method will be removed in 5.0.0. There is no direct
     *     alternative, but the same information may be obtained by splitting
     *     in half the value returned by {@see UuidInterface::getHex()}.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getMostSignificantBits()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(substr($instance->getHex()->toString(), 0, 16));
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getNode()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getNode()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getNode()
                    ->toString()
            );
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getTimeHiAndVersion()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getTimeHiAndVersion()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getTimeHiAndVersion()
                    ->toString()
            );
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getTimeLow()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getTimeLow()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getTimeLow()
                    ->toString()
            );
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getTimeMid()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getTimeMid()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        return $instance->getNumberConverter()
            ->fromHex(
                $instance->getFields()
                    ->getTimeMid()
                    ->toString()
            );
    }

    /**
     * @deprecated Use {@see UuidInterface::getFields()} to get a
     *     {@see FieldsInterface} instance. If it is a {@see Rfc4122FieldsInterface}
     *     instance, you may call {@see Rfc4122FieldsInterface::getTimestamp()}
     *     and use the arbitrary-precision math library of your choice to
     *     convert it to a string integer.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress DeprecatedMethod
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     */
    public function getTimestamp()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());
        $fields = $instance->getFields();

        if ($fields->getVersion() !== 1) {
            throw new UnsupportedOperationException('Not a time-based UUID');
        }

        return $instance->getNumberConverter()
            ->fromHex($fields->getTimestamp()->toString());
    }

    public function toUuidV1()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        if ($instance instanceof UuidV1) {
            return $instance;
        }

        assert($instance instanceof UuidV6);

        return $instance->toUuidV1();
    }

    public function toUuidV6()
    {
        $instance = ($this->unwrapped ?? $this->unwrap());

        assert($instance instanceof UuidV6);

        return $instance;
    }

    /**
     * @psalm-suppress ImpureMethodCall the retrieval of the factory is a clear violation of purity here: this is a
     *                                  known pitfall of the design of this library, where a value object contains
     *                                  a mutable reference to a factory. We use a fixed factory here, so the violation
     *                                  will not have real-world effects, as this object is only instantiated with the
     *                                  default factory settings/features.
     * @psalm-suppress InaccessibleProperty property {@see $unwrapped} is used as a cache: we don't expose it to the
     *                                      outside world, so we should be fine here.
     */
    private function unwrap()
    {
        return $this->unwrapped = (new UuidFactory())
            ->fromString($this->uuid);
    }
}
