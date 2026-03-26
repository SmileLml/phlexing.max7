<?php

namespace Ramsey\Uuid\Converter\Time;

use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Math\CalculatorInterface;
use Ramsey\Uuid\Math\RoundingMode;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\Type\Time;

use function explode;
use function str_pad;

use const STR_PAD_LEFT;

/**
 * GenericTimeConverter uses the provided calculator to calculate and convert
 * time values
 *
 * @psalm-immutable
 */
class GenericTimeConverter implements TimeConverterInterface
{
    /**
     * The number of 100-nanosecond intervals from the Gregorian calendar epoch
     * to the Unix epoch.
     */
    private const GREGORIAN_TO_UNIX_INTERVALS = '122192928000000000';

    /**
     * The number of 100-nanosecond intervals in one second.
     */
    private const SECOND_INTERVALS = '10000000';

    /**
     * The number of 100-nanosecond intervals in one microsecond.
     */
    private const MICROSECOND_INTERVALS = '10';
    /**
     * @var \Ramsey\Uuid\Math\CalculatorInterface
     */
    private $calculator;

    /**
     * @param \Ramsey\Uuid\Math\CalculatorInterface $calculator
     */
    public function __construct($calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param string $seconds
     * @param string $microseconds
     */
    public function calculateTime($seconds, $microseconds)
    {
        $timestamp = new Time($seconds, $microseconds);

        // Convert the seconds into a count of 100-nanosecond intervals.
        $sec = $this->calculator->multiply(
            $timestamp->getSeconds(),
            new IntegerObject(self::SECOND_INTERVALS)
        );

        // Convert the microseconds into a count of 100-nanosecond intervals.
        $usec = $this->calculator->multiply(
            $timestamp->getMicroseconds(),
            new IntegerObject(self::MICROSECOND_INTERVALS)
        );

        // Combine the seconds and microseconds intervals and add the count of
        // 100-nanosecond intervals from the Gregorian calendar epoch to the
        // Unix epoch. This gives us the correct count of 100-nanosecond
        // intervals since the Gregorian calendar epoch for the given seconds
        // and microseconds.
        /** @var IntegerObject $uuidTime */
        $uuidTime = $this->calculator->add(
            $sec,
            $usec,
            new IntegerObject(self::GREGORIAN_TO_UNIX_INTERVALS)
        );

        $uuidTimeHex = str_pad(
            $this->calculator->toHexadecimal($uuidTime)->toString(),
            16,
            '0',
            STR_PAD_LEFT
        );

        return new Hexadecimal($uuidTimeHex);
    }

    /**
     * @param \Ramsey\Uuid\Type\Hexadecimal $uuidTimestamp
     */
    public function convertTime($uuidTimestamp)
    {
        // From the total, subtract the number of 100-nanosecond intervals from
        // the Gregorian calendar epoch to the Unix epoch. This gives us the
        // number of 100-nanosecond intervals from the Unix epoch, which also
        // includes the microtime.
        $epochNanoseconds = $this->calculator->subtract(
            $this->calculator->toInteger($uuidTimestamp),
            new IntegerObject(self::GREGORIAN_TO_UNIX_INTERVALS)
        );

        // Convert the 100-nanosecond intervals into seconds and microseconds.
        $unixTimestamp = $this->calculator->divide(
            RoundingMode::HALF_UP,
            6,
            $epochNanoseconds,
            new IntegerObject(self::SECOND_INTERVALS)
        );

        $split = explode('.', (string) $unixTimestamp, 2);

        return new Time($split[0], $split[1] ?? 0);
    }
}
