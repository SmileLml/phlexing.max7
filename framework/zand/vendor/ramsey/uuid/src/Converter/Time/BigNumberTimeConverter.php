<?php

namespace Ramsey\Uuid\Converter\Time;

use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Math\BrickMathCalculator;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Time;

/**
 * Previously used to integrate moontoast/math as a bignum arithmetic library,
 * BigNumberTimeConverter is deprecated in favor of GenericTimeConverter
 *
 * @deprecated Transition to {@see GenericTimeConverter}.
 *
 * @psalm-immutable
 */
class BigNumberTimeConverter implements TimeConverterInterface
{
    /**
     * @var \Ramsey\Uuid\Converter\TimeConverterInterface
     */
    private $converter;

    public function __construct()
    {
        $this->converter = new GenericTimeConverter(new BrickMathCalculator());
    }

    /**
     * @param string $seconds
     * @param string $microseconds
     */
    public function calculateTime($seconds, $microseconds)
    {
        return $this->converter->calculateTime($seconds, $microseconds);
    }

    /**
     * @param \Ramsey\Uuid\Type\Hexadecimal $uuidTimestamp
     */
    public function convertTime($uuidTimestamp)
    {
        return $this->converter->convertTime($uuidTimestamp);
    }
}
