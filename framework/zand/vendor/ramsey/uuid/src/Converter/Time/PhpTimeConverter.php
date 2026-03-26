<?php

namespace Ramsey\Uuid\Converter\Time;

use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Math\BrickMathCalculator;
use Ramsey\Uuid\Math\CalculatorInterface;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\Type\Time;

use function count;
use function dechex;
use function explode;
use function is_float;
use function is_int;
use function str_pad;
use function strlen;
use function substr;

use const STR_PAD_LEFT;
use const STR_PAD_RIGHT;

/**
 * PhpTimeConverter uses built-in PHP functions and standard math operations
 * available to the PHP programming language to provide facilities for
 * converting parts of time into representations that may be used in UUIDs
 *
 * @psalm-immutable
 */
class PhpTimeConverter implements TimeConverterInterface
{
    /**
     * The number of 100-nanosecond intervals from the Gregorian calendar epoch
     * to the Unix epoch.
     */
    private const GREGORIAN_TO_UNIX_INTERVALS = 0x01b21dd213814000;

    /**
     * The number of 100-nanosecond intervals in one second.
     */
    private const SECOND_INTERVALS = 10000000;

    /**
     * The number of 100-nanosecond intervals in one microsecond.
     */
    private const MICROSECOND_INTERVALS = 10;

    /**
     * @var int
     */
    private $phpPrecision;
    /**
     * @var \Ramsey\Uuid\Math\CalculatorInterface
     */
    private $calculator;
    /**
     * @var \Ramsey\Uuid\Converter\TimeConverterInterface
     */
    private $fallbackConverter;

    /**
     * @param \Ramsey\Uuid\Math\CalculatorInterface|null $calculator
     * @param \Ramsey\Uuid\Converter\TimeConverterInterface|null $fallbackConverter
     */
    public function __construct(
        $calculator = null,
        $fallbackConverter = null
    ) {
        if ($calculator === null) {
            $calculator = new BrickMathCalculator();
        }

        if ($fallbackConverter === null) {
            $fallbackConverter = new GenericTimeConverter($calculator);
        }

        $this->calculator = $calculator;
        $this->fallbackConverter = $fallbackConverter;
        $this->phpPrecision = (int) ini_get('precision');
    }

    /**
     * @param string $seconds
     * @param string $microseconds
     */
    public function calculateTime($seconds, $microseconds)
    {
        $seconds = new IntegerObject($seconds);
        $microseconds = new IntegerObject($microseconds);

        // Calculate the count of 100-nanosecond intervals since the Gregorian
        // calendar epoch for the given seconds and microseconds.
        $uuidTime = ((int) $seconds->toString() * self::SECOND_INTERVALS)
            + ((int) $microseconds->toString() * self::MICROSECOND_INTERVALS)
            + self::GREGORIAN_TO_UNIX_INTERVALS;

        // Check to see whether we've overflowed the max/min integer size.
        // If so, we will default to a different time converter.
        /** @psalm-suppress RedundantCondition */
        if (!is_int($uuidTime)) {
            return $this->fallbackConverter->calculateTime(
                $seconds->toString(),
                $microseconds->toString()
            );
        }

        return new Hexadecimal(str_pad(dechex($uuidTime), 16, '0', STR_PAD_LEFT));
    }

    /**
     * @param \Ramsey\Uuid\Type\Hexadecimal $uuidTimestamp
     */
    public function convertTime($uuidTimestamp)
    {
        $timestamp = $this->calculator->toInteger($uuidTimestamp);

        // Convert the 100-nanosecond intervals into seconds and microseconds.
        $splitTime = $this->splitTime(
            ((int) $timestamp->toString() - self::GREGORIAN_TO_UNIX_INTERVALS)
            / self::SECOND_INTERVALS
        );

        if (count($splitTime) === 0) {
            return $this->fallbackConverter->convertTime($uuidTimestamp);
        }

        return new Time($splitTime['sec'], $splitTime['usec']);
    }

    /**
     * @param float|int $time The time to split into seconds and microseconds
     *
     * @return string[]
     */
    private function splitTime($time)
    {
        $split = explode('.', (string) $time, 2);

        // If the $time value is a float but $split only has 1 element, then the
        // float math was rounded up to the next second, so we want to return
        // an empty array to allow use of the fallback converter.
        if (is_float($time) && count($split) === 1) {
            return [];
        }

        if (count($split) === 1) {
            return [
                'sec' => $split[0],
                'usec' => '0',
            ];
        }

        // If the microseconds are less than six characters AND the length of
        // the number is greater than or equal to the PHP precision, then it's
        // possible that we lost some precision for the microseconds. Return an
        // empty array, so that we can choose to use the fallback converter.
        if (strlen($split[1]) < 6 && strlen((string) $time) >= $this->phpPrecision) {
            return [];
        }

        $microseconds = $split[1];

        // Ensure the microseconds are no longer than 6 digits. If they are,
        // truncate the number to the first 6 digits and round up, if needed.
        if (strlen($microseconds) > 6) {
            $roundingDigit = (int) substr($microseconds, 6, 1);
            $microseconds = (int) substr($microseconds, 0, 6);

            if ($roundingDigit >= 5) {
                $microseconds++;
            }
        }

        return [
            'sec' => $split[0],
            'usec' => str_pad((string) $microseconds, 6, '0', STR_PAD_RIGHT),
        ];
    }
}
